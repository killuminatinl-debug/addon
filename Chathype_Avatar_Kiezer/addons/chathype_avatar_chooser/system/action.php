<?php
header('X-Content-Type-Options: nosniff');
header('Content-Type: text/plain; charset=utf-8');

$load_addons = 'chathype_avatar_chooser';
require(__DIR__ . '/../../../system/config_session.php');
require_once __DIR__ . '/settings.php';

/* Addonbeheer: alleen bevoegde beheerders mogen de instelling wijzigen. */
if (isset($_POST['save_avatar_setting'])) {
    $isAdmin = false;
    if (function_exists('canManageAddons')) {
        $isAdmin = (bool)canManageAddons();
    }
    if (!$isAdmin && function_exists('isAdmin')) {
        $isAdmin = (bool)isAdmin(isset($data) && is_array($data) ? $data : array());
    }
    if (!$isAdmin && isset($data['user_rank']) && is_numeric($data['user_rank'])) {
        $isAdmin = ((int)$data['user_rank'] >= 8);
    }
    if (!$isAdmin || !isset($_POST['chathype_avatar_enabled']) || !is_scalar($_POST['chathype_avatar_enabled'])) {
        http_response_code(403);
        exit('AVATAR_ERROR:NOT_AUTHORIZED');
    }
    $enabled = ((string)$_POST['chathype_avatar_enabled'] === '1') ? '1' : '0';
    $stmt = $mysqli->prepare("UPDATE boom_addons SET custom1 = ? WHERE addons = 'chathype_avatar_chooser' LIMIT 1");
    if (!$stmt) {
        http_response_code(500);
        exit('AVATAR_ERROR:SETTING_QUERY_FAILED');
    }
    $stmt->bind_param('s', $enabled);
    $ok = $stmt->execute();
    $stmt->close();
    if ($ok && function_exists('redisUpdateAddons')) {
        redisUpdateAddons('chathype_avatar_chooser');
    }
    if (!$ok) {
        http_response_code(500);
        exit('AVATAR_ERROR:SETTING_SAVE_FAILED');
    }
    exit('5');
}

/* Opslaan van de avatar van de ingelogde gebruiker. */
if (!chathypeAvatarEnabled()) {
    http_response_code(403);
    exit('AVATAR_ERROR:ADDON_DISABLED');
}
if (empty($data['user_id'])) {
    http_response_code(401);
    exit('AVATAR_ERROR:LOGIN_REQUIRED');
}
if (!isset($_POST['save_custom_avatar'])) {
    http_response_code(400);
    exit('AVATAR_ERROR:INVALID_REQUEST');
}
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
    http_response_code(500);
    exit('AVATAR_ERROR:DATABASE_UNAVAILABLE');
}

$imageData = isset($_POST['image']) && is_string($_POST['image']) ? $_POST['image'] : '';
if ($imageData === '' || strlen($imageData) > 1800000 ||
    !preg_match('#^data:image/png;base64,([A-Za-z0-9+/=]+)$#D', $imageData, $matches)) {
    http_response_code(400);
    exit('AVATAR_ERROR:INVALID_IMAGE_DATA');
}

$png = base64_decode($matches[1], true);
if ($png === false || strlen($png) < 100 || strlen($png) > 1300000 ||
    substr($png, 0, 8) !== "\x89PNG\r\n\x1a\n") {
    http_response_code(400);
    exit('AVATAR_ERROR:INVALID_PNG');
}
if (!function_exists('getimagesizefromstring')) {
    http_response_code(500);
    exit('AVATAR_ERROR:PHP_IMAGE_FUNCTION_UNAVAILABLE');
}
$info = @getimagesizefromstring($png);
if (!$info || (int)$info[0] !== 360 || (int)$info[1] !== 360 || (int)$info[2] !== IMAGETYPE_PNG) {
    http_response_code(400);
    exit('AVATAR_ERROR:INVALID_IMAGE_DIMENSIONS');
}

$avatarDir = realpath(__DIR__ . '/../../../avatar');
if (!$avatarDir || !is_dir($avatarDir)) {
    $candidateDirs = array(
        realpath(__DIR__ . '/../../../upload/avatar'),
        realpath(__DIR__ . '/../../../uploads/avatar'),
        realpath(__DIR__ . '/../../../avatar')
    );
    $avatarDir = false;
    foreach ($candidateDirs as $candidate) {
        if ($candidate && is_dir($candidate)) { $avatarDir = $candidate; break; }
    }
}
if (!$avatarDir || !is_dir($avatarDir)) {
    http_response_code(500);
    exit('AVATAR_ERROR:AVATAR_FOLDER_NOT_FOUND');
}
if (!is_writable($avatarDir)) {
    http_response_code(500);
    exit('AVATAR_ERROR:AVATAR_FOLDER_NOT_WRITABLE');
}

$uid = (int)$data['user_id'];
$filename = 'chathype_custom_' . $uid . '.png';
$target = $avatarDir . DIRECTORY_SEPARATOR . $filename;
$tmp = $target . '.' . uniqid('tmp', true);

if (@file_put_contents($tmp, $png, LOCK_EX) === false) {
    @unlink($tmp);
    http_response_code(500);
    exit('AVATAR_ERROR:IMAGE_FILE_WRITE_FAILED');
}
if (!@rename($tmp, $target)) {
    @unlink($tmp);
    http_response_code(500);
    exit('AVATAR_ERROR:IMAGE_FILE_RENAME_FAILED');
}

$stmt = $mysqli->prepare('UPDATE boom_users SET user_tumb = ? WHERE user_id = ? LIMIT 1');
if (!$stmt) {
    http_response_code(500);
    exit('AVATAR_ERROR:USER_QUERY_FAILED');
}
$stmt->bind_param('si', $filename, $uid);
$ok = $stmt->execute();
$changed = $stmt->affected_rows;
$stmt->close();

if (!$ok) {
    http_response_code(500);
    exit('AVATAR_ERROR:USER_AVATAR_UPDATE_FAILED');
}
if (function_exists('redisUpdateUser')) {
    redisUpdateUser($uid);
}
exit('5');
