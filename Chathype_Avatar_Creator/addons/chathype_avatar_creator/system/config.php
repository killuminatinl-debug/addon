<?php
$load_addons = 'chathype_avatar_creator';
require(__DIR__ . '/../../../system/config_addons.php');
if (!canManageAddons()) { die(); }
/* Match the working Chathype Avatar Kiezer admin pattern: use CodyChat's loaded addon row. */
$creatorEnabled = !isset($addons['custom1']) || (string)$addons['custom1'] !== '0';
?>
<?php echo elementTitle('Chathype Avatar Creator', 'loadL(\'admin/setting_addons.php\');'); ?>
<div class="page_full"><div class="page_element"><div class="config_section">
  <div class="setting_element">
    <p class="label">Zelf avatars maken</p>
    <p class="sub_text">Leden kunnen een cartoonavatar samenstellen en als hun CodyChat-profielfoto opslaan.</p>
    <select id="chac_enabled">
      <option value="1" <?php echo $creatorEnabled ? 'selected' : ''; ?>>Ingeschakeld</option>
      <option value="0" <?php echo !$creatorEnabled ? 'selected' : ''; ?>>Uitgeschakeld</option>
    </select>
  </div>
  <button id="save_chac_setting" type="button" class="tmargin10 reg_button theme_btn" onclick="saveChacSetting();"><i class="fa fa-floppy-o"></i> <?php echo $lang['save']; ?></button>
</div></div></div>
<script data-cfasync="false">
function saveChacSetting(){
  var button = $('#save_chac_setting');
  button.prop('disabled', true);
  $.post('addons/chathype_avatar_creator/system/action.php', {
    save_creator_setting: 1,
    enabled: $('#chac_enabled').val()
  }, function(response){
    if(String(response).trim() === '5'){
      if(typeof callSuccess === 'function' && typeof system !== 'undefined') callSuccess(system.saved);
      if(typeof configAddons === 'function') configAddons('chathype_avatar_creator');
    }
    else if(typeof callError === 'function' && typeof system !== 'undefined') callError(system.error);
  }).fail(function(){
    if(typeof callError === 'function' && typeof system !== 'undefined') callError(system.error);
  }).always(function(){ button.prop('disabled', false); });
}
</script>
