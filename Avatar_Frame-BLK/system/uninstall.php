<?php
if (!defined('BOOM')) {
    require_once __DIR__ . '/../../../system/config_addons.php';
}
if (isset($mysqli) && $mysqli instanceof mysqli) {
    // Keep the user's selected-frame column/data by default; only remove the addon catalog.
    $mysqli->query("DROP TABLE IF EXISTS `avatar_frame`");
}
echo 'Avatar Frame BLK-catalogus verwijderd. De gebruikerskolom is behouden.';
