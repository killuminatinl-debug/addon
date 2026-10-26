<?php
/* CodyChat-native toggle: boom_addons.custom1 (1=enabled, 0=disabled). */
if (!function_exists('chathypeAvatarEnabled')) {
    function chathypeAvatarEnabled() {
        global $mysqli;
        if (!isset($mysqli) || !($mysqli instanceof mysqli)) return false;
        $stmt = $mysqli->prepare("SELECT custom1 FROM boom_addons WHERE addons = 'chathype_avatar_chooser' LIMIT 1");
        if (!$stmt) return false;
        if (!$stmt->execute()) { $stmt->close(); return false; }
        $stmt->bind_result($setting);
        $found = $stmt->fetch();
        $stmt->close();
        return $found && (string)$setting !== '0';
    }
}
