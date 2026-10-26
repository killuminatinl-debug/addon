<?php
/* Reads the enabled flag from CodyChat's native addon registry. */
if (!function_exists('chathypeCreatorEnabled')) {
    function chathypeCreatorEnabled() {
        global $mysqli;
        if (!isset($mysqli) || !($mysqli instanceof mysqli)) return false;
        $stmt = $mysqli->prepare("SELECT custom1 FROM boom_addons WHERE addons = 'chathype_avatar_creator' LIMIT 1");
        if (!$stmt) return false;
        if (!$stmt->execute()) { $stmt->close(); return false; }
        $stmt->bind_result($value);
        $found = $stmt->fetch();
        $stmt->close();
        return $found && (string)$value !== '0';
    }
}
