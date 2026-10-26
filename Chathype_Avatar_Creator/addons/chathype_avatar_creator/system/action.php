<?php
$load_addons = 'chathype_avatar_creator';
require(__DIR__ . '/../../../system/config_addons.php');
if (isset($_POST['save_creator_setting'])) {
    if (!canManageAddons() || !isset($_POST['enabled'])) { http_response_code(403); die('0'); }
    $enabled = ((string)$_POST['enabled'] === '1') ? '1' : '0';
    $stmt = $mysqli->prepare("UPDATE boom_addons SET custom1 = ? WHERE addons = 'chathype_avatar_creator' LIMIT 1");
    if (!$stmt) { die('0'); }
    $stmt->bind_param('s', $enabled);
    $ok = $stmt->execute();
    $stmt->close();
    if ($ok && function_exists('redisUpdateAddons')) redisUpdateAddons('chathype_avatar_creator');
    echo $ok ? '5' : '0';
    die();
}
http_response_code(400);
echo '0';
