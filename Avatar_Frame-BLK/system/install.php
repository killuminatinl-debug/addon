<?php
if (!defined('BOOM')) {
    require_once __DIR__ . '/../../../system/config_addons.php';
}
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
    http_response_code(500);
    exit('Databaseverbinding ontbreekt.');
}
$ok = $mysqli->query("CREATE TABLE IF NOT EXISTS `avatar_frame` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` int(11) NOT NULL DEFAULT 0,
  `rank` int(11) NOT NULL DEFAULT 1,
  `method` int(11) NOT NULL DEFAULT 1,
  `tumb` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_tumb` (`tumb`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
if (!$ok) { http_response_code(500); exit('Tabel avatar_frame kon niet worden aangemaakt.'); }
$col = $mysqli->query("SHOW COLUMNS FROM `boom_users` LIKE 'avatar_frame'");
if ($col && $col->num_rows === 0) {
    if (!$mysqli->query("ALTER TABLE `boom_users` ADD `avatar_frame` int(11) NOT NULL DEFAULT 0")) {
        http_response_code(500); exit('Kolom boom_users.avatar_frame kon niet worden aangemaakt.');
    }
}
if ($col) $col->free();
echo 'Avatar Frame BLK: installatie gecontroleerd.';
