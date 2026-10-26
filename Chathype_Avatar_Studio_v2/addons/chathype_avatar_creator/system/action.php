<?php
$load_addons = 'chathype_avatar_creator';
require(__DIR__ . '/../../../system/config_addons.php');
if (!canManageAddons() || !isset($_POST['save_setting'])) { http_response_code(403); exit('0'); }
$enabled = isset($_POST['enabled']) && (string)$_POST['enabled'] === '0' ? '0' : '1';
$stmt = $mysqli->prepare("UPDATE boom_addons SET custom1 = ? WHERE addons = 'chathype_avatar_creator' LIMIT 1");
if (!$stmt) { http_response_code(500); exit('0'); }
$stmt->bind_param('s', $enabled); $ok = $stmt->execute();
if ($ok && $stmt->affected_rows === 0) { $q = $mysqli->query("SELECT addons FROM boom_addons WHERE addons = 'chathype_avatar_creator' LIMIT 1"); $ok = $q && $q->num_rows > 0; if ($q) $q->free(); }
$stmt->close(); echo $ok ? '5' : '0';
