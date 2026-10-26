<?php
require(__DIR__ . '/../../system/config_session.php');
require(__DIR__ . '/system/settings.php');
if (!function_exists('canManageAddons') || !canManageAddons()) { http_response_code(403); exit('Geen toegang. Alleen teamleden met addonbeheerrechten kunnen deze instelling wijzigen.'); }
if (!chathypeAvatarTableReady()) { exit('Instellingen konden niet worden opgeslagen in de database. Controleer database-rechten.'); }
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chathype_avatar_enabled'])) {
    $enabled = ((string)$_POST['chathype_avatar_enabled'] === '1') ? 1 : 0;
    $stmt = $mysqli->prepare('UPDATE boom_chathype_avatar_settings SET enabled = ? WHERE setting_id = 1 LIMIT 1');
    if ($stmt) { $stmt->bind_param('i', $enabled); $ok = $stmt->execute(); $stmt->close(); $message = $ok ? 'Instelling opgeslagen.' : 'Opslaan mislukt.'; }
    else { $message = 'Databasefout bij opslaan.'; }
}
$current = chathypeAvatarEnabled();
?>
<div class="modal_content"><div class="setting_element"><div class="setting_title">Chathype Avatar Kiezer</div><div class="setting_text">Schakel de avatar-kiezer in of uit voor leden in hun eigen profielmenu.</div>
<?php if ($message !== '') { ?><div class="sub_text" style="padding:10px 0"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
<form method="post" action="addons/chathype_avatar_chooser/admin.php"><label style="display:block;margin:12px 0"><input type="radio" name="chathype_avatar_enabled" value="1" <?php echo $current ? 'checked' : ''; ?>> Ingeschakeld</label><label style="display:block;margin:12px 0"><input type="radio" name="chathype_avatar_enabled" value="0" <?php echo !$current ? 'checked' : ''; ?>> Uitgeschakeld</label><button type="submit" class="reg_button">Instellingen opslaan</button></form></div></div>
