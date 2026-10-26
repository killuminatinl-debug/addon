<?php
require_once __DIR__ . '/../../system/config_session.php';
require_once __DIR__ . '/system/settings.php';

$isAdmin = false;
if (function_exists('canManageAddons')) {
    $isAdmin = (bool)canManageAddons();
}
if (!$isAdmin && function_exists('isAdmin')) {
    $isAdmin = (bool)isAdmin(isset($data) && is_array($data) ? $data : array());
}
if (!$isAdmin && isset($data['user_rank']) && is_numeric($data['user_rank'])) {
    $isAdmin = ((int)$data['user_rank'] >= 8);
}
if (!$isAdmin) {
    http_response_code(403);
    exit('Geen toegang.');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chathype_avatar_enabled'])) {
    $enabled = ((string)$_POST['chathype_avatar_enabled'] === '1') ? '1' : '0';
    $stmt = $mysqli->prepare("UPDATE boom_addons SET custom1 = ? WHERE addons = 'chathype_avatar_chooser' LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('s', $enabled);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok && function_exists('redisUpdateAddons')) {
            redisUpdateAddons('chathype_avatar_chooser');
        }
        $message = $ok ? 'Instelling opgeslagen.' : 'Opslaan mislukt.';
    } else {
        $message = 'Databasefout bij opslaan.';
    }
}
$current = chathypeAvatarEnabled();

/* Build an URL for CodyChat root installs and subfolder installs. */
$chacScript = isset($_SERVER["SCRIPT_NAME"]) ? str_replace("\\\\", "/", $_SERVER["SCRIPT_NAME"]) : "/index.php";
$chacControlPos = strpos($chacScript, "/control/");
$chacAddonPos = strpos($chacScript, "/addons/");
if ($chacControlPos !== false) {
    $chacBase = substr($chacScript, 0, $chacControlPos + 1);
} elseif ($chacAddonPos !== false) {
    $chacBase = substr($chacScript, 0, $chacAddonPos + 1);
} else {
    $chacBase = rtrim(str_replace("\\\\", "/", dirname($chacScript)), "/") . "/";
    if ($chacBase === "//") $chacBase = "/";
}
$chacAdminUrl = $chacBase . "addons/chathype_avatar_chooser/admin.php";

?>
<div class="modal_content">
    <div class="setting_element">
        <div class="setting_title">Chathype Avatar Kiezer</div>
        <div class="setting_text">Schakel de avatar-editor in of uit voor leden.</div>
        <?php if ($message !== '') { ?>
            <div class="sub_text" style="padding:10px 0"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($chacAdminUrl, ENT_QUOTES, 'UTF-8'); ?>">
            <label style="display:block;margin:12px 0">
                <input type="radio" name="chathype_avatar_enabled" value="1" <?php echo $current ? 'checked' : ''; ?>>
                Ingeschakeld
            </label>
            <label style="display:block;margin:12px 0">
                <input type="radio" name="chathype_avatar_enabled" value="0" <?php echo !$current ? 'checked' : ''; ?>>
                Uitgeschakeld
            </label>
            <button type="submit" class="reg_button">Instellingen opslaan</button>
        </form>
    </div>
</div>
