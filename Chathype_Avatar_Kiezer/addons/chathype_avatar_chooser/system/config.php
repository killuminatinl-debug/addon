<?php
$load_addons = 'chathype_avatar_chooser';
require(__DIR__ . '/../../../system/config_addons.php');
if (!canManageAddons()) { die(); }
$avatarEnabled = !isset($addons['custom1']) || (string)$addons['custom1'] !== '0';
?>
<?php echo elementTitle('Chathype Avatar Kiezer', 'loadL(\'admin/setting_addons.php\');'); ?>
<div class="page_full"><div class="page_element"><div class="config_section">
  <div class="setting_element"><p class="label">Avatar-kiezer voor leden</p><p class="sub_text">Wanneer ingeschakeld, zien leden op hun eigen profiel een knop waarmee ze één van de 40 avatars kunnen kiezen.</p>
    <select id="chathype_avatar_enabled"><option value="1" <?php echo $avatarEnabled ? 'selected' : ''; ?>>Ingeschakeld</option><option value="0" <?php echo !$avatarEnabled ? 'selected' : ''; ?>>Uitgeschakeld</option></select>
  </div>
  <button id="save_chathype_avatar" onclick="saveChathypeAvatar();" type="button" class="tmargin10 reg_button theme_btn"><i class="fa fa-floppy-o"></i> <?php echo $lang['save']; ?></button>
</div></div></div>
<script data-cfasync="false">
function saveChathypeAvatar(){
  $.post('addons/chathype_avatar_chooser/system/action.php',{save_avatar_setting:1,chathype_avatar_enabled:$('#chathype_avatar_enabled').val()},function(response){
    if(String(response).trim()==='5'){ if(typeof callSuccess==='function') callSuccess(system.saved); if(typeof configAddons==='function') configAddons('chathype_avatar_chooser'); }
    else { if(typeof callError==='function') callError(system.error); }
  }).fail(function(){ if(typeof callError==='function') callError(system.error); });
}
</script>
