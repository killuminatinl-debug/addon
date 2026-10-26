<?php
/*
 * Chathype Avatar Kiezer — CodyChat 11 addon-instellingen.
 * Gebaseerd op het adminpaneel van Chathype Avatar Creator in dezelfde repository.
 */
$load_addons = 'chathype_avatar_chooser';
require(__DIR__ . '/../../../system/config_addons.php');

if (!function_exists('canManageAddons') || !canManageAddons()) {
    http_response_code(403);
    exit;
}

$avatarEnabled = !isset($addons['custom1']) || (string)$addons['custom1'] !== '0';
?>
<?php echo elementTitle('Chathype Avatar Kiezer', 'loadL(\'admin/setting_addons.php\');'); ?>
<div class="page_full">
  <div class="page_element">
    <div class="config_section">
      <div class="setting_element">
        <p class="label">Avatar-maker voor leden</p>
        <p class="sub_text">Beheer of leden hun eigen avatar mogen maken en als profielfoto kunnen opslaan.</p>
        <select id="chav_enabled">
          <option value="1" <?php echo $avatarEnabled ? 'selected' : ''; ?>>Ingeschakeld</option>
          <option value="0" <?php echo !$avatarEnabled ? 'selected' : ''; ?>>Uitgeschakeld</option>
        </select>
      </div>
      <button id="save_chav_setting" type="button" class="tmargin10 reg_button theme_btn">
        <i class="fa fa-floppy-o"></i> <?php echo isset($lang['save']) ? $lang['save'] : 'Opslaan'; ?>
      </button>
      <div id="chav_setting_status" class="sub_text" role="status" aria-live="polite"></div>
    </div>
  </div>
</div>
<script data-cfasync="false">
(function($){
  var saveButton = '#save_chav_setting';
  $(document).off('click.chavSave', saveButton).on('click.chavSave', saveButton, function(){
    var $button = $(this);
    var $status = $('#chav_setting_status');
    var enabled = $('#chav_enabled').val();
    $button.prop('disabled', true);
    $status.text('Instellingen opslaan…');
    $.ajax({
      url: 'addons/chathype_avatar_chooser/system/action.php',
      method: 'POST',
      data: { save_avatar_setting: 1, chathype_avatar_enabled: enabled },
      cache: false,
      dataType: 'text'
    }).done(function(response){
      if (String(response).trim() === '5') {
        $status.text('Instelling opgeslagen.');
        if (typeof callSuccess === 'function' && typeof system !== 'undefined') {
          callSuccess(system.saved || 'Opgeslagen');
        }
      } else {
        $status.text('Opslaan mislukt. Controleer of de addon geïnstalleerd is.');
        if (typeof callError === 'function' && typeof system !== 'undefined') callError(system.error || 'Fout');
      }
    }).fail(function(xhr){
      $status.text('Opslaan mislukt (HTTP ' + xhr.status + ').');
      if (typeof callError === 'function' && typeof system !== 'undefined') callError(system.error || 'Fout');
    }).always(function(){
      $button.prop('disabled', false);
    });
  });
})(jQuery);
</script>
