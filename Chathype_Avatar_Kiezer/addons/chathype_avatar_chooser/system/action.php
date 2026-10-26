<?php
header('X-Content-Type-Options: nosniff');
$load_addons = 'chathype_avatar_chooser';
require(__DIR__ . '/../../../system/config_addons.php');
require_once __DIR__ . '/settings.php';

/* Admin setting: use CodyChat's permission helper, with the same rank fallback as other addon admin screens. */
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
        exit('0');
    }
    $enabled = ((string)$_POST['chathype_avatar_enabled'] === '1') ? '1' : '0';
    $stmt = $mysqli->prepare("UPDATE boom_addons SET custom1 = ? WHERE addons = 'chathype_avatar_chooser' LIMIT 1");
    if (!$stmt) {
        http_response_code(500);
        exit('0');
    }
    $stmt->bind_param('s', $enabled);
    $ok = $stmt->execute() && ($stmt->affected_rows >= 0);
    $stmt->close();
    if ($ok && function_exists('redisUpdateAddons')) {
        redisUpdateAddons('chathype_avatar_chooser');
    }
    header('Content-Type: text/plain; charset=utf-8');
    echo $ok ? '5' : '0';
    exit;
}

/* Member-created avatar: accept only a valid PNG data URL from the logged-in user. */
if (!chathypeAvatarEnabled() || empty($data['user_id']) || !isset($_POST['save_custom_avatar'])) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    exit('AVATAR_ERROR:SESSION_OR_DISABLED');
}

$imageData = isset($_POST['image']) ? (string)$_POST['image'] : '';
if (strlen($imageData) > 1800000 ||
    !preg_match('#^data:image/png;base64,([A-Za-z0-9+/=]+)$#D', $imageData, $matches)) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=utf-8');
    exit('AVATAR_ERROR:INVALID_IMAGE_DATA');
}

$png = base64_decode($matches[1], true);
if ($png === false || strlen($png) < 100 || strlen($png) > 1300000 || substr($png, 0, 8) !== "\x89PNG\r\n\x1a\n") {
    http_response_code(400);
    exit('0');
}

$info = @getimagesizefromstring($png);
if (!$info || $info[0] !== 360 || $info[1] !== 360 || $info[2] !== IMAGETYPE_PNG) {
    http_response_code(400);
    exit('0');
}

$avatarDir = realpath(__DIR__ . '/../../../avatar');
if (!$avatarDir || !is_dir($avatarDir) || !is_writable($avatarDir)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    exit('AVATAR_ERROR:SERVER_OR_FOLDER_PERMISSION');
}

$uid = (int)$data['user_id'];
$filename = 'chathype_custom_' . $uid . '.png';
$target = $avatarDir . DIRECTORY_SEPARATOR . $filename;
$tmp = $target . '.tmp';
if (@file_put_contents($tmp, $png, LOCK_EX) === false) {
    @unlink($tmp);
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    exit('AVATAR_ERROR:DATABASE_UPDATE_FAILED');
}
if (!@rename($tmp, $target)) {
    @unlink($tmp);
    http_response_code(500);
    exit('0');
}

$stmt = $mysqli->prepare('UPDATE boom_users SET user_tumb = ? WHERE user_id = ? LIMIT 1');
if (!$stmt) {
    http_response_code(500);
    exit('0');
}
$stmt->bind_param('si', $filename, $uid);
$ok = $stmt->execute();
$stmt->close();
if (!$ok) {
    http_response_code(500);
    exit('0');
}
if (function_exists('redisUpdateUser')) {
    redisUpdateUser($uid);
}
header('Content-Type: text/plain; charset=utf-8');
echo '5';
