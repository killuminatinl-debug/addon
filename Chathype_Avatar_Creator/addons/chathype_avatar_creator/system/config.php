<?php
/*
 * Chathype Avatar Creator — CodyChat 11
 * Admin page intentionally follows the working Avatar Kiezer config pattern.
 */
$load_addons = 'chathype_avatar_creator';
require(__DIR__ . '/../../../system/config_addons.php');
if (!canManageAddons()) { die(); }

$creatorEnabled = !isset($addons['custom1']) || (string)$addons['custom1'] !== '0';
?>
<?php echo elementTitle('Chathype Avatar Creator', 'loadL(\'admin/setting_addons.php\');'); ?>
<div class="page_full">
  <div class="page_element">
    <div class="config_section">
      <div class="setting_element">
        <p class="label">Zelf avatars maken</p>
        <p class="sub_text">Leden kunnen hun eigen cartoonavatar samenstellen en opslaan als profielfoto.</p>
        <select id="chac_enabled">
          <option value="1" <?php echo $creatorEnabled ? 'selected' : ''; ?>>Ingeschakeld</option>
          <option value="0" <?php echo !$creatorEnabled ? 'selected' : ''; ?>>Uitgeschakeld</option>
        </select>
      </div>
      <button id="save_chac_setting" type="button" class="tmargin10 reg_button theme_btn" onclick="saveChacSetting();">
        <i class="fa fa-floppy-o"></i> <?php echo $lang['save']; ?>
      </button>
      <div id="chac_setting_status" class="sub_text" role="status" aria-live="polite"></div>
    </div>
  </div>
</div>
<script data-cfasync="false">
(function($){
  // Namespace the function to avoid collisions with other addons/admin pages.
  window.saveChacSetting = function(){
    var $button = $('#save_chac_setting');
    var $status = $('#chac_setting_status');
    var enabled = $('#chac_enabled').val();
    $button.prop('disabled', true);
    $status.text('');
    $.ajax({
      url: 'addons/chathype_avatar_creator/system/action.php',
      method: 'POST',
      data: { save_creator_setting: 1, enabled: enabled },
      cache: false,
      dataType: 'text'
    }).done(function(response){
      if (String(response).trim() === '5') {
        $status.text('Instelling opgeslagen.');
        if (typeof callSuccess === 'function' && typeof system !== 'undefined') {
          callSuccess(system.saved || 'Opgeslagen');
        }
        if (typeof configAddons === 'function') {
          configAddons('chathype_avatar_creator');
        }
      } else {
        $status.text('Opslaan mislukt. Controleer of de addon is geïnstalleerd.');
        if (typeof callError === 'function' && typeof system !== 'undefined') callError(system.error);
      }
    }).fail(function(xhr){
      $status.text('Opslaan mislukt (HTTP ' + xhr.status + '). Controleer de serverlog en addonregistratie.');
      if (typeof callError === 'function' && typeof system !== 'undefined') callError(system.error);
    }).always(function(){
      $button.prop('disabled', false);
    });
  };
})(jQuery);
</script>
