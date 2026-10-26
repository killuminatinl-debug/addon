<?php
/*
 * Chathype Avatar Creator for CodyChat 11
 * Include/open this file from an authenticated CodyChat session.
 */
require_once __DIR__ . '/../../../system/config_session.php';
require_once __DIR__ . '/../system/settings.php';
if (empty($data['user_id']) || !chathypeCreatorEnabled()) {
    http_response_code(403);
    exit('Je moet ingelogd zijn om een avatar te maken.');
}
?>
<div id="chAvatarCreator" class="chac">
  <style>
  .chac{font-family:inherit;color:var(--body-color,#222);max-width:920px;margin:0 auto;padding:14px}
  .chac *{box-sizing:border-box}.chac-layout{display:grid;grid-template-columns:minmax(220px,1fr) minmax(240px,1.1fr);gap:18px}
  .chac-preview{background:linear-gradient(145deg,#eef2ff,#fce7f3);border-radius:18px;min-height:320px;display:flex;align-items:center;justify-content:center;overflow:hidden}
  .chac-preview canvas{width:100%;max-width:360px;height:auto;touch-action:pan-y}
  .chac-controls{display:grid;grid-template-columns:1fr 1fr;gap:10px;align-content:start}
  .chac-group{background:rgba(127,127,127,.08);padding:10px;border-radius:10px;min-width:0}
  .chac-group label{display:block;font-size:12px;font-weight:700;margin-bottom:5px}
  .chac-group select,.chac-group input[type=color]{width:100%;min-height:36px}
  .chac-actions{display:flex;flex-wrap:wrap;gap:8px;margin-top:14px}
  .chac-btn{border:0;border-radius:9px;padding:10px 14px;cursor:pointer;font-weight:700;background:#6366f1;color:#fff}
  .chac-btn.secondary{background:#64748b}.chac-status{margin-top:10px;font-size:13px}
  @media(max-width:620px){.chac-layout{grid-template-columns:1fr}.chac-preview{min-height:280px}.chac-controls{grid-template-columns:1fr 1fr}}
  </style>
  <h3>Maak je eigen avatar</h3>
  <p>Kies zelf je uiterlijk en sla je avatar op als profielfoto.</p>
  <div class="chac-layout">
    <div>
      <div class="chac-preview"><canvas id="chacCanvas" width="360" height="360" aria-label="Voorbeeld van je avatar"></canvas></div>
      <div class="chac-actions">
        <button class="chac-btn secondary" type="button" id="chacRandom">Verras me</button>
        <button class="chac-btn" type="button" id="chacSave">Avatar opslaan</button>
      </div>
      <div class="chac-status" id="chacStatus" role="status"></div>
    </div>
    <div class="chac-controls">
      <div class="chac-group"><label for="chacSkin">Huidskleur</label><input id="chacSkin" type="color" value="#d99a70"></div>
      <div class="chac-group"><label for="chacHair">Haarkleur</label><input id="chacHair" type="color" value="#3b2419"></div>
      <div class="chac-group"><label for="chacEyes">Oogkleur</label><input id="chacEyes" type="color" value="#3867a8"></div>
      <div class="chac-group"><label for="chacShirt">Shirtkleur</label><input id="chacShirt" type="color" value="#6366f1"></div>
      <div class="chac-group"><label for="chacHairStyle">Kapsel</label><select id="chacHairStyle"><option value="short">Kort</option><option value="long">Lang</option><option value="curly">Krullen</option><option value="bald">Kaal</option></select></div>
      <div class="chac-group"><label for="chacFace">Gezicht</label><select id="chacFace"><option value="smile">Glimlach</option><option value="neutral">Neutraal</option><option value="happy">Blij</option><option value="cool">Stoer</option></select></div>
      <div class="chac-group"><label for="chacBg">Achtergrond</label><input id="chacBg" type="color" value="#e0e7ff"></div>
      <div class="chac-group"><label for="chacAccessory">Accessoire</label><select id="chacAccessory"><option value="none">Geen</option><option value="glasses">Bril</option><option value="cap">Pet</option><option value="earrings">Oorbellen</option></select></div>
    </div>
  </div>
</div>
<script>
(function(){
 const root=document.getElementById('chAvatarCreator'); if(!root||root.dataset.ready)return; root.dataset.ready='1';
 const canvas=root.querySelector('#chacCanvas'),ctx=canvas.getContext('2d');
 const ids=['Skin','Hair','Eyes','Shirt','HairStyle','Face','Bg','Accessory'];
 const get=id=>root.querySelector('#chac'+id).value;
 function draw(){
  const W=360,H=360;ctx.clearRect(0,0,W,H);ctx.fillStyle=get('Bg');ctx.fillRect(0,0,W,H);
  // soft background circles
  ctx.globalAlpha=.12;ctx.fillStyle='#ffffff';ctx.beginPath();ctx.arc(180,145,122,0,Math.PI*2);ctx.fill();ctx.globalAlpha=1;
  // shoulders and torso
  ctx.fillStyle=get('Shirt');ctx.beginPath();ctx.moveTo(70,350);ctx.lineTo(83,270);ctx.quadraticCurveTo(180,220,277,270);ctx.lineTo(290,350);ctx.closePath();ctx.fill();
  // neck
  ctx.fillStyle=get('Skin');ctx.fillRect(153,218,54,55);
  // ears and face
  ctx.beginPath();ctx.ellipse(104,166,18,28,0,0,Math.PI*2);ctx.ellipse(256,166,18,28,0,0,Math.PI*2);ctx.fill();
  ctx.beginPath();ctx.ellipse(180,160,78,94,0,0,Math.PI*2);ctx.fill();
  // hair
  const hs=get('HairStyle');ctx.fillStyle=get('Hair');
  if(hs!=='bald'){ctx.beginPath(); if(hs==='long'){ctx.moveTo(103,169);ctx.lineTo(98,105);ctx.quadraticCurveTo(180,28,262,105);ctx.lineTo(262,244);ctx.lineTo(240,250);ctx.lineTo(235,137);ctx.quadraticCurveTo(180,99,125,137);ctx.lineTo(120,250);ctx.lineTo(98,240);ctx.closePath();}
   else if(hs==='curly'){ctx.ellipse(180,95,81,43,0,0,Math.PI*2);for(let x=113;x<250;x+=23){ctx.beginPath();ctx.arc(x,83+(x%3)*4,18,0,Math.PI*2);ctx.fill();}}
   else {ctx.moveTo(103,150);ctx.lineTo(103,106);ctx.quadraticCurveTo(112,58,180,59);ctx.quadraticCurveTo(253,58,257,111);ctx.lineTo(257,150);ctx.quadraticCurveTo(225,117,203,113);ctx.quadraticCurveTo(158,137,103,150);ctx.closePath();}
   ctx.fill();}
  // eyebrows and eyes
  ctx.strokeStyle=get('Hair');ctx.lineWidth=6;ctx.lineCap='round';ctx.beginPath();ctx.moveTo(132,135);ctx.lineTo(157,132);ctx.moveTo(203,132);ctx.lineTo(228,135);ctx.stroke();
  ctx.fillStyle='#fff';ctx.beginPath();ctx.ellipse(145,151,13,9,0,0,Math.PI*2);ctx.ellipse(215,151,13,9,0,0,Math.PI*2);ctx.fill();
  ctx.fillStyle=get('Eyes');ctx.beginPath();ctx.arc(146,152,6,0,Math.PI*2);ctx.arc(214,152,6,0,Math.PI*2);ctx.fill();
  ctx.fillStyle='#111827';ctx.beginPath();ctx.arc(147,152,2.5,0,Math.PI*2);ctx.arc(215,152,2.5,0,Math.PI*2);ctx.fill();
  // nose
  ctx.strokeStyle='rgba(90,45,30,.45)';ctx.lineWidth=3;ctx.beginPath();ctx.moveTo(180,158);ctx.lineTo(172,184);ctx.quadraticCurveTo(180,190,188,184);ctx.stroke();
  // mouth expression
  ctx.strokeStyle='#783b36';ctx.lineWidth=4;ctx.beginPath();
  if(get('Face')==='neutral'){ctx.moveTo(158,207);ctx.lineTo(202,207);}
  else if(get('Face')==='cool'){ctx.moveTo(158,204);ctx.quadraticCurveTo(180,215,202,204);}
  else {ctx.moveTo(156,201);ctx.quadraticCurveTo(180,get('Face')==='happy'?231:222,204,201);}ctx.stroke();
  if(get('Face')==='happy'){ctx.fillStyle='#fff';ctx.fillRect(164,205,32,8);}
  // accessory
  const ac=get('Accessory');ctx.strokeStyle='#202938';ctx.lineWidth=5;
  if(ac==='glasses'){ctx.beginPath();ctx.roundRect(124,139,42,29,8);ctx.roundRect(194,139,42,29,8);ctx.moveTo(166,150);ctx.lineTo(194,150);ctx.stroke();}
  if(ac==='cap'){ctx.fillStyle=get('Hair');ctx.beginPath();ctx.ellipse(180,91,81,20,0,Math.PI,Math.PI*2);ctx.fill();ctx.fillRect(103,90,155,9);}
  if(ac==='earrings'){ctx.fillStyle='#fbbf24';ctx.beginPath();ctx.arc(103,190,5,0,Math.PI*2);ctx.arc(257,190,5,0,Math.PI*2);ctx.fill();}
  // neck shirt collar
  ctx.fillStyle='#fff';ctx.globalAlpha=.75;ctx.beginPath();ctx.moveTo(153,257);ctx.lineTo(180,276);ctx.lineTo(207,257);ctx.lineTo(198,250);ctx.lineTo(180,263);ctx.lineTo(162,250);ctx.closePath();ctx.fill();ctx.globalAlpha=1;
 }
 ids.forEach(id=>root.querySelector('#chac'+id).addEventListener('input',draw));
 root.querySelector('#chacRandom').addEventListener('click',()=>{root.querySelectorAll('input[type=color]').forEach(el=>el.value='#'+Math.floor(Math.random()*16777215).toString(16).padStart(6,'0'));['HairStyle','Face','Accessory'].forEach(id=>{const el=root.querySelector('#chac'+id);el.selectedIndex=Math.floor(Math.random()*el.options.length)});draw()});
 root.querySelector('#chacSave').addEventListener('click',async()=>{
  const status=root.querySelector('#chacStatus');status.textContent='Avatar opslaan…';
  try{const png=canvas.toDataURL('image/png');const res=await fetch('addons/chathype_avatar_creator/system/save_avatar.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({image:png})});const data=await res.json();if(!res.ok||!data.success)throw new Error(data.error||'Opslaan mislukt');status.textContent='Avatar opgeslagen! Vernieuw de chat als je profielfoto niet direct verandert.';}
  catch(e){status.textContent='Opslaan mislukt: '+e.message;}
 });
 draw();
})();
</script>