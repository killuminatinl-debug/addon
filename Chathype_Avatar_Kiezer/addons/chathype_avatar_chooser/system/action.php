<?php
$load_addons = 'chathype_avatar_chooser';
require(__DIR__ . '/../../../system/config_addons.php');
require_once __DIR__ . '/settings.php';

if (isset($_POST['save_avatar_setting'])) {
    if (!canManageAddons() || !isset($_POST['chathype_avatar_enabled'])) { die(); }
    $enabled = ((string)$_POST['chathype_avatar_enabled'] === '1') ? '1' : '0';
    $stmt = $mysqli->prepare("UPDATE boom_addons SET custom1 = ? WHERE addons = 'chathype_avatar_chooser' LIMIT 1");
    if (!$stmt) { die('0'); }
    $stmt->bind_param('s', $enabled);
    $ok = $stmt->execute();
    $stmt->close();
    if ($ok && function_exists('redisUpdateAddons')) { redisUpdateAddons('chathype_avatar_chooser'); }
    echo $ok ? '5' : '0';
    die();
}

if (!chathypeAvatarEnabled() || empty($data['user_id']) || !isset($_POST['set_chathype_avatar']) || (int)$_POST['set_chathype_avatar'] !== 1) { die('0'); }
$key = isset($_POST['avatar']) ? (string)$_POST['avatar'] : '';
if (!preg_match('/^(0[1-9]|[1-3][0-9]|40)$/', $key)) { die('0'); }
$source = realpath(__DIR__ . '/../avatars/avatar_' . $key . '.png');
$avatarDir = realpath(__DIR__ . '/../../../avatar');
if (!$source || !$avatarDir || !is_file($source) || !is_writable($avatarDir)) { die('0'); }
$uid = (int)$data['user_id'];
$filename = 'chathype_' . $uid . '_' . $key . '.png';
$target = $avatarDir . DIRECTORY_SEPARATOR . $filename;
if (!@copy($source, $target)) { die('0'); }
$stmt = $mysqli->prepare('UPDATE boom_users SET user_tumb = ? WHERE user_id = ? LIMIT 1');
if (!$stmt) { @unlink($target); die('0'); }
$stmt->bind_param('si', $filename, $uid);
$ok = $stmt->execute();
$stmt->close();
if (!$ok) { @unlink($target); die('0'); }
if (function_exists('redisUpdateUser')) { redisUpdateUser($uid); }
echo '5';
