<?php
/*
 * Chathype Avatar Kiezer — CodyChat 11 uninstall.
 * Herstelt opgeslagen originele avatars en verwijdert alleen eigen avatarbestanden.
 */
if (!defined('BOOM')) {
    require_once __DIR__ . '/../../../system/config_addons.php';
}
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
    echo 'Chathype Avatar Kiezer: database niet beschikbaar; back-up is behouden.';
    return;
}
$table = 'chathype_avatar_chooser_backup';
$tableCheck = $mysqli->query("SHOW TABLES LIKE '" . $mysqli->real_escape_string($table) . "'");
if (!$tableCheck || $tableCheck->num_rows === 0) {
    echo 'Chathype Avatar Kiezer verwijderd. Geen avatar-back-up gevonden; er zijn geen bekende addon-avatars om te verwijderen.';
    return;
}
$tableCheck->free();
$rows = $mysqli->query("SELECT user_id, original_avatar, custom_avatar FROM `chathype_avatar_chooser_backup`");
if (!$rows) {
    echo 'Chathype Avatar Kiezer: back-up kon niet worden gelezen en is behouden.';
    return;
}
$avatarDir = realpath(__DIR__ . '/../../../avatar');
if (!$avatarDir || !is_dir($avatarDir)) $avatarDir = realpath(__DIR__ . '/../../../upload/avatar');
if (!$avatarDir || !is_dir($avatarDir)) $avatarDir = realpath(__DIR__ . '/../../../uploads/avatar');
$restore = $mysqli->prepare("UPDATE boom_users SET user_tumb = '' WHERE user_id = ? AND user_tumb = ? LIMIT 1");
if (!$restore) {
    $rows->free();
    echo 'Chathype Avatar Kiezer: herstelquery kon niet worden voorbereid; back-up is behouden.';
    return;
}
$restored = 0;
$filesRemoved = 0;
$failed = false;
while ($backup = $rows->fetch_assoc()) {
    $uid = isset($backup['user_id']) ? (int)$backup['user_id'] : 0;
    $original = isset($backup['original_avatar']) ? (string)$backup['original_avatar'] : '';
    $custom = isset($backup['custom_avatar']) ? (string)$backup['custom_avatar'] : '';
    // Alleen addon-bestandsnamen herstellen; overschrijf geen later gekozen gewone avatar.
    $isCustom = ($uid > 0 && $custom !== '' && basename($custom) === $custom &&
        strpos($custom, 'chathype_custom_' . $uid . '_') === 0);
    if ($isCustom) {
        $restore->bind_param('sis', $original, $uid, $custom);
        if (!$restore->execute()) {
            $failed = true;
            continue;
        }
        if ($restore->affected_rows > 0) {
            $restored++;
            if (function_exists('redisUpdateUser')) redisUpdateUser($uid);
        }
        if ($avatarDir) {
            $file = $avatarDir . DIRECTORY_SEPARATOR . $custom;
            if (is_file($file) && @unlink($file)) $filesRemoved++;
        }
    }
}
$restore->close();
$rows->free();
if ($failed) {
    echo 'Verwijderen gedeeltelijk uitgevoerd; er waren databasefouten. De back-up is behouden zodat je opnieuw kunt proberen.';
    return;
}
if ($mysqli->query("DROP TABLE IF EXISTS `chathype_avatar_chooser_backup`")) {
    if (function_exists('redisUpdateAddons')) redisUpdateAddons('chathype_avatar_chooser');
    echo 'Chathype Avatar Kiezer verwijderd. Leeg gemaakte ledenavatars: ' . (int)$restored .
        '. Verwijderde addon-bestanden: ' . (int)$filesRemoved . '.';
} else {
    echo 'Avatar-herstel uitgevoerd, maar de back-uptabel kon niet worden verwijderd; de back-up is behouden.';
}
