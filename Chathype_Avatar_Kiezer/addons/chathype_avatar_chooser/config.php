<?php
/* CodyChat 11 addon admin configuration page. Loaded through the built-in Addons manager. */
if (!defined('BOOM')) {
    require_once __DIR__ . '/../../../system/config_addons.php';
}
require_once __DIR__ . '/system/settings.php';
if (!isset($mysqli) || !($mysqli instanceof mysqli)) { echo '<div class="page_full"><div class="page_element">Databaseverbinding ontbreekt.</div></div>'; return; }
$message = '';
if (isset($_POST['chathype_avatar_save']) && isset($_POST['chathype_avatar_enabled'])) {
    $enabled = ((string)$_POST['chathype_avatar_enabled'] === '1') ? 1 : 0;
    if (chathypeAvatarTableReady()) {
        $stmt = $mysqli->prepare('UPDATE boom_chathype_avatar_settings SET enabled = ? WHERE setting_id = 1 LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('i', $enabled);
            $ok = $stmt->execute();
            $stmt->close();
            $message = $ok ? 'Instellingen opgeslagen.' : 'Opslaan is mislukt. Controleer de databasefout.';
        } else { $message = 'De databasequery kon niet worden voorbereid.'; }
    } else { $message = 'Instellingentabel kon niet worden aangemaakt. Controleer de database-rechten.'; }
}
$enabled = chathypeAvatarEnabled();
?>
<div class="page_full"><div class="page_element"><div class="tpad15">
<h3>Chathype Avatar Kiezer</h3>
<p>Schakel de avatar-kiezer voor leden in of uit. Als deze uitstaat, verdwijnt de knop uit het eigen profielmenu en worden avatarwijzigingen geweigerd.</p>
<?php if ($message !== '') { ?><div class="setting_element"><div class="sub_text"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div></div><?php } ?>
<?php if (!chathypeAvatarTableReady()) { ?><div class="setting_element"><div class="sub_text">De instellingentabel is nog niet beschikbaar. Controleer de databaseverbinding en rechten.</div></div><?php } ?>
<form method="post" action="">
<div class="setting_element"><div class="setting_title">Avatar-kiezer</div><div class="setting_text">Bepaal of spelers avatars mogen kiezen.</div>
<label style="display:block;padding:8px 0"><input type="radio" name="chathype_avatar_enabled" value="1" <?php echo $enabled ? 'checked' : ''; ?>> Ingeschakeld</label>
<label style="display:block;padding:8px 0"><input type="radio" name="chathype_avatar_enabled" value="0" <?php echo !$enabled ? 'checked' : ''; ?>> Uitgeschakeld</label>
<input type="hidden" name="chathype_avatar_save" value="1">
<button type="submit" class="reg_button theme_btn">Instellingen opslaan</button></div>
</form>
<p class="sub_text">Dit scherm hoort bij de ingebouwde CodyChat Addons-beheerder. Open de configuratie van <b>Chathype Avatar Kiezer</b> via Admin → Addons.</p>
</div></div></div>
