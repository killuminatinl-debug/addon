<?php
$load_addons = 'chathype_avatar_creator';
require('../../../system/config_addons.php');
if (!canManageAddons()) { die(); }
$chacEnabled = !isset($addons['custom1']) || (string)$addons['custom1'] !== '0';
?>
<?php echo elementTitle('Chathype Avatar Studio', "loadL('admin/setting_addons.php');"); ?>
<div class="page_full"><div class="page_element"><div class="config_section">
<div class="setting_element"><p class="label">Avatar Studio</p><p class="sub_text">Leden kunnen een cartoonavatar ontwerpen en opslaan als profielfoto.</p>
<select id="chac_admin_enabled"><option value="1" <?php echo $chacEnabled ? 'selected' : ''; ?>>Ingeschakeld</option><option value="0" <?php echo !$chacEnabled ? 'selected' : ''; ?>>Uitgeschakeld</option></select></div>
<button type="button" class="reg_button theme_btn tmargin10" id="chac_admin_save">Opslaan</button><div id="chac_admin_status" class="sub_text" role="status" aria-live="polite"></div>
</div></div></div>
<script>(function($){$('#chac_admin_save').off('click.chac').on('click.chac',function(){var b=$(this),s=$('#chac_admin_status');b.prop('disabled',true);s.text('Opslaan…');$.ajax({url:'addons/chathype_avatar_creator/system/action.php',type:'POST',data:{save_setting:1,enabled:$('#chac_admin_enabled').val()},dataType:'text',cache:false}).done(function(r){s.text(String(r).trim()==='5'?'Instellingen opgeslagen.':'Opslaan mislukt. Controleer addonregistratie.');}).fail(function(x){s.text('Opslaan mislukt (HTTP '+x.status+').');}).always(function(){b.prop('disabled',false);});});})(jQuery);</script>
