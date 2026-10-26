<?php
require(__DIR__ . '/../../../system/config_session.php');
require(__DIR__ . '/../system/settings.php');
if (!chathypeAvatarEnabled() || empty($data['user_id'])) {
    die('');
}
start();
ob_start();
?>
<div class="ch_avatar_modal">
    <div class="chac_head">
        <div class="chac_icon"><i class="fa fa-user-circle"></i></div>
        <div><div class="chac_title">Maak je eigen avatar</div><div class="chac_sub">Stel jouw eigen Chathype-personage samen. Pas je look live aan en sla hem op als profielfoto.</div></div>
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
            <div class="chac_group chac_wide"><label>Huidskleur</label><div class="chac_swatches" data-input="Skin"><button type="button" data-color="#f7d7bd" style="--sw:#f7d7bd" aria-label="Lichte huidskleur"></button><button type="button" data-color="#e9b994" style="--sw:#e9b994" aria-label="Huidskleur"></button><button type="button" data-color="#c98259" style="--sw:#c98259" aria-label="Warme huidskleur"></button><button type="button" data-color="#985b3d" style="--sw:#985b3d" aria-label="Donkere huidskleur"></button><button type="button" data-color="#623b2b" style="--sw:#623b2b" aria-label="Diepe huidskleur"></button></div></div>
            <div class="chac_group"><label for="chacBg">Achtergrond</label><input id="chacBg" type="color" value="#dbeafe"></div>
            <div class="chac_group"><label for="chacSkin">Huidskleur</label><input id="chacSkin" type="color" value="#d99a70"></div>
            <div class="chac_group"><label for="chacHair">Haarkleur</label><input id="chacHair" type="color" value="#3b2419"></div>
            <div class="chac_group"><label for="chacEyes">Oogkleur</label><input id="chacEyes" type="color" value="#3867a8"></div>
            <div class="chac_group"><label for="chacShirt">Shirtkleur</label><input id="chacShirt" type="color" value="#6366f1"></div>
            <div class="chac_group chac_choices chac_wide"><label>Kapsel</label><input type="hidden" id="chacHairStyle" value="fade"><div class="chac_options" data-target="HairStyle"><button type="button" class="is_active" data-value="fade">Fade</button><button type="button" data-value="short">Kort</button><button type="button" data-value="long">Lang</button><button type="button" data-value="curly">Krullen</button><button type="button" data-value="afro">Afro</button><button type="button" data-value="bun">Knot</button><button type="button" data-value="fringe">Pony</button><button type="button" data-value="bald">Kaal</button></div></div>
            <div class="chac_group chac_choices"><label>Gezicht</label><input type="hidden" id="chacFace" value="smile"><div class="chac_options" data-target="Face"><button type="button" class="is_active" data-value="smile">Glimlach</button><button type="button" data-value="neutral">Neutraal</button><button type="button" data-value="happy">Blij</button><button type="button" data-value="cool">Stoer</button></div></div>
            <div class="chac_group chac_choices"><label>Accessoire</label><input type="hidden" id="chacAccessory" value="none"><div class="chac_options" data-target="Accessory"><button type="button" class="is_active" data-value="none">Geen</button><button type="button" data-value="glasses">Bril</button><button type="button" data-value="sunglasses">Zonnebril</button><button type="button" data-value="cap">Pet</button><button type="button" data-value="earrings">Oorbellen</button><button type="button" data-value="beard">Baard</button></div></div>
            <div class="chac_group chac_choices"><label>Outfit</label><input type="hidden" id="chacOutfit" value="hoodie"><div class="chac_options" data-target="Outfit"><button type="button" class="is_active" data-value="hoodie">Hoodie</button><button type="button" data-value="jacket">Jasje</button><button type="button" data-value="tee">T-shirt</button><button type="button" data-value="smart">Netjes</button></div></div>
            <div class="chac_group chac_choices"><label>Ogen</label><input type="hidden" id="chacEyeStyle" value="round"><div class="chac_options" data-target="EyeStyle"><button type="button" class="is_active" data-value="round">Rond</button><button type="button" data-value="sleepy">Relaxed</button><button type="button" data-value="wink">Knipoog</button></div></div>
        </section>
    </div>
