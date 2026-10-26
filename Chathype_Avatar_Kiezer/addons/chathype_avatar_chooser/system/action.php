<?php
/*
 * Chathype Avatar Kiezer — native CodyChat 11 actions.
 * Admin save follows the working Chathype Avatar Creator admin action pattern.
 */
header('X-Content-Type-Options: nosniff');
header('Content-Type: text/plain; charset=utf-8');

$load_addons = 'chathype_avatar_chooser';
require(__DIR__ . '/../../../system/config_addons.php');
require_once __DIR__ . '/settings.php';

if (isset($_POST['save_avatar_setting'])) {
    if (!function_exists('canManageAddons') || !canManageAddons() ||
        !isset($_POST['chathype_avatar_enabled']) || !is_scalar($_POST['chathype_avatar_enabled'])) {
        http_response_code(403);
        exit('AVATAR_SETTING_ERROR:PERMISSION');
    }
    if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
        http_response_code(500);
        exit('AVATAR_SETTING_ERROR:DATABASE');
    }

    $enabled = ((string)$_POST['chathype_avatar_enabled'] === '1') ? '1' : '0';

    $check = $mysqli->prepare("SELECT addons FROM boom_addons WHERE addons = 'chathype_avatar_chooser' LIMIT 1");
    if (!$check || !$check->execute()) {
        if ($check) $check->close();
        http_response_code(500);
        exit('AVATAR_SETTING_ERROR:QUERY');
    }
    $check->store_result();
    $installed = ($check->num_rows > 0);
    $check->close();
    if (!$installed) {
        http_response_code(409);
        exit('AVATAR_SETTING_ERROR:ADDON_NOT_INSTALLED');
    }

    $stmt = $mysqli->prepare("UPDATE boom_addons SET custom1 = ? WHERE addons = 'chathype_avatar_chooser' LIMIT 1");
    if (!$stmt) {
        http_response_code(500);
        exit('AVATAR_SETTING_ERROR:UPDATE_PREPARE');
    }
    $stmt->bind_param('s', $enabled);
    $ok = $stmt->execute();
    $stmt->close();
    if (!$ok) {
        http_response_code(500);
        exit('AVATAR_SETTING_ERROR:UPDATE_FAILED');
    }
    if (function_exists('redisUpdateAddons')) {
        redisUpdateAddons('chathype_avatar_chooser');
    }
    exit('5');
}

/* Save the logged-in member's generated avatar. */
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

/*
 * Bewaar de oorspronkelijke CodyChat-avatar alleen bij de eerste keer.
 * Bij latere avatarwijzigingen blijft dit origineel behouden zodat uninstall
 * de avatar kan terugzetten die de gebruiker vóór deze addon had.
 */
$backupTable = 'chathype_avatar_chooser_backup';
$createBackup = $mysqli->query(
    "CREATE TABLE IF NOT EXISTS `chathype_avatar_chooser_backup` (
        `user_id` INT NOT NULL,
        `original_avatar` VARCHAR(255) NOT NULL DEFAULT '',
        `custom_avatar` VARCHAR(255) NOT NULL DEFAULT '',
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
);
if (!$createBackup) {
    http_response_code(500);
    exit('AVATAR_ERROR:BACKUP_TABLE_FAILED');
}
$originalStmt = $mysqli->prepare('SELECT user_tumb FROM boom_users WHERE user_id = ? LIMIT 1');
if (!$originalStmt) {
    http_response_code(500);
    exit('AVATAR_ERROR:ORIGINAL_AVATAR_QUERY_FAILED');
}
$originalStmt->bind_param('i', $uid);
if (!$originalStmt->execute()) {
    $originalStmt->close();
    http_response_code(500);
    exit('AVATAR_ERROR:ORIGINAL_AVATAR_QUERY_FAILED');
}
$originalStmt->bind_result($originalAvatar);
$hasUser = $originalStmt->fetch();
$originalStmt->close();
if (!$hasUser) {
    http_response_code(404);
    exit('AVATAR_ERROR:USER_NOT_FOUND');
}
$originalAvatar = is_string($originalAvatar) ? $originalAvatar : '';
$backupStmt = $mysqli->prepare(
    'INSERT IGNORE INTO `chathype_avatar_chooser_backup` (user_id, original_avatar, custom_avatar) VALUES (?, ?, ?)'
);
if (!$backupStmt) {
    http_response_code(500);
    exit('AVATAR_ERROR:BACKUP_SAVE_FAILED');
}
$emptyCustom = '';
$backupStmt->bind_param('iss', $uid, $originalAvatar, $emptyCustom);
$backupOk = $backupStmt->execute();
$backupStmt->close();
if (!$backupOk) {
    http_response_code(500);
    exit('AVATAR_ERROR:BACKUP_SAVE_FAILED');
}

$filename = 'chathype_custom_' . $uid . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(3)) . '.png';
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
    @unlink($target);
    http_response_code(500);
    exit('AVATAR_ERROR:USER_QUERY_FAILED');
}
$stmt->bind_param('si', $filename, $uid);
$ok = $stmt->execute();
$stmt->close();
if (!$ok) {
    @unlink($target);
    http_response_code(500);
    exit('AVATAR_ERROR:USER_AVATAR_UPDATE_FAILED');
}

/* Track the latest generated file, while preserving the first original avatar. */
$trackStmt = $mysqli->prepare('UPDATE `chathype_avatar_chooser_backup` SET custom_avatar = ? WHERE user_id = ? LIMIT 1');
if (!$trackStmt) {
    http_response_code(500);
    exit('AVATAR_ERROR:BACKUP_UPDATE_FAILED');
}
$trackStmt->bind_param('si', $filename, $uid);
$trackOk = $trackStmt->execute();
$trackStmt->close();
if (!$trackOk) {
    http_response_code(500);
    exit('AVATAR_ERROR:BACKUP_UPDATE_FAILED');
}
if (function_exists('redisUpdateUser')) {
    redisUpdateUser($uid);
}
exit('5');
