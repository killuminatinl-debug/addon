<?php
/* Chathype Avatar Kiezer — vernieuwd CodyChat 11-beheercentrum (PHP 7.4+). */
$load_addons = 'chathype_avatar_chooser';
require(__DIR__ . '/../../../system/config_addons.php');
if (!function_exists('canManageAddons') || !canManageAddons()) {
    die();
}
$avatarEnabled = isset($addons['custom1']) ? ((string)$addons['custom1'] !== '0') : true;
?>
<?php echo elementTitle('Chathype Avatar Kiezer', 'loadL(\'admin/setting_addons.php\');'); ?>
<div class="page_full chav-admin" id="chavAdmin">
  <section class="chav-hero">
    <div><div class="chav-eyebrow"><i></i> CHATHYPE ADDONBEHEER</div><h2>Avatar Kiezer <span>beheercentrum</span></h2><p>Beheer de avatar-maker voor je leden, zonder addonbestanden handmatig aan te passen.</p></div>
    <div class="chav-hero-icon"><span class="fa fa-magic"></span></div>
  </section>
  <div class="chav-grid">
    <section class="chav-card">
      <div class="chav-heading"><span class="chav-icon fa fa-sliders"></span><div><h3>Algemene instellingen</h3><p>Beheer de beschikbaarheid van de avatar-maker.</p></div></div>
      <div class="chav-setting">
        <div><strong>Avatar-maker voor leden</strong><p>Laat leden een eigen avatar ontwerpen en opslaan als profielfoto.</p></div>
        <label class="chav-switch" for="chav_enabled"><input type="checkbox" id="chav_enabled" <?php echo $avatarEnabled ? 'checked' : ''; ?>><span class="chav-track"><i></i></span><span class="chav-toggle-label"><?php echo $avatarEnabled ? 'Ingeschakeld' : 'Uitgeschakeld'; ?></span></label>
      </div>
      <div class="chav-actions"><span class="chav-note"><i class="fa fa-shield"></i> Alleen beheerders kunnen dit wijzigen.</span><button id="save_chav_setting" type="button"><i class="fa fa-floppy-o"></i> Instellingen opslaan</button></div>
      <div id="chav_setting_status" role="status" aria-live="polite"></div>
    </section>
    <aside class="chav-card chav-status-card">
      <div class="chav-heading"><span class="chav-icon green fa fa-heartbeat"></span><div><h3>Huidige status</h3><p>Beschikbaarheid voor leden</p></div></div>
      <div class="chav-state"><i id="chav_state_dot" class="<?php echo $avatarEnabled ? 'on' : 'off'; ?>"></i><div><strong id="chav_state_title"><?php echo $avatarEnabled ? 'Avatar-maker actief' : 'Avatar-maker uitgeschakeld'; ?></strong><p id="chav_state_desc"><?php echo $avatarEnabled ? 'Leden kunnen de avatar-maker openen.' : 'Leden kunnen de avatar-maker momenteel niet gebruiken.'; ?></p></div></div>
      <div class="chav-tip"><i class="fa fa-info-circle"></i><p>De wijziging wordt direct opgeslagen. Uitschakelen verwijdert geen bestaande profielfoto’s.</p></div>
    </aside>
  </div>
  <section class="chav-card chav-help">
    <div class="chav-heading"><span class="chav-icon amber fa fa-lightbulb-o"></span><div><h3>Handig om te weten</h3><p>Snelle uitleg voor beheerders</p></div></div>
    <div class="chav-help-grid"><div><b>01</b><strong>Avatar opslaan</strong><p>De gekozen afbeelding wordt de profielfoto van het betreffende lid.</p></div><div><b>02</b><strong>Addon uitschakelen</strong><p>De maker wordt geblokkeerd voor leden; bestaande avatars blijven staan.</p></div><div><b>03</b><strong>Addon verwijderen</strong><p>De uninstall-handler kan originele avatars herstellen die na installatie zijn geback-upt.</p></div></div>
  </section>
