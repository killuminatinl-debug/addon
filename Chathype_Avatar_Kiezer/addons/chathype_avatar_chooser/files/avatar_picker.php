<?php
require(__DIR__ . '/../../../system/config_session.php');
require(__DIR__ . '/../system/settings.php');
if (!chathypeAvatarEnabled() || empty($data['user_id'])) {
    die('');
}
start();
?>
<div class="modal_content ch_avatar_modal">
    <button type="button" class="modal_close chac_close" id="chacClose" aria-label="Sluiten" title="Sluiten"><span class="bcell_mid"><i class="fa fa-times"></i></span></button>
    <div class="chac_head">
        <div class="chac_icon"><i class="fa fa-user-circle"></i></div>
        <div><div class="chac_title">Maak je eigen avatar</div><div class="chac_sub">Stel zelf je gezicht, kleuren en stijl samen.</div></div>
    </div>
    <div class="chac_layout">
        <section class="chac_preview_col">
            <div class="chac_preview"><canvas id="chacCanvas" width="360" height="360" aria-label="Voorbeeld van je zelfgemaakte avatar"></canvas></div>
            <div class="chac_actions">
                <button type="button" class="chac_btn chac_secondary" id="chacRandom"><i class="fa fa-shuffle"></i> Verrassen</button>
                <button type="button" class="chac_btn" id="chacSave"><i class="fa fa-check"></i> Avatar opslaan</button>
            </div>
            <div id="chacStatus" class="chac_status" role="status" aria-live="polite"></div>
        </section>
        <section class="chac_controls">
            <div class="chac_group"><label for="chacBg">Achtergrond</label><input id="chacBg" type="color" value="#dbeafe"></div>
            <div class="chac_group"><label for="chacSkin">Huidskleur</label><input id="chacSkin" type="color" value="#d99a70"></div>
            <div class="chac_group"><label for="chacHair">Haarkleur</label><input id="chacHair" type="color" value="#3b2419"></div>
            <div class="chac_group"><label for="chacEyes">Oogkleur</label><input id="chacEyes" type="color" value="#3867a8"></div>
            <div class="chac_group"><label for="chacShirt">Shirtkleur</label><input id="chacShirt" type="color" value="#6366f1"></div>
            <div class="chac_group"><label for="chacHairStyle">Kapsel</label><select id="chacHairStyle"><option value="short">Kort</option><option value="long">Lang</option><option value="curly">Krullen</option><option value="bald">Kaal</option></select></div>
            <div class="chac_group"><label for="chacFace">Gezicht</label><select id="chacFace"><option value="smile">Glimlach</option><option value="neutral">Neutraal</option><option value="happy">Blij</option><option value="cool">Stoer</option></select></div>
            <div class="chac_group"><label for="chacAccessory">Accessoire</label><select id="chacAccessory"><option value="none">Geen</option><option value="glasses">Bril</option><option value="cap">Pet</option><option value="earrings">Oorbellen</option></select></div>
        </section>
    </div>
