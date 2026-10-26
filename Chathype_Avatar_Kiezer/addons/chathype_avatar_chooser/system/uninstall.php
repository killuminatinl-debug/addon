<?php
/*
 * Chathype Avatar Kiezer — veilige CodyChat 11 uninstall.
 * Zet de oorspronkelijke profielfoto terug voor gebruikers waarvan de avatar
 * door deze addon is vervangen. Verwijdert alleen bestanden van deze addon.
 */
if (!defined('BOOM')) {
    require_once __DIR__ . '/../../../system/config_addons.php';
}

if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
    echo 'Chathype Avatar Kiezer: database niet beschikbaar; er zijn geen gegevens verwijderd.';
    return;
}

$table = 'chathype_avatar_chooser_backup';
$tableCheck = $mysqli->query("SHOW TABLES LIKE '" . $mysqli->real_escape_string($table) . "'");
if ($tableCheck && $tableCheck->num_rows > 0) {
    $rows = $mysqli->query("SELECT user_id, original_avatar, custom_avatar FROM `chathype_avatar_chooser_backup`");
    if ($rows) {
        $avatarDir = realpath(__DIR__ . '/../../../avatar');
        if (!$avatarDir || !is_dir($avatarDir)) {
            $avatarDir = realpath(__DIR__ . '/../../../upload/avatar');
        }
        if (!$avatarDir || !is_dir($avatarDir)) {
            $avatarDir = realpath(__DIR__ . '/../../../uploads/avatar');
        }

        $restore = $mysqli->prepare(
            'UPDATE boom_users SET user_tumb = ? WHERE user_id = ? AND user_tumb = ? LIMIT 1'
        );
        while ($backup = $rows->fetch_assoc()) {
            $uid = isset($backup['user_id']) ? (int)$backup['user_id'] : 0;
            $original = isset($backup['original_avatar']) ? (string)$backup['original_avatar'] : '';
            $custom = isset($backup['custom_avatar']) ? (string)$backup['custom_avatar'] : '';
            if ($uid > 0 && $custom !== '' && $restore) {
                // Alleen terugzetten als de gebruiker nog steeds de addon-avatar gebruikt.
                $restore->bind_param('sis', $original, $uid, $custom);
                $restore->execute();
                if (function_exists('redisUpdateUser')) {
                    redisUpdateUser($uid);
                }
            }

            // Verwijder uitsluitend bestanden die aantoonbaar door deze addon zijn aangemaakt.
            if ($avatarDir && $custom !== '' &&
                basename($custom) === $custom &&
                strpos($custom, 'chathype_custom_' . $uid . '_') === 0) {
                $file = $avatarDir . DIRECTORY_SEPARATOR . $custom;
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
        if ($restore) {
            $restore->close();
        }
        $rows->free();
    }
    $mysqli->query("DROP TABLE IF EXISTS `chathype_avatar_chooser_backup`");
    if (function_exists('redisUpdateAddons')) {
        redisUpdateAddons('chathype_avatar_chooser');
    }
}

echo 'Chathype Avatar Kiezer verwijderd. Opgeslagen oorspronkelijke avatars zijn teruggezet.';