</div>
<style>
/* Chathype Avatar Studio — all styles are deliberately scoped to this addon. */
.ch_avatar_modal{display:block!important;position:relative;width:100%!important;max-width:1120px;margin:0 auto;padding:clamp(14px,2.4vw,28px)!important;max-height:88vh;overflow:auto;overscroll-behavior:contain;background:#f6f7fb!important;color:#191a25!important;border:1px solid #e7e8f0;border-radius:24px;box-shadow:0 24px 80px rgba(18,20,40,.28);font-family:inherit;line-height:1.45}
.ch_avatar_modal,.ch_avatar_modal *{box-sizing:border-box}
.ch_avatar_modal button,.ch_avatar_modal input{font:inherit}
.ch_avatar_modal .chac_head{display:flex;align-items:center;gap:15px;margin:0 0 22px;padding:0 0 20px;border:0;border-bottom:1px solid #e4e6ef}
.ch_avatar_modal .chac_icon{display:flex;align-items:center;justify-content:center;flex:0 0 54px;width:54px;height:54px;border-radius:18px;background:#fffc00;color:#171923;font-size:26px;box-shadow:0 7px 18px rgba(23,25,35,.10)}
.ch_avatar_modal .chac_title{margin:0;color:#171923;font-size:clamp(21px,2.5vw,29px);font-weight:900;letter-spacing:-.8px;line-height:1.15}
.ch_avatar_modal .chac_sub{margin-top:7px;color:#747588;font-size:13px;line-height:1.55}
.ch_avatar_modal .chac_layout{display:grid;grid-template-columns:minmax(250px,.82fr) minmax(0,1.18fr);align-items:start;gap:clamp(14px,2vw,24px)}
.ch_avatar_modal .chac_preview_col{position:sticky;top:0;min-width:0;padding:12px;border:1px solid #e6e7ef;border-radius:22px;background:#fff;box-shadow:0 8px 28px rgba(25,27,45,.045)}
.ch_avatar_modal .chac_preview{position:relative;display:flex;align-items:center;justify-content:center;width:100%;overflow:hidden;isolation:isolate;aspect-ratio:1/1;border:1px solid rgba(20,20,20,.06);border-radius:17px;background:radial-gradient(ellipse at 50% 24%,#fff 0%,#fff9b8 32%,#ffec00 75%,#f4d900 100%)}
.ch_avatar_modal .chac_preview:before{content:"";position:absolute;z-index:-1;width:78%;aspect-ratio:1;top:8%;left:11%;border-radius:50%;background:rgba(255,255,255,.36);pointer-events:none}
.ch_avatar_modal .chac_preview canvas{display:block;position:relative;width:100%;height:auto;max-width:420px;aspect-ratio:1/1;object-fit:contain;filter:drop-shadow(0 12px 13px rgba(15,23,42,.12))}
.ch_avatar_modal .chac_actions{display:grid;grid-template-columns:minmax(0,.8fr) minmax(0,1.2fr);gap:9px;margin-top:12px}
.ch_avatar_modal .chac_btn{display:inline-flex!important;align-items:center;justify-content:center;gap:8px;min-width:0;min-height:46px;margin:0!important;padding:11px 12px!important;border:1px solid #171923!important;border-radius:12px!important;background:#171923!important;color:#fff!important;font-size:12px!important;font-weight:850!important;line-height:1.3;white-space:normal;cursor:pointer;box-shadow:0 5px 12px rgba(20,20,30,.10);transition:transform .15s,box-shadow .15s}
.ch_avatar_modal .chac_btn:hover{transform:translateY(-1px);box-shadow:0 8px 17px rgba(20,20,30,.16)}
.ch_avatar_modal .chac_btn:focus-visible,.ch_avatar_modal .chac_options button:focus-visible,.ch_avatar_modal .chac_swatches button:focus-visible{outline:3px solid #6366f1;outline-offset:3px}
.ch_avatar_modal .chac_btn:disabled{opacity:.62;cursor:wait;transform:none}
.ch_avatar_modal .chac_btn.chac_secondary{border-color:#e0e2eb!important;background:#fff!important;color:#303144!important;box-shadow:none}
.ch_avatar_modal .chac_status{min-height:18px;margin:10px 2px 0;color:#77788a;font-size:12px;overflow-wrap:anywhere}
.ch_avatar_modal .chac_status.error{color:#c62845}.ch_avatar_modal .chac_status.ok{color:#16834a}
.ch_avatar_modal .chac_controls{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:11px;min-width:0;align-content:start}
.ch_avatar_modal .chac_group{display:block;min-width:0;margin:0!important;padding:14px!important;border:1px solid #e4e6ef!important;border-radius:15px!important;background:#fff!important;box-shadow:0 4px 15px rgba(25,25,45,.035)!important}
.ch_avatar_modal .chac_group.chac_wide{grid-column:1/-1}
.ch_avatar_modal .chac_group>label{display:flex;align-items:center;gap:7px;margin:0 0 11px!important;padding:0!important;color:#77788a!important;font-size:10px!important;font-weight:900!important;letter-spacing:1.05px;text-transform:uppercase;line-height:1.4}
.ch_avatar_modal .chac_group input[type=color]{display:block;width:100%;height:42px;min-height:42px;margin:0;padding:4px;border:1px solid #e1e3ec;border-radius:10px;background:#fff;cursor:pointer}
.ch_avatar_modal .chac_swatches{display:flex;align-items:center;gap:12px;flex-wrap:wrap;padding:3px 2px 5px}
.ch_avatar_modal .chac_swatches button{display:block;flex:0 0 30px;width:30px;height:30px;min-width:30px;margin:0;padding:0;border:3px solid #fff;border-radius:50%;background:var(--sw);box-shadow:0 0 0 1px #d7d8e3;cursor:pointer;transition:transform .15s,box-shadow .15s}
.ch_avatar_modal .chac_swatches button:hover{transform:scale(1.08)}
.ch_avatar_modal .chac_swatches button.is_active{box-shadow:0 0 0 3px #171923;transform:scale(1.04)}
.ch_avatar_modal .chac_options{display:flex;align-items:center;flex-wrap:wrap;gap:7px}
.ch_avatar_modal .chac_options button{display:inline-flex;align-items:center;justify-content:center;min-width:0;min-height:35px;margin:0;padding:8px 11px;border:1px solid #e2e4ed;border-radius:10px;background:#f7f8fb;color:#424457;font-size:11px;font-weight:800;line-height:1.25;white-space:nowrap;cursor:pointer;transition:background .15s,border-color .15s,transform .15s,color .15s}
.ch_avatar_modal .chac_options button:hover{border-color:#b8bacb;background:#fff;transform:translateY(-1px)}
.ch_avatar_modal .chac_options button.is_active{border-color:#171923;background:#171923;color:#fff;box-shadow:0 3px 8px rgba(23,25,35,.12)}
@media(max-width:760px){
 .ch_avatar_modal{max-height:91vh;padding:15px!important;border-radius:19px}
 .ch_avatar_modal .chac_head{gap:11px;margin-bottom:15px;padding-bottom:14px}
 .ch_avatar_modal .chac_icon{width:44px;height:44px;flex-basis:44px;border-radius:14px;font-size:22px}
 .ch_avatar_modal .chac_layout{grid-template-columns:minmax(0,1fr);gap:13px}
 .ch_avatar_modal .chac_preview_col{position:relative;top:auto;display:grid;grid-template-columns:minmax(0,1fr);max-width:430px;width:100%;margin:0 auto;padding:10px}
 .ch_avatar_modal .chac_preview{max-width:100%}
 .ch_avatar_modal .chac_controls{gap:9px}
 .ch_avatar_modal .chac_group{padding:11px!important}
 .ch_avatar_modal .chac_options{gap:6px}
 .ch_avatar_modal .chac_options button{padding:8px 9px}
}
@media(max-width:420px){
 .ch_avatar_modal{padding:11px!important}
 .ch_avatar_modal .chac_controls{grid-template-columns:minmax(0,1fr)}
 .ch_avatar_modal .chac_group.chac_wide{grid-column:auto}
 .ch_avatar_modal .chac_actions{grid-template-columns:1fr}
 .ch_avatar_modal .chac_btn{width:100%}
 .ch_avatar_modal .chac_title{font-size:20px}
 .ch_avatar_modal .chac_sub{font-size:12px}
}
@media(prefers-reduced-motion:reduce){.ch_avatar_modal *{transition:none!important;scroll-behavior:auto!important}}
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
    var canvas = root.querySelector('#chacCanvas');
    var ctx = canvas.getContext('2d');
    var val = function(id){ return root.querySelector('#chac'+id).value; };
    function draw(){
        var skin=val('Skin'), hairColor=val('Hair'), eyeColor=val('Eyes'), shirt=val('Shirt');
        var hairstyle=val('HairStyle'), face=val('Face'), accessory=val('Accessory');
        var outfit=val('Outfit'), eyeStyle=val('EyeStyle');
        ctx.clearRect(0,0,360,360);
        ctx.save();
        /* Soft studio backdrop and graphic accents */
        var bg=ctx.createLinearGradient(0,0,360,360);bg.addColorStop(0,val('Bg'));bg.addColorStop(1,'#c4b5fd');
        ctx.fillStyle=bg;ctx.fillRect(0,0,360,360);
        ctx.globalAlpha=.18;ctx.fillStyle='#fff';
        [[48,50,15],[306,76,22],[302,278,10],[55,285,19],[277,42,7]].forEach(function(p){ctx.beginPath();ctx.arc(p[0],p[1],p[2],0,Math.PI*2);ctx.fill();});
        ctx.globalAlpha=1;
        /* shoulder silhouette / outfit */
        ctx.fillStyle=shirt;ctx.beginPath();ctx.moveTo(34,360);ctx.lineTo(46,305);ctx.quadraticCurveTo(61,270,118,258);ctx.lineTo(242,258);ctx.quadraticCurveTo(299,270,314,305);ctx.lineTo(326,360);ctx.closePath();ctx.fill();
        if(outfit==='hoodie'){
            ctx.fillStyle='rgba(15,23,42,.22)';ctx.beginPath();ctx.moveTo(115,267);ctx.quadraticCurveTo(180,330,245,267);ctx.lineTo(224,253);ctx.lineTo(136,253);ctx.closePath();ctx.fill();
            ctx.strokeStyle='rgba(255,255,255,.65)';ctx.lineWidth=3;ctx.beginPath();ctx.moveTo(166,290);ctx.lineTo(163,322);ctx.moveTo(194,290);ctx.lineTo(197,322);ctx.stroke();
        } else if(outfit==='jacket'){
            ctx.fillStyle='#f8fafc';ctx.beginPath();ctx.moveTo(160,264);ctx.lineTo(180,294);ctx.lineTo(200,264);ctx.lineTo(190,360);ctx.lineTo(170,360);ctx.closePath();ctx.fill();
            ctx.strokeStyle='rgba(15,23,42,.35)';ctx.lineWidth=2;ctx.beginPath();ctx.moveTo(180,294);ctx.lineTo(180,360);ctx.stroke();
        } else if(outfit==='smart'){
            ctx.fillStyle='#f8fafc';ctx.beginPath();ctx.moveTo(137,261);ctx.lineTo(180,292);ctx.lineTo(223,261);ctx.lineTo(209,360);ctx.lineTo(151,360);ctx.closePath();ctx.fill();
            ctx.fillStyle='#ec4899';ctx.beginPath();ctx.moveTo(180,292);ctx.lineTo(190,310);ctx.lineTo(180,345);ctx.lineTo(170,310);ctx.closePath();ctx.fill();
        }
        /* Neck, ears, and face shape */
        ctx.fillStyle=skin;ctx.beginPath();ctx.moveTo(150,222);ctx.lineTo(210,222);ctx.lineTo(215,276);ctx.quadraticCurveTo(180,294,145,276);ctx.closePath();ctx.fill();
        ctx.beginPath();ctx.ellipse(103,163,18,29,0,0,Math.PI*2);ctx.ellipse(257,163,18,29,0,0,Math.PI*2);ctx.fill();
        ctx.fillStyle='rgba(111,53,42,.24)';ctx.beginPath();ctx.ellipse(104,166,8,15,0,0,Math.PI*2);ctx.ellipse(256,166,8,15,0,0,Math.PI*2);ctx.fill();
        ctx.fillStyle=skin;ctx.beginPath();ctx.moveTo(180,67);ctx.bezierCurveTo(230,65,259,101,256,159);ctx.bezierCurveTo(253,211,222,246,180,248);ctx.bezierCurveTo(138,246,107,211,104,159);ctx.bezierCurveTo(101,101,130,65,180,67);ctx.closePath();ctx.fill();
        /* soft cheek blush */
        ctx.globalAlpha=.18;ctx.fillStyle='#ef4444';ctx.beginPath();ctx.ellipse(132,187,17,8,0,0,Math.PI*2);ctx.ellipse(228,187,17,8,0,0,Math.PI*2);ctx.fill();ctx.globalAlpha=1;
        /* Hair drawn behind and over forehead */
        ctx.fillStyle=hairColor;
        if(hairstyle==='long'){
            ctx.beginPath();ctx.moveTo(103,164);ctx.lineTo(94,111);ctx.quadraticCurveTo(92,49,180,43);ctx.quadraticCurveTo(269,49,266,111);ctx.lineTo(257,225);ctx.quadraticCurveTo(244,246,230,230);ctx.lineTo(232,131);ctx.quadraticCurveTo(180,96,128,131);ctx.lineTo(130,230);ctx.quadraticCurveTo(112,244,103,218);ctx.closePath();ctx.fill();
        }
        if(hairstyle==='afro'){
            for(var ax=110;ax<=250;ax+=20){ctx.beginPath();ctx.arc(ax,83+(ax%3)*3,24,0,Math.PI*2);ctx.fill();}
            ctx.beginPath();ctx.ellipse(180,104,76,43,0,Math.PI,Math.PI*2);ctx.fill();
        } else if(hairstyle==='curly'){
            ctx.beginPath();ctx.ellipse(180,91,78,42,0,Math.PI,Math.PI*2);ctx.fill();
            for(var cx=111;cx<=249;cx+=19){ctx.beginPath();ctx.arc(cx,83+(cx%3)*2,17,0,Math.PI*2);ctx.fill();}
        } else if(hairstyle==='bun'){
            ctx.beginPath();ctx.ellipse(180,88,68,40,0,Math.PI,Math.PI*2);ctx.fill();
            ctx.beginPath();ctx.arc(217,48,24,0,Math.PI*2);ctx.fill();
            ctx.beginPath();ctx.moveTo(111,111);ctx.quadraticCurveTo(180,72,249,111);ctx.quadraticCurveTo(238,65,180,63);ctx.quadraticCurveTo(122,65,111,111);ctx.fill();
        } else if(hairstyle==='short' || hairstyle==='fade'){
            ctx.beginPath();ctx.moveTo(103,145);ctx.lineTo(100,104);ctx.quadraticCurveTo(112,48,180,48);ctx.quadraticCurveTo(248,48,260,104);ctx.lineTo(257,145);ctx.quadraticCurveTo(225,115,204,105);ctx.quadraticCurveTo(158,129,103,145);ctx.closePath();ctx.fill();
            if(hairstyle==='fade'){ctx.globalAlpha=.24;ctx.fillStyle='#fff';ctx.beginPath();ctx.moveTo(106,112);ctx.quadraticCurveTo(117,66,144,60);ctx.lineTo(132,100);ctx.closePath();ctx.fill();ctx.globalAlpha=1;}
        } else if(hairstyle==='fringe'){
            ctx.beginPath();ctx.moveTo(103,151);ctx.lineTo(103,103);ctx.quadraticCurveTo(114,49,180,49);ctx.quadraticCurveTo(246,49,257,103);ctx.lineTo(257,143);ctx.quadraticCurveTo(228,130,222,104);ctx.quadraticCurveTo(204,135,184,113);ctx.quadraticCurveTo(158,145,142,116);ctx.quadraticCurveTo(128,142,103,151);ctx.closePath();ctx.fill();
        }
        /* eyebrows */
        ctx.strokeStyle=hairColor;ctx.lineWidth=6;ctx.lineCap='round';ctx.beginPath();ctx.moveTo(128,126);ctx.quadraticCurveTo(143,118,159,126);ctx.moveTo(201,126);ctx.quadraticCurveTo(217,118,232,126);ctx.stroke();
        /* expressive eyes */
        function eye(x, wink){
            ctx.fillStyle='#fff';ctx.beginPath();ctx.ellipse(x,151,14,11,0,0,Math.PI*2);ctx.fill();
            if(wink){ctx.strokeStyle=hairColor;ctx.lineWidth=4;ctx.beginPath();ctx.moveTo(x-13,151);ctx.quadraticCurveTo(x,160,x+13,149);ctx.stroke();return;}
            ctx.fillStyle=eyeColor;ctx.beginPath();ctx.arc(x,152,7,0,Math.PI*2);ctx.fill();
            ctx.fillStyle='#172033';ctx.beginPath();ctx.arc(x,153,3.4,0,Math.PI*2);ctx.fill();
            ctx.fillStyle='#fff';ctx.beginPath();ctx.arc(x-2,149,2,0,Math.PI*2);ctx.fill();
        }
        eye(145,eyeStyle==='wink');eye(215,false);
        if(eyeStyle==='sleepy'){ctx.strokeStyle=skin;ctx.lineWidth=5;ctx.beginPath();ctx.moveTo(130,147);ctx.lineTo(159,147);ctx.moveTo(201,147);ctx.lineTo(230,147);ctx.stroke();}
        /* nose and mouth */
        ctx.strokeStyle='rgba(113,62,45,.65)';ctx.lineWidth=3;ctx.beginPath();ctx.moveTo(180,157);ctx.lineTo(173,181);ctx.quadraticCurveTo(180,187,188,181);ctx.stroke();
        ctx.fillStyle='rgba(239,68,68,.16)';ctx.beginPath();ctx.ellipse(180,199,25,15,0,0,Math.PI*2);ctx.fill();
        ctx.strokeStyle='#873f45';ctx.lineWidth=4;ctx.lineCap='round';ctx.beginPath();
        if(face==='neutral'){ctx.moveTo(160,204);ctx.lineTo(200,204);}
        else if(face==='cool'){ctx.moveTo(158,201);ctx.quadraticCurveTo(180,209,202,199);}
        else if(face==='happy'){ctx.moveTo(156,199);ctx.quadraticCurveTo(180,232,204,199);}
        else {ctx.moveTo(158,200);ctx.quadraticCurveTo(180,221,202,200);}
        ctx.stroke();
        if(face==='happy'){ctx.fillStyle='#fff';ctx.beginPath();ctx.moveTo(164,204);ctx.quadraticCurveTo(180,211,196,204);ctx.lineTo(193,212);ctx.quadraticCurveTo(180,217,167,212);ctx.closePath();ctx.fill();}
        /* optional beard and accessories */
        if(accessory==='beard'){ctx.fillStyle=hairColor;ctx.globalAlpha=.92;ctx.beginPath();ctx.moveTo(126,185);ctx.quadraticCurveTo(130,235,180,242);ctx.quadraticCurveTo(230,235,234,185);ctx.quadraticCurveTo(216,210,202,211);ctx.quadraticCurveTo(180,222,158,211);ctx.quadraticCurveTo(143,209,126,185);ctx.fill();ctx.globalAlpha=1;}
        if(accessory==='glasses' || accessory==='sunglasses'){
            ctx.strokeStyle=accessory==='glasses'?'#263449':'#111827';ctx.fillStyle=accessory==='glasses'?'rgba(219,234,254,.18)':'rgba(17,24,39,.92)';ctx.lineWidth=5;
            ctx.beginPath();ctx.roundRect(122,137,45,30,8);ctx.roundRect(193,137,45,30,8);ctx.fill();ctx.stroke();ctx.beginPath();ctx.moveTo(167,148);ctx.lineTo(193,148);ctx.stroke();
        }
        if(accessory==='cap'){
            ctx.fillStyle=hairColor;ctx.beginPath();ctx.ellipse(180,86,78,24,0,Math.PI,Math.PI*2);ctx.fill();ctx.beginPath();ctx.ellipse(190,98,70,9,0,0,Math.PI);ctx.fill();
            ctx.fillStyle='#fff';ctx.globalAlpha=.75;ctx.beginPath();ctx.arc(180,75,5,0,Math.PI*2);ctx.fill();ctx.globalAlpha=1;
        }
        if(accessory==='earrings'){ctx.fillStyle='#fbbf24';ctx.beginPath();ctx.arc(103,190,6,0,Math.PI*2);ctx.arc(257,190,6,0,Math.PI*2);ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.beginPath();ctx.arc(103,190,3,0,Math.PI*2);ctx.arc(257,190,3,0,Math.PI*2);ctx.stroke();}
        /* highlights */
        ctx.globalAlpha=.28;ctx.fillStyle='#fff';ctx.beginPath();ctx.ellipse(131,115,9,20,-.35,0,Math.PI*2);ctx.fill();ctx.globalAlpha=1;
        ctx.restore();
    }
    root.querySelectorAll('.chac_swatches').forEach(function(group){group.addEventListener('click',function(e){var b=e.target.closest('button[data-color]');if(!b)return;var input=root.querySelector('#chac'+group.getAttribute('data-input'));if(input){input.value=b.getAttribute('data-color');group.querySelectorAll('button').forEach(function(item){item.classList.toggle('is_active',item===b);});draw();}});});
    root.querySelectorAll('input').forEach(function(el){if(el.type!=='hidden'){el.addEventListener('input',draw);el.addEventListener('change',draw);}});
    root.querySelectorAll('.chac_options').forEach(function(group){group.addEventListener('click',function(e){var b=e.target.closest('button[data-value]');if(!b)return;var target=root.querySelector('#chac'+group.getAttribute('data-target'));if(target)target.value=b.getAttribute('data-value');group.querySelectorAll('button').forEach(function(item){item.classList.toggle('is_active',item===b);});draw();});});
    root.querySelector('#chacRandom').addEventListener('click',function(){
        root.querySelectorAll('input[type=color]').forEach(function(el){el.value='#'+Math.floor(Math.random()*16777215).toString(16).padStart(6,'0');});
        ['HairStyle','Face','Accessory','Outfit','EyeStyle'].forEach(function(id){var el=root.querySelector('#chac'+id),group=root.querySelector('.chac_options[data-target="'+id+'"]');if(!el||!group)return;var opts=group.querySelectorAll('button[data-value]'),pick=opts[Math.floor(Math.random()*opts.length)];el.value=pick.getAttribute('data-value');opts.forEach(function(item){item.classList.toggle('is_active',item===pick);});});
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
                status.className='chac_status error';status.textContent=String(response).trim().indexOf('AVATAR_ERROR:')===0 ? 'Opslaan mislukt: '+String(response).trim().replace('AVATAR_ERROR:','').replace(/_/g,' ').toLowerCase()+'.' : 'Opslaan mislukt. Serverantwoord: '+String(response).trim().slice(0,160);
            }
        }).fail(function(xhr){
            status.className='chac_status error';var detail=String(xhr.responseText||'').trim();status.textContent='Opslaan mislukt (HTTP '+xhr.status+'). '+(detail ? detail.slice(0,160) : 'Controleer PHP-errorlog en schrijfpermissies van de avatar-map.');
        }).always(function(){button.disabled=false;});
    });
    draw();
})(jQuery);
</script>
<?php echo ob_get_clean(); ?>
