<?php
if (!defined('BOOM')) require_once __DIR__ . '/../../../system/config_addons.php';
if (!function_exists('canManageAddons') || !canManageAddons()) { http_response_code(403); exit; }
require_once __DIR__ . '/addons_function.php';
$slug = basename(dirname(__DIR__));
?>
<div class="page_full"><div class="page_element"><div class="tpad15">
<h3>Avatar Frame BLK — beheer</h3>
<p>Upload een PNG-, JPG-, GIF- of WebP-afbeelding (maximaal 5 MB). Frames zijn gratis te kiezen voor spelers.</p>
<div class="setting_element"><input type="file" id="afb-upload-file" accept="image/png,image/jpeg,image/gif,image/webp"><button type="button" class="reg_button theme_btn" id="afb-upload">Frame uploaden</button></div>
<div id="afb-admin-list"><?php echo frames_list_admin(); ?></div>
</div></div></div>
<script data-cfasync="false">
(function($){
  var endpoint='addons/<?php echo rawurlencode($slug); ?>/system/action.php';
  $(document).off('click.afbUpload','#afb-upload').on('click.afbUpload','#afb-upload',function(){
    var input=document.getElementById('afb-upload-file');
    if(!input||!input.files||!input.files[0]){if(window.callError)callError('Selecteer eerst een afbeelding.');return;}
    var fd=new FormData();fd.append('file',input.files[0]);fd.append('add_frame','1');fd.append('token',utk);
    $.ajax({url:endpoint,type:'POST',data:fd,processData:false,contentType:false,dataType:'json'}).done(function(r){
      if(r===1){if(window.callSuccess)callSuccess(system.saved);if(typeof configAddons==='function')configAddons('<?php echo addslashes($slug); ?>');}
      else if(window.callError)callError(r===2?'Ongeldig bestand of bestand groter dan 5 MB.':system.error);
    }).fail(function(){if(window.callError)callError(system.error);});
  });
  $(document).off('click.afbDelete','.afb-admin-card').on('click.afbDelete','.afb-admin-card',function(){
    var id=parseInt($(this).attr('data-id'),10)||0;if(!id)return;
    if(!window.confirm('Dit frame verwijderen?'))return;
    $.post(endpoint,{delete_frame:id,token:utk},function(r){
      if(r===1||r==='1'){if(typeof configAddons==='function')configAddons('<?php echo addslashes($slug); ?>');}
      else if(window.callError)callError(system.error);
    }).fail(function(){if(window.callError)callError(system.error);});
  });
})(jQuery);
</script>
<style><?php @include dirname(__DIR__) . '/files/style.css'; ?></style>