</div>
<style>
.ch_avatar_modal{padding:18px!important;max-height:85vh;overflow:auto}.chac_close{border:0;cursor:pointer;background:transparent;color:inherit;font:inherit;z-index:20}.chac_head{display:flex;align-items:center;gap:12px;margin:4px 0 18px;padding-right:32px}.chac_icon{width:44px;height:44px;flex:0 0 44px;border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;background:linear-gradient(135deg,#6366f1,#ec4899 60%,#06b6d4);box-shadow:0 5px 20px #6366f144}.chac_title{font-size:19px;font-weight:800}.chac_sub{font-size:12px;opacity:.72;margin-top:3px}.chac_layout{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:16px}.chac_preview{display:flex;align-items:center;justify-content:center;overflow:hidden;border-radius:16px;background:#e0e7ff;max-width:100%}.chac_preview canvas{display:block;width:100%;max-width:360px;height:auto}.chac_controls{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:9px;align-content:start}.chac_group{min-width:0;padding:9px;border:1px solid rgba(127,127,127,.2);border-radius:10px;background:rgba(127,127,127,.06)}.chac_group label{display:block;font-size:12px;font-weight:700;margin-bottom:6px}.chac_group select,.chac_group input[type=color]{width:100%;height:36px;min-height:36px;padding:2px;border-radius:6px}.chac_actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}.chac_btn{border:0;border-radius:9px;padding:10px 12px;cursor:pointer;font-weight:700;background:#6366f1;color:#fff}.chac_btn:hover{filter:brightness(1.06)}.chac_btn:disabled{opacity:.6;cursor:wait}.chac_secondary{background:#64748b}.chac_status{min-height:18px;margin-top:9px;font-size:12px;overflow-wrap:anywhere}.chac_status.error{color:#dc2626}.chac_status.ok{color:#16a34a}@media(max-width:560px){.chac_layout{grid-template-columns:1fr}.chac_preview{max-width:340px;margin:auto;width:100%}.chac_controls{grid-template-columns:repeat(2,minmax(0,1fr))}.chac_btn{flex:1}}
</style>
<script data-cfasync="false">
(function($){
    var root = document.querySelector('.ch_avatar_modal');
    if (!root || root.getAttribute('data-chac-ready') === '1') return;
    root.setAttribute('data-chac-ready','1');
    function closeCreator(){
        var closed = false;
        try { if (typeof hideOver === 'function') { hideOver(); closed = true; } } catch(e) {}
        try { if (typeof hideModal === 'function') { hideModal(); closed = true; } } catch(e) {}
        try { if (typeof closeOver === 'function') { closeOver(); closed = true; } } catch(e) {}
        /* Fallback for CodyChat skins where the close helper is absent. */
        window.setTimeout(function(){
            if (!document.body.contains(root)) return;
            var modal = root.closest('.modal_content');
            var wrappers = [];
            if (modal) {
                var p = modal.parentElement;
                for (var i=0; p && i<5; i++, p=p.parentElement) {
                    if (p.id && /over|modal/i.test(p.id)) wrappers.push(p);
                    if (p.classList && (p.classList.contains('over_modal') || p.classList.contains('modal_back') || p.classList.contains('modal_wrapper'))) wrappers.push(p);
                }
            }
            if (wrappers.length) wrappers.forEach(function(el){el.style.display='none';});
            else if (modal) modal.style.display='none';
        }, 80);
    }
    var closeButton = root.querySelector('#chacClose');
    if (closeButton) closeButton.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); closeCreator(); });
    var canvas = root.querySelector('#chacCanvas');
    var ctx = canvas.getContext('2d');
    var val = function(id){ return root.querySelector('#chac'+id).value; };
    function draw(){
        ctx.clearRect(0,0,360,360);
        ctx.fillStyle=val('Bg'); ctx.fillRect(0,0,360,360);
        ctx.globalAlpha=.16; ctx.fillStyle='#ffffff'; ctx.beginPath(); ctx.arc(180,150,125,0,Math.PI*2); ctx.fill(); ctx.globalAlpha=1;
        /* shirt and neck */
        ctx.fillStyle=val('Shirt'); ctx.beginPath(); ctx.moveTo(48,360); ctx.lineTo(72,278); ctx.quadraticCurveTo(180,220,288,278); ctx.lineTo(312,360); ctx.closePath(); ctx.fill();
        ctx.fillStyle=val('Skin'); ctx.fillRect(151,218,58,59);
        /* ears and face */
        ctx.beginPath(); ctx.ellipse(103,164,17,27,0,0,Math.PI*2); ctx.ellipse(257,164,17,27,0,0,Math.PI*2); ctx.fill();
        ctx.beginPath(); ctx.ellipse(180,160,78,96,0,0,Math.PI*2); ctx.fill();
        /* hair */
        var hair=val('HairStyle'); ctx.fillStyle=val('Hair');
        if(hair==='short'){
            ctx.beginPath();ctx.moveTo(103,151);ctx.lineTo(103,108);ctx.quadraticCurveTo(110,57,180,57);ctx.quadraticCurveTo(252,57,257,110);ctx.lineTo(257,151);ctx.quadraticCurveTo(220,116,204,112);ctx.quadraticCurveTo(158,136,103,151);ctx.closePath();ctx.fill();
        } else if(hair==='long'){
            ctx.beginPath();ctx.moveTo(101,167);ctx.lineTo(98,105);ctx.quadraticCurveTo(180,25,262,105);ctx.lineTo(263,258);ctx.lineTo(238,252);ctx.lineTo(235,137);ctx.quadraticCurveTo(180,99,125,137);ctx.lineTo(122,252);ctx.lineTo(98,245);ctx.closePath();ctx.fill();
        } else if(hair==='curly'){
            ctx.beginPath();ctx.ellipse(180,93,80,42,0,0,Math.PI*2);ctx.fill();
            for(var x=111;x<252;x+=23){ctx.beginPath();ctx.arc(x,82+(x%3)*3,18,0,Math.PI*2);ctx.fill();}
        }
        /* eyebrows, eyes and nose */
        ctx.strokeStyle=val('Hair');ctx.lineWidth=5;ctx.lineCap='round';ctx.beginPath();ctx.moveTo(132,135);ctx.lineTo(157,132);ctx.moveTo(203,132);ctx.lineTo(228,135);ctx.stroke();
        ctx.fillStyle='#fff';ctx.beginPath();ctx.ellipse(145,151,13,9,0,0,Math.PI*2);ctx.ellipse(215,151,13,9,0,0,Math.PI*2);ctx.fill();
        ctx.fillStyle=val('Eyes');ctx.beginPath();ctx.arc(146,152,6,0,Math.PI*2);ctx.arc(214,152,6,0,Math.PI*2);ctx.fill();
        ctx.fillStyle='#111827';ctx.beginPath();ctx.arc(147,152,2.5,0,Math.PI*2);ctx.arc(215,152,2.5,0,Math.PI*2);ctx.fill();
        ctx.strokeStyle='rgba(90,45,30,.5)';ctx.lineWidth=3;ctx.beginPath();ctx.moveTo(180,160);ctx.lineTo(172,184);ctx.quadraticCurveTo(180,190,188,184);ctx.stroke();
        ctx.strokeStyle='#783b36';ctx.lineWidth=4;ctx.beginPath();
        if(val('Face')==='neutral'){ctx.moveTo(158,207);ctx.lineTo(202,207);}
        else if(val('Face')==='cool'){ctx.moveTo(158,204);ctx.quadraticCurveTo(180,212,202,204);}
        else {ctx.moveTo(156,201);ctx.quadraticCurveTo(180,val('Face')==='happy'?231:222,204,201);}
        ctx.stroke();
        if(val('Face')==='happy'){ctx.fillStyle='#fff';ctx.fillRect(164,205,32,8);}
        /* accessories */
        var ac=val('Accessory');
        if(ac==='glasses'){ctx.strokeStyle='#202938';ctx.lineWidth=5;ctx.beginPath();ctx.rect(124,139,42,29);ctx.rect(194,139,42,29);ctx.moveTo(166,150);ctx.lineTo(194,150);ctx.stroke();}
        if(ac==='cap'){ctx.fillStyle=val('Hair');ctx.beginPath();ctx.ellipse(180,91,81,20,0,Math.PI,Math.PI*2);ctx.fill();ctx.fillRect(103,90,155,9);}
        if(ac==='earrings'){ctx.fillStyle='#fbbf24';ctx.beginPath();ctx.arc(103,190,5,0,Math.PI*2);ctx.arc(257,190,5,0,Math.PI*2);ctx.fill();}
        ctx.fillStyle='#fff';ctx.globalAlpha=.75;ctx.beginPath();ctx.moveTo(153,257);ctx.lineTo(180,276);ctx.lineTo(207,257);ctx.lineTo(198,250);ctx.lineTo(180,263);ctx.lineTo(162,250);ctx.closePath();ctx.fill();ctx.globalAlpha=1;
    }
    root.querySelectorAll('input,select').forEach(function(el){el.addEventListener('input',draw);el.addEventListener('change',draw);});
    root.querySelector('#chacRandom').addEventListener('click',function(){
        root.querySelectorAll('input[type=color]').forEach(function(el){el.value='#'+Math.floor(Math.random()*16777215).toString(16).padStart(6,'0');});
        ['HairStyle','Face','Accessory'].forEach(function(id){var el=root.querySelector('#chac'+id);el.selectedIndex=Math.floor(Math.random()*el.options.length);});
        draw();
    });
    root.querySelector('#chacSave').addEventListener('click',function(){
        var button=this, status=root.querySelector('#chacStatus');
        button.disabled=true;status.className='chac_status';status.textContent='Avatar opslaan…';
        $.ajax({
            url:'addons/chathype_avatar_chooser/system/action.php',
            method:'POST',
            data:{save_custom_avatar:1,image:canvas.toDataURL('image/png')},
            cache:false,
            dataType:'text'
        }).done(function(response){
            if(String(response).trim()==='5'){
                status.className='chac_status ok';status.textContent='Je avatar is opgeslagen!';
                if(typeof callSuccess==='function' && typeof system!=='undefined') callSuccess(system.saved || 'Opgeslagen');
                setTimeout(function(){if(typeof hideOver==='function')hideOver();window.location.reload();},500);
            } else {
                status.className='chac_status error';status.textContent='Opslaan mislukt. Controleer of de map avatar schrijfbaar is.';
            }
        }).fail(function(xhr){
            status.className='chac_status error';status.textContent='Opslaan mislukt (HTTP '+xhr.status+').';
        }).always(function(){button.disabled=false;});
    });
    draw();
})(jQuery);
</script>
<?php echo boomOverModal(clean(), 620); ?>
