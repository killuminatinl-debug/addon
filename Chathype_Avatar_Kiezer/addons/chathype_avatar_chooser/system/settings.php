<?php
/* Chathype Avatar Kiezer settings helper - CodyChat 11 / PHP 7.4+ */
if (!function_exists('chathypeAvatarTableReady')) {
    function chathypeAvatarTableReady() {
        global $mysqli;
        static $ready = null;
        if ($ready !== null) return $ready;
        if (!isset($mysqli) || !($mysqli instanceof mysqli)) return $ready = false;
        $sql = "CREATE TABLE IF NOT EXISTS `boom_chathype_avatar_settings` (`setting_id` TINYINT UNSIGNED NOT NULL PRIMARY KEY, `enabled` TINYINT(1) NOT NULL DEFAULT 1, `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        if (!$mysqli->query($sql)) return $ready = false;
        if (!$mysqli->query("INSERT IGNORE INTO `boom_chathype_avatar_settings` (`setting_id`,`enabled`) VALUES (1,1)")) return $ready = false;
        return $ready = true;
    }
}
if (!function_exists('chathypeAvatarEnabled')) {
    function chathypeAvatarEnabled() {
        global $mysqli;
        if (!chathypeAvatarTableReady()) return false;
        $result = $mysqli->query("SELECT `enabled` FROM `boom_chathype_avatar_settings` WHERE `setting_id`=1 LIMIT 1");
        if (!$result) return false;
        $row = $result->fetch_assoc(); $result->free();
        return !empty($row) && (int)$row['enabled'] === 1;
    }
}
