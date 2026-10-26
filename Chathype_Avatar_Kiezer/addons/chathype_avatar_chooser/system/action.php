<?php
require(__DIR__ . '/../../../system/config_session.php');
require(__DIR__ . '/settings.php');
if (!chathypeAvatarEnabled() || empty($data['user_id'])) { die('0'); }
if (!isset($_POST['set_chathype_avatar']) || (int)$_POST['set_chathype_avatar'] !== 1) { die('0'); }
$key = isset($_POST['avatar']) ? (string)$_POST['avatar'] : '';
if (!preg_match('/^(0[1-9]|[1-3][0-9]|40)$/', $key)) { die('0'); }
$source = realpath(__DIR__ . '/../avatars/avatar_' . $key . '.png');
$avatarDir = realpath(__DIR__ . '/../../../avatar');
if (!$source || !$avatarDir || !is_file($source) || !is_writable($avatarDir)) { die('0'); }
$filename = 'chathype_' . (int)$data['user_id'] . '_' . $key . '.png';
$target = $avatarDir . DIRECTORY_SEPARATOR . $filename;
if (!@copy($source, $target)) { die('0'); }
$stmt = $mysqli->prepare('UPDATE boom_users SET user_tumb = ? WHERE user_id = ? LIMIT 1');
if (!$stmt) { @unlink($target); die('0'); }
$uid = (int)$data['user_id']; $stmt->bind_param('si', $filename, $uid); $ok = $stmt->execute(); $stmt->close();
if (!$ok) { @unlink($target); die('0'); }
if (function_exists('redisUpdateUser')) { redisUpdateUser($uid); }
echo '5';
?>
