<?php
/*
 * Chathype Avatar Creator — native CodyChat 11 admin action.
 * Uses the same boom_addons.custom1 switch as Chathype Avatar Kiezer.
 */
$load_addons = 'chathype_avatar_creator';
require(__DIR__ . '/../../../system/config_addons.php');

if (!isset($_POST['save_creator_setting'])) {
    http_response_code(400);
    exit('0');
}
if (!canManageAddons() || !isset($_POST['enabled'])) {
    http_response_code(403);
    exit('0');
}
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
    http_response_code(500);
    exit('0');
}

$enabled = ((string)$_POST['enabled'] === '1') ? '1' : '0';

/* Verify that the addon has been installed before reporting a successful save. */
$check = $mysqli->prepare("SELECT addons FROM boom_addons WHERE addons = 'chathype_avatar_creator' LIMIT 1");
if (!$check || !$check->execute()) {
    if ($check) $check->close();
    http_response_code(500);
    exit('0');
}
$check->store_result();
$installed = ($check->num_rows > 0);
$check->close();
if (!$installed) {
    http_response_code(409);
    exit('0');
}

$stmt = $mysqli->prepare("UPDATE boom_addons SET custom1 = ? WHERE addons = 'chathype_avatar_creator' LIMIT 1");
if (!$stmt) {
    http_response_code(500);
    exit('0');
}
$stmt->bind_param('s', $enabled);
$ok = $stmt->execute();
$stmt->close();

if (!$ok) {
    http_response_code(500);
    exit('0');
}
if (function_exists('redisUpdateAddons')) {
    redisUpdateAddons('chathype_avatar_creator');
}
echo '5';