</div>
<style>
#chavAdmin{--ci:var(--text_color,#28283d);--cm:var(--gray_text,#85869a);--cl:rgba(128,132,160,.2);color:var(--ci);padding-bottom:20px}
#chavAdmin *{box-sizing:border-box}
#chavAdmin .chav-hero{display:flex;align-items:center;justify-content:space-between;gap:18px;overflow:hidden;position:relative;margin:0 0 16px;padding:27px 29px;border-radius:16px;background:linear-gradient(115deg,#252342,#4d429f 60%,#7165eb);color:#fff;box-shadow:0 9px 25px rgba(74,62,153,.15)}
#chavAdmin .chav-hero:after{content:"";position:absolute;right:95px;top:-170px;width:290px;height:290px;border:1px solid #ffffff30;border-radius:50%;box-shadow:0 0 0 32px #ffffff09,0 0 0 68px #ffffff08}
#chavAdmin .chav-hero>div:first-child{position:relative;z-index:1}
#chavAdmin .chav-eyebrow{display:flex;align-items:center;gap:8px;color:#d8d4ff;font-size:9px;font-weight:900;letter-spacing:1.6px;margin-bottom:9px}
#chavAdmin .chav-eyebrow i{width:7px;height:7px;background:#68f0c0;border-radius:50%;box-shadow:0 0 0 4px #68f0c022}
#chavAdmin .chav-hero h2{margin:0;color:#fff;font-size:clamp(21px,3vw,29px);font-weight:850;letter-spacing:-.7px;line-height:1.25}
#chavAdmin .chav-hero h2 span{color:#c9c2ff}
#chavAdmin .chav-hero p{max-width:580px;margin:9px 0 0;color:#ffffffc9;font-size:12px;line-height:1.7}
#chavAdmin .chav-hero-icon{z-index:1;display:flex;align-items:center;justify-content:center;flex:0 0 70px;width:70px;height:70px;border:1px solid #ffffff40;border-radius:21px;background:#ffffff1c;color:white;font-size:27px}
#chavAdmin .chav-grid{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(245px,.8fr);gap:16px;margin-bottom:16px}
#chavAdmin .chav-card{min-width:0;padding:21px;border:1px solid var(--cl);border-radius:14px;background:var(--body_color,#fff);box-shadow:0 4px 15px #1e204109}
#chavAdmin .chav-heading{display:flex;align-items:center;gap:12px;margin-bottom:20px}
#chavAdmin .chav-icon{display:flex;align-items:center;justify-content:center;flex:0 0 42px;width:42px;height:42px;border-radius:13px;background:#eeecff;color:#6558e8;font-size:17px}
#chavAdmin .chav-icon.green{background:#e5f9f1;color:#15966b}#chavAdmin .chav-icon.amber{background:#fff4df;color:#bd7b14}
#chavAdmin .chav-heading h3{margin:0;color:var(--ci);font-size:14px;font-weight:850}
#chavAdmin .chav-heading p{margin:4px 0 0;color:var(--cm);font-size:11px}
#chavAdmin .chav-setting{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:17px 0;border-top:1px solid var(--cl);border-bottom:1px solid var(--cl)}
#chavAdmin .chav-setting>div{min-width:0}#chavAdmin .chav-setting strong{font-size:12px;font-weight:800}
#chavAdmin .chav-setting p{max-width:430px;margin:6px 0 0;color:var(--cm);font-size:11px;line-height:1.65}
#chavAdmin .chav-switch{display:flex;align-items:center;gap:9px;flex:0 0 auto;cursor:pointer}
#chavAdmin .chav-switch input{position:absolute;width:1px;height:1px;opacity:0}
#chavAdmin .chav-track{position:relative;display:block;width:40px;height:23px;border-radius:30px;background:#c7c8d4;transition:background .18s}
#chavAdmin .chav-track i{position:absolute;left:3px;top:3px;width:17px;height:17px;border-radius:50%;background:#fff;box-shadow:0 1px 4px #0003;transition:transform .18s}
#chavAdmin .chav-switch input:checked+.chav-track{background:#6558e8}#chavAdmin .chav-switch input:checked+.chav-track i{transform:translateX(17px)}
#chavAdmin .chav-switch input:focus-visible+.chav-track{outline:3px solid #6558e84d;outline-offset:3px}
#chavAdmin .chav-toggle-label{min-width:84px;color:var(--cm);font-size:10px;font-weight:800}
#chavAdmin .chav-actions{display:flex;align-items:center;justify-content:space-between;gap:14px;padding-top:17px}
#chavAdmin .chav-note{display:flex;align-items:center;gap:7px;color:var(--cm);font-size:10px;line-height:1.5}#chavAdmin .chav-note i{color:#6558e8}
#chavAdmin #save_chav_setting{display:flex;align-items:center;justify-content:center;gap:8px;min-height:39px;padding:0 15px;border:0;border-radius:10px;background:#6558e8;color:#fff;font-size:11px;font-weight:800;cursor:pointer;box-shadow:0 5px 12px #6558e82e}
#chavAdmin #save_chav_setting:hover{background:#5144d2}#chavAdmin #save_chav_setting:disabled{opacity:.65;cursor:wait}
#chavAdmin #chav_setting_status{font-size:11px;color:var(--cm);overflow-wrap:anywhere}
#chavAdmin #chav_setting_status:not(:empty){margin-top:13px;padding:10px 12px;border-radius:9px;background:#6558e812}
#chavAdmin #chav_setting_status.ok{color:#16835c;background:#16a36f14}#chavAdmin #chav_setting_status.error{color:#c62845;background:#c6284512}
#chavAdmin .chav-state{display:flex;align-items:flex-start;gap:11px;padding:5px 0 18px}
#chavAdmin #chav_state_dot{flex:0 0 10px;width:10px;height:10px;margin-top:4px;border-radius:50%}
#chavAdmin #chav_state_dot.on{background:#19b780;box-shadow:0 0 0 4px #19b78020}#chavAdmin #chav_state_dot.off{background:#a1a3b3;box-shadow:0 0 0 4px #a1a3b326}
#chavAdmin .chav-state strong{font-size:12px;font-weight:850}#chavAdmin .chav-state p{margin:6px 0 0;color:var(--cm);font-size:11px;line-height:1.6}
#chavAdmin .chav-tip{display:flex;align-items:flex-start;gap:9px;padding:12px;border:1px solid #6558e822;border-radius:10px;background:#6558e80d;color:#6558e8}
#chavAdmin .chav-tip i{margin-top:2px}#chavAdmin .chav-tip p{margin:0;color:var(--cm);font-size:10px;line-height:1.65}
#chavAdmin .chav-help{margin-bottom:10px}#chavAdmin .chav-help-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
#chavAdmin .chav-help-grid>div{padding:14px;border:1px solid var(--cl);border-radius:11px}
#chavAdmin .chav-help-grid b{display:block;margin-bottom:10px;color:#6558e8;font-size:10px;letter-spacing:1px}
#chavAdmin .chav-help-grid strong{font-size:11px;font-weight:850}#chavAdmin .chav-help-grid p{margin:7px 0 0;color:var(--cm);font-size:10px;line-height:1.7}
@media(max-width:850px){#chavAdmin .chav-grid{grid-template-columns:1fr}#chavAdmin .chav-help-grid{gap:9px}}
@media(max-width:560px){#chavAdmin .chav-hero{padding:21px 18px}#chavAdmin .chav-hero-icon{flex-basis:52px;width:52px;height:52px;font-size:22px}#chavAdmin .chav-card{padding:16px}#chavAdmin .chav-setting{align-items:flex-start;flex-direction:column;gap:14px}#chavAdmin .chav-actions{align-items:stretch;flex-direction:column}#chavAdmin #save_chav_setting{width:100%}#chavAdmin .chav-help-grid{grid-template-columns:1fr}}
</style>
<script data-cfasync="false">
(function($){
  var root=document.getElementById('chavAdmin');
  if(!root||!window.jQuery)return;
  var $root=$(root),$toggle=$root.find('#chav_enabled'),$button=$root.find('#save_chav_setting'),$status=$root.find('#chav_setting_status');
  function paintState(enabled){
    $root.find('#chav_state_dot').toggleClass('on',enabled).toggleClass('off',!enabled);
    $root.find('#chav_state_title').text(enabled?'Avatar-maker actief':'Avatar-maker uitgeschakeld');
    $root.find('#chav_state_desc').text(enabled?'Leden kunnen de avatar-maker openen.':'Leden kunnen de avatar-maker momenteel niet gebruiken.');
    $root.find('.chav-toggle-label').text(enabled?'Ingeschakeld':'Uitgeschakeld');
  }
  $toggle.off('change.chavAdmin').on('change.chavAdmin',function(){paintState(this.checked);});
  $button.off('click.chavAdmin').on('click.chavAdmin',function(){
    var enabled=$toggle.is(':checked')?'1':'0';
    $button.prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Opslaan…');
    $status.removeClass('ok error').text('Instellingen worden opgeslagen…');
    $.ajax({url:(function(){var p=(typeof getChatPath==='function'?getChatPath():'');if(p&&p.charAt(p.length-1)!=='/')p+='/';return p+'addons/chathype_avatar_chooser/system/action.php';})(),method:'POST',data:{save_avatar_setting:1,chathype_avatar_enabled:enabled},cache:false,dataType:'text'})
    .done(function(response){
      if(String(response).trim()==='5'){
        $status.removeClass('error').addClass('ok').text('✓ Instellingen opgeslagen. De wijziging is direct actief.');
        paintState(enabled==='1');
        if(typeof callSuccess==='function'&&typeof system!=='undefined')callSuccess(system.saved||'Opgeslagen');
      }else{
        $status.removeClass('ok').addClass('error').text('Opslaan mislukt: '+String(response).trim().substring(0,180));
        if(typeof callError==='function'&&typeof system!=='undefined')callError(system.error||'Fout');
      }
    }).fail(function(xhr){
      $status.removeClass('ok').addClass('error').text('Opslaan mislukt (HTTP '+xhr.status+'): '+String(xhr.responseText||'geen servermelding').trim().substring(0,180));
      if(typeof callError==='function'&&typeof system!=='undefined')callError(system.error||'Fout');
    }).always(function(){
      $button.prop('disabled',false).html('<i class="fa fa-floppy-o"></i> Instellingen opslaan');
    });
  });
})(jQuery);
</script>
