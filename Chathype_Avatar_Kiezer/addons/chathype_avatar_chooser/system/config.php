<?php
$load_addons = 'chathype_avatar_chooser';
require_once __DIR__ . '/../../../system/config_addons.php';
$chacAdminAllowed = false;
if (function_exists('canManageAddons')) {
    $chacAdminAllowed = (bool)canManageAddons();
}
if (!$chacAdminAllowed && function_exists('isAdmin')) {
    $chacAdminAllowed = (bool)isAdmin(isset($data) && is_array($data) ? $data : array());
}
if (!$chacAdminAllowed && isset($data['user_rank']) && is_numeric($data['user_rank'])) {
    $chacAdminAllowed = ((int)$data['user_rank'] >= 8);
}
if (!$chacAdminAllowed) {
    http_response_code(403);
    exit('Geen toegang tot de avatar-instellingen.');
}
$chacScript = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\\\', '/', $_SERVER['SCRIPT_NAME']) : '/index.php';
$chacControlPos = strpos($chacScript, '/control/');
$chacAddonPos = strpos($chacScript, '/addons/');
if ($chacControlPos !== false) {
    $chacBase = substr($chacScript, 0, $chacControlPos + 1);
} elseif ($chacAddonPos !== false) {
    $chacBase = substr($chacScript, 0, $chacAddonPos + 1);
} else {
    $chacBase = rtrim(str_replace('\\\\', '/', dirname($chacScript)), '/') . '/';
    if ($chacBase === '//') $chacBase = '/';
}
$chacActionUrl = $chacBase . 'addons/chathype_avatar_chooser/system/action.php';
$avatarEnabled = true;
$avatarSettingQuery = $mysqli->query("SELECT custom1 FROM boom_addons WHERE addons = 'chathype_avatar_chooser' LIMIT 1");
if ($avatarSettingQuery && ($avatarSettingRow = $avatarSettingQuery->fetch_assoc())) {
    $avatarEnabled = !isset($avatarSettingRow['custom1']) || (string)$avatarSettingRow['custom1'] !== '0';
}
?>
<?php echo elementTitle('Chathype Avatar Studio', 'loadL(\'admin/setting_addons.php\');'); ?>
<div class="page_full"><div class="page_element"><div class="config_section chac_admin_card">
 <div class="chac_admin_head"><div class="chac_admin_icon"><i class="fa fa-magic"></i></div><div><p class="label">Chathype Avatar Studio</p><p class="sub_text">Beheer de avatar-maker voor leden.</p></div></div>
 <div class="setting_element"><p class="label">Avatar-maker voor leden</p><p class="sub_text">Leden kunnen hun eigen gezicht, kapsel, kleuren en accessoires samenstellen.</p>
 <div class="chac_admin_options"><label><input type="radio" name="chathype_avatar_enabled" value="1" <?php echo $avatarEnabled ? 'checked' : ''; ?>> Ingeschakeld</label><label><input type="radio" name="chathype_avatar_enabled" value="0" <?php echo !$avatarEnabled ? 'checked' : ''; ?>> Uitgeschakeld</label></div></div>
 <button id="save_chathype_avatar" type="button" class="tmargin10 reg_button theme_btn" onclick="(function(b){var s=document.getElementById('chacAdminStatus'),v=document.querySelector('input[name="chathype_avatar_enabled"]:checked');if(!v||!window.jQuery){if(s)s.textContent='Instellingen konden niet worden geladen.';return;}b.disabled=true;if(s)s.textContent='Instellingen opslaan…';jQuery.ajax({url:<?php echo json_encode($chacActionUrl); ?>,type:'POST',data:{save_avatar_setting:1,chathype_avatar_enabled:v.value},cache:false,dataType:'text'}).done(function(r){if(String(r).trim()==='5'){if(s)s.textContent='Instellingen opgeslagen.';if(typeof callSuccess==='function'&&window.system)callSuccess(system.saved||'Opgeslagen');}else{if(s)s.textContent='Opslaan mislukt. Controleer de database en addonrechten.';if(typeof callError==='function'&&window.system)callError(system.error||'Fout');}}).fail(function(x){if(s)s.textContent='Opslaan mislukt (HTTP '+x.status+').';if(typeof callError==='function'&&window.system)callError(system.error||'Fout');}).always(function(){b.disabled=false;});})(this);"><i class="fa fa-floppy-o"></i> <?php echo $lang['save']; ?></button>
 <p id="chacAdminStatus" class="sub_text" role="status" aria-live="polite"></p>
</div></div></div>
<style>.chac_admin_card{max-width:760px;padding:22px!important;border-radius:16px}.chac_admin_head{display:flex;align-items:center;gap:14px;margin-bottom:22px}.chac_admin_icon{width:52px;height:52px;display:flex;align-items:center;justify-content:center;border-radius:15px;color:#fff;font-size:23px;background:linear-gradient(135deg,#6366f1,#ec4899,#06b6d4);box-shadow:0 8px 24px #6366f133}.chac_admin_head .label{font-size:18px;font-weight:800;margin:0 0 5px}.chac_admin_head .sub_text{margin:0}.chac_admin_options{display:flex;gap:18px;flex-wrap:wrap;margin-top:10px}.chac_admin_options label{display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid rgba(127,127,127,.25);border-radius:10px;cursor:pointer}#chacAdminStatus{min-height:18px;margin-top:12px}</style>
