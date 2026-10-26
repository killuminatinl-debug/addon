<?php
require(__DIR__ . '/../../../system/config_session.php');
require(__DIR__ . '/../system/settings.php');
if (!chathypeAvatarEnabled() || empty($data['user_id'])) {
    die('');
}
start();
ob_start();
?>
<div class="ch_avatar_modal chac_studio" data-action-path="addons/chathype_avatar_chooser/system/action.php" data-chac-ready="0">
  <header class="chac_topbar">
    <div class="chac_brand"><span class="chac_brandmark"><i class="fa fa-magic"></i></span><span><strong>Chathype Avatar Studio</strong><small>Jouw look. Jouw stijl.</small></span></div>
    <div class="chac_topbar_actions"><span class="chac_live"><i></i> LIVE VOORBEELD</span><button type="button" class="chac_close" id="chacClose" aria-label="Avatarvenster sluiten" title="Scherm sluiten" onclick="if(typeof hideOver==='function'){hideOver()}else{var m=this.closest('.ch_avatar_modal');if(m)m.remove()}"><span class="chac_close_icon" aria-hidden="true">×</span><span class="chac_close_text">Sluiten</span></button></div>
  </header>
  <div class="chac_workspace">
    <aside class="chac_stage">
      <div class="chac_stage_heading"><span>Jouw personage</span><span class="chac_sparkle">✦ ✧</span></div>
      <div class="chac_avatar_stage" id="chacStage">
        <div class="chac_stage_orbit chac_orbit_one"></div><div class="chac_stage_orbit chac_orbit_two"></div>
        <div class="chac_avatar_svg" id="chacAvatarSvg" role="img" aria-label="Live voorbeeld van je avatar"></div>
        <span class="chac_stage_tag"><i class="fa fa-sparkles"></i> MY AVATAR</span>
      </div>
      <div class="chac_stage_caption"><span class="chac_online_dot"></span><span>Verander iets — je ziet het meteen</span></div>
      <div class="chac_stage_buttons">
        <button type="button" class="chac_btn chac_btn_light" id="chacRandom"><i class="fa fa-shuffle"></i> Verrassingslook</button>
        <button type="button" class="chac_btn chac_btn_save" id="chacSave"><i class="fa fa-check"></i><span>Opslaan als profielfoto</span></button>
      </div>
      <div id="chacStatus" class="chac_status" role="status" aria-live="polite"></div>
      <canvas id="chacCanvas" width="360" height="360" aria-hidden="true"></canvas>
    </aside>
    <main class="chac_editor">
      <div class="chac_editor_intro"><div><div class="chac_eyebrow">AVATAR BOUWER</div><h2>Maak 'm helemaal van jou.</h2><p>Mix kleuren, kies je kapsel en stel je eigen Chathype-look samen.</p></div><span class="chac_step">01 <i>/</i> 04</span></div>
      <div class="chac_tabs" role="tablist" aria-label="Avatar onderdelen">
        <button type="button" class="is_active" data-tab="look"><i class="fa fa-user"></i><span>Uiterlijk</span></button>
        <button type="button" data-tab="hair"><i class="fa fa-scissors"></i><span>Haar</span></button>
        <button type="button" data-tab="style"><i class="fa fa-shirt"></i><span>Stijl</span></button>
        <button type="button" data-tab="details"><i class="fa fa-star"></i><span>Details</span></button>
      </div>
      <section class="chac_panel is_active" data-panel="look">
        <input type="hidden" id="chacSkin" value="#d99a70">
        <div class="chac_section_title"><div><h3>Huidskleur</h3><p>Kies de tint die bij je past.</p></div><span class="chac_section_icon">◉</span></div>
        <div class="chac_swatches" data-input="Skin">
          <button type="button" data-color="#ffe0c8" style="--sw:#ffe0c8" aria-label="Licht" title="Licht"></button>
          <button type="button" data-color="#f3c5a0" style="--sw:#f3c5a0" aria-label="Perzik" title="Perzik"></button>
          <button type="button" data-color="#d99a70" style="--sw:#d99a70" aria-label="Warm" title="Warm"></button>
          <button type="button" data-color="#b97850" style="--sw:#b97850" aria-label="Karamel" title="Karamel"></button>
          <button type="button" data-color="#8e563c" style="--sw:#8e563c" aria-label="Bruin" title="Bruin"></button>
          <button type="button" data-color="#623b2b" style="--sw:#623b2b" aria-label="Donker" title="Donker"></button>
        </div>
        <div class="chac_divider"></div>
        <div class="chac_color_grid">
          <label class="chac_color_control"><span><i class="chac_color_dot" style="background:#3b2419"></i>Haarkleur</span><input id="chacHair" type="color" value="#3b2419"></label>
          <label class="chac_color_control"><span><i class="chac_color_dot" style="background:#3867a8"></i>Oogkleur</span><input id="chacEyes" type="color" value="#3867a8"></label>
        </div>
      </section>
      <section class="chac_panel" data-panel="hair">
        <div class="chac_section_title"><div><h3>Vind je kapsel</h3><p>Een goede look begint bovenaan.</p></div><span class="chac_section_icon">✂</span></div>
        <input type="hidden" id="chacHairStyle" value="fade">
        <div class="chac_choice_grid chac_hair_choices" data-target="HairStyle">
          <button type="button" data-value="fade" class="is_active"><span class="chac_choice_art">◒</span><span>Fresh fade</span></button>
          <button type="button" data-value="short"><span class="chac_choice_art">◓</span><span>Kort</span></button>
          <button type="button" data-value="long"><span class="chac_choice_art">♧</span><span>Lang</span></button>
          <button type="button" data-value="curly"><span class="chac_choice_art">〰</span><span>Krullen</span></button>
          <button type="button" data-value="afro"><span class="chac_choice_art">✿</span><span>Afro</span></button>
          <button type="button" data-value="bun"><span class="chac_choice_art">♧</span><span>Knot</span></button>
          <button type="button" data-value="fringe"><span class="chac_choice_art">⌁</span><span>Pony</span></button>
          <button type="button" data-value="bald"><span class="chac_choice_art">○</span><span>Kaal</span></button>
        </div>
        <div class="chac_divider"></div>
        <div class="chac_section_title"><div><h3>Haarkleur</h3><p>Van naturel tot opvallend.</p></div></div>
        <div class="chac_color_chips" data-color-target="Hair">
          <button type="button" data-color="#211b1b" style="--sw:#211b1b" aria-label="Zwart"></button><button type="button" data-color="#3b2419" style="--sw:#3b2419" aria-label="Donkerbruin"></button><button type="button" data-color="#8b4b2b" style="--sw:#8b4b2b" aria-label="Bruin"></button><button type="button" data-color="#d7a84b" style="--sw:#d7a84b" aria-label="Blond"></button><button type="button" data-color="#d7dce5" style="--sw:#d7dce5" aria-label="Zilver"></button><button type="button" data-color="#8b5cf6" style="--sw:#8b5cf6" aria-label="Paars"></button><button type="button" data-color="#ec4899" style="--sw:#ec4899" aria-label="Roze"></button><button type="button" data-color="#06b6d4" style="--sw:#06b6d4" aria-label="Cyaan"></button>
        </div>
      </section>
      <section class="chac_panel" data-panel="style">
        <div class="chac_section_title"><div><h3>Jouw outfit</h3><p>Welke vibe past vandaag bij jou?</p></div><span class="chac_section_icon">✦</span></div>
        <div class="chac_color_grid chac_outfit_colors">
          <label class="chac_color_control"><span><i class="chac_color_dot" style="background:#6366f1"></i>Kledingkleur</span><input id="chacShirt" type="color" value="#6366f1"></label>
          <label class="chac_color_control"><span><i class="chac_color_dot" style="background:#dbeafe"></i>Achtergrond</span><input id="chacBg" type="color" value="#dbeafe"></label>
        </div>
        <div class="chac_divider"></div>
        <input type="hidden" id="chacOutfit" value="hoodie">
        <div class="chac_section_title"><div><h3>Kledingstijl</h3><p>Van comfy tot classy.</p></div></div>
        <div class="chac_choice_grid chac_outfit_choices" data-target="Outfit">
          <button type="button" data-value="hoodie" class="is_active"><span class="chac_outfit_art">♧</span><span>Street hoodie</span></button>
          <button type="button" data-value="jacket"><span class="chac_outfit_art">◈</span><span>Jasje</span></button>
          <button type="button" data-value="tee"><span class="chac_outfit_art">Ｔ</span><span>T-shirt</span></button>
          <button type="button" data-value="smart"><span class="chac_outfit_art">♢</span><span>Classy</span></button>
        </div>
      </section>
      <section class="chac_panel" data-panel="details">
        <div class="chac_section_title"><div><h3>Maak 'm uniek</h3><p>Kleine details, groot verschil.</p></div><span class="chac_section_icon">✧</span></div>
        <input type="hidden" id="chacFace" value="smile">
        <div class="chac_section_title chac_small_title"><div><h3>Gezichtsuitdrukking</h3></div></div>
        <div class="chac_pills chac_options" data-target="Face"><button type="button" data-value="smile" class="is_active">Glimlach</button><button type="button" data-value="neutral">Neutraal</button><button type="button" data-value="happy">Blij</button><button type="button" data-value="cool">Stoer</button></div>
        <input type="hidden" id="chacEyeStyle" value="round">
        <div class="chac_section_title chac_small_title"><div><h3>Ogen</h3></div></div>
        <div class="chac_pills chac_options" data-target="EyeStyle"><button type="button" data-value="round" class="is_active">Open</button><button type="button" data-value="sleepy">Relaxed</button><button type="button" data-value="wink">Knipoog</button></div>
        <input type="hidden" id="chacAccessory" value="none">
        <div class="chac_section_title chac_small_title"><div><h3>Accessoires</h3></div></div>
        <div class="chac_choice_grid chac_accessory_choices chac_options" data-target="Accessory">
          <button type="button" data-value="none" class="is_active"><span class="chac_choice_art">＋</span><span>Geen</span></button>
          <button type="button" data-value="glasses"><span class="chac_choice_art">◉</span><span>Bril</span></button>
          <button type="button" data-value="sunglasses"><span class="chac_choice_art">▰</span><span>Zonnebril</span></button>
          <button type="button" data-value="cap"><span class="chac_choice_art">⌒</span><span>Pet</span></button>
          <button type="button" data-value="earrings"><span class="chac_choice_art">✧</span><span>Oorbellen</span></button>
          <button type="button" data-value="beard"><span class="chac_choice_art">〰</span><span>Baard</span></button>
        </div>
      </section>
      <footer class="chac_editor_footer"><span><i class="fa fa-lock"></i> Alleen jouw avatar wordt aangepast</span><button type="button" id="chacNext" class="chac_next">Volgende stap <i class="fa fa-arrow-right"></i></button></footer>
    </main>
  </div>
</div>
<style>
.ch_avatar_modal.chac_studio{--chac-ink:#202132;--chac-muted:#85869a;--chac-line:#e9eaf2;--chac-purple:#6558e8;--chac-soft:#f5f4ff;display:block!important;width:100%!important;max-width:1200px;margin:0 auto;padding:0!important;max-height:92vh;overflow:auto;background:#f9faff!important;color:var(--chac-ink)!important;border:0!important;border-radius:16px;box-shadow:none!important;font-family:inherit;line-height:1.45}
.ch_avatar_modal.chac_studio *{box-sizing:border-box}
.ch_avatar_modal.chac_studio button,.ch_avatar_modal.chac_studio input{font:inherit}
.chac_topbar{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 23px;background:#fff;border-bottom:1px solid var(--chac-line)}
.chac_topbar_actions{display:flex;align-items:center;gap:10px;flex:0 0 auto}.chac_close{display:flex!important;align-items:center;justify-content:center;width:38px;height:38px;flex:0 0 38px;margin:0!important;padding:0!important;border:1px solid #e5e6ef!important;border-radius:10px!important;background:#fff!important;color:#52536a!important;font-family:Arial,sans-serif!important;font-size:28px!important;font-weight:400!important;line-height:1!important;cursor:pointer;position:relative;z-index:5}.chac_close span{display:block;line-height:1;transform:translateY(-1px);pointer-events:none}.chac_close:hover{background:#f3f1ff!important;color:#6558e8!important}.chac_brand{display:flex;align-items:center;gap:11px;min-width:0}.chac_brandmark{display:flex;align-items:center;justify-content:center;flex:0 0 42px;width:42px;height:42px;border-radius:14px;background:#fffc00;color:#171923;font-size:19px}
.chac_brand strong,.chac_brand small{display:block}.chac_brand strong{font-size:15px;font-weight:900;letter-spacing:-.25px}.chac_brand small{margin-top:2px;font-size:11px;color:var(--chac-muted)}
.chac_live{display:flex;align-items:center;gap:7px;flex:0 0 auto;padding:7px 10px;border:1px solid #e6e6f0;border-radius:30px;color:#73748a;font-size:9px;font-weight:900;letter-spacing:.8px}.chac_live i,.chac_online_dot{display:inline-block;width:7px;height:7px;border-radius:50%;background:#22c58b;box-shadow:0 0 0 3px #e3fbf1}
.chac_workspace{display:grid;grid-template-columns:minmax(265px,.82fr) minmax(0,1.18fr);align-items:stretch;gap:0}
.chac_stage{position:sticky;top:0;align-self:start;padding:23px;background:#f0efff;border-right:1px solid #e6e5f6;min-width:0}
.chac_stage_heading{display:flex;align-items:center;justify-content:space-between;margin:0 0 14px;color:#424258;font-size:12px;font-weight:850}.chac_sparkle{color:#8c80ed;font-size:18px;letter-spacing:3px}
.chac_avatar_stage{position:relative;display:flex;align-items:center;justify-content:center;isolation:isolate;overflow:hidden;width:100%;aspect-ratio:1/1;min-height:240px;border:1px solid rgba(255,255,255,.85);border-radius:22px;background:linear-gradient(145deg,#fffcdf,#e6e3ff 75%);box-shadow:0 10px 28px rgba(64,54,130,.08)}
.chac_avatar_stage:before{content:"";position:absolute;z-index:-1;width:76%;aspect-ratio:1;border-radius:50%;background:rgba(255,255,255,.7);box-shadow:0 0 0 16px rgba(255,255,255,.2)}
.chac_stage_orbit{position:absolute;z-index:-1;border:1px solid rgba(113,101,222,.16);border-radius:50%;pointer-events:none}.chac_orbit_one{width:88%;aspect-ratio:1}.chac_orbit_two{width:108%;aspect-ratio:1}
.chac_avatar_svg{position:relative;display:flex;align-items:center;justify-content:center;width:100%;max-width:390px;z-index:1;line-height:0;pointer-events:none}.chac_avatar_svg svg{display:block;width:100%;height:auto;max-height:100%;overflow:visible}.chac_avatar_svg svg{display:block;width:100%;height:auto;overflow:visible;filter:drop-shadow(0 12px 9px rgba(48,43,86,.12))}
.chac_stage_tag{position:absolute;z-index:3;left:12px;bottom:12px;pointer-events:none;display:flex;align-items:center;gap:6px;padding:7px 9px;border:1px solid rgba(255,255,255,.8);border-radius:9px;background:rgba(255,255,255,.8);color:#6259a8;font-size:9px;font-weight:900;letter-spacing:.7px;backdrop-filter:blur(8px)}
.chac_stage_caption{display:flex;align-items:center;justify-content:center;gap:9px;margin:13px 0 16px;color:#77778e;font-size:11px;text-align:center}.chac_online_dot{width:6px;height:6px;box-shadow:none;flex:0 0 6px}
.chac_stage_buttons{display:grid;grid-template-columns:minmax(0,.92fr) minmax(0,1.3fr);gap:8px}
.chac_btn{display:flex!important;align-items:center;justify-content:center;gap:7px;min-width:0;min-height:44px;margin:0!important;padding:10px 11px!important;border:1px solid transparent!important;border-radius:12px!important;font-size:11px!important;font-weight:850!important;line-height:1.3;white-space:normal;cursor:pointer;transition:transform .16s,box-shadow .16s,background .16s}
.chac_btn:hover{transform:translateY(-1px)}.chac_btn:disabled{opacity:.65;cursor:wait;transform:none}
.chac_btn_light{background:#fff!important;color:#46465c!important;border-color:#e4e3f4!important}.chac_btn_save{background:#6558e8!important;color:#fff!important;box-shadow:0 6px 14px rgba(101,88,232,.22)}
.chac_status{min-height:18px;margin:10px 1px 0;color:#77778e;font-size:11px;overflow-wrap:anywhere}.chac_status.error{color:#c62845}.chac_status.ok{color:#16834a}
#chacCanvas{position:absolute!important;width:1px!important;height:1px!important;opacity:0!important;pointer-events:none!important;left:-10000px!important;top:-10000px!important}
.chac_editor{min-width:0;padding:22px 24px 16px;background:#fff}
.chac_editor_intro{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:18px}.chac_eyebrow{margin-bottom:5px;color:var(--chac-purple);font-size:9px;font-weight:950;letter-spacing:1.7px}
.chac_editor_intro h2{margin:0;color:#242438;font-size:clamp(19px,2vw,25px);font-weight:950;letter-spacing:-.8px;line-height:1.2}.chac_editor_intro p{margin:7px 0 0;color:#85869a;font-size:11px;line-height:1.5}
.chac_step{flex:0 0 auto;padding:6px 9px;border-radius:8px;background:#f4f3ff;color:#6558e8;font-size:11px;font-weight:900}.chac_step i{padding:0 3px;color:#b5b1e8;font-style:normal}
.chac_tabs{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:5px;margin-bottom:19px;padding:5px;border-radius:13px;background:#f5f5fa}
.chac_tabs button{display:flex;align-items:center;justify-content:center;gap:7px;min-width:0;min-height:38px;padding:8px 5px;border:0;border-radius:9px;background:transparent;color:#77788d;font-size:11px;font-weight:800;cursor:pointer}
.chac_tabs button i{font-size:12px}.chac_tabs button.is_active{background:#fff;color:#6255df;box-shadow:0 2px 8px rgba(30,30,60,.08)}
.chac_panel{display:none;min-width:0;animation:chacFadeIn .18s ease-out}.chac_panel.is_active{display:block}
@keyframes chacFadeIn{from{opacity:.5;transform:translateY(3px)}to{opacity:1;transform:translateY(0)}}
.chac_section_title{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:0 0 14px}.chac_section_title h3{margin:0;color:#303047;font-size:13px;font-weight:900;letter-spacing:-.15px}.chac_section_title p{margin:4px 0 0;color:#9293a5;font-size:10px}
.chac_section_icon{display:flex;align-items:center;justify-content:center;width:31px;height:31px;flex:0 0 31px;border-radius:10px;background:#f1efff;color:#7568e8;font-size:16px}
.chac_swatches{display:flex;align-items:center;gap:13px;flex-wrap:wrap;padding:3px 2px 5px}
.chac_swatches button,.chac_color_chips button{display:block;width:32px;height:32px;flex:0 0 32px;margin:0;padding:0;border:3px solid #fff;border-radius:50%;background:var(--sw);box-shadow:0 0 0 1px #dedfea;cursor:pointer;transition:transform .15s,box-shadow .15s}
.chac_swatches button:hover,.chac_color_chips button:hover{transform:scale(1.08)}.chac_swatches button.is_active,.chac_color_chips button.is_active{box-shadow:0 0 0 3px #6558e8;transform:scale(1.04)}
.chac_divider{height:1px;margin:19px 0;border:0;background:#eeeeF5}
.chac_color_grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:9px}
.chac_color_control{display:flex;align-items:center;justify-content:space-between;gap:8px;min-width:0;min-height:52px;padding:10px 11px;border:1px solid #e9eaf2;border-radius:12px;background:#fff}
.chac_color_control>span{display:flex;align-items:center;gap:7px;min-width:0;color:#55566d;font-size:10px;font-weight:800}
.chac_color_dot{display:inline-block;width:12px;height:12px;flex:0 0 12px;border-radius:50%;box-shadow:0 0 0 1px rgba(0,0,0,.08)}
.chac_color_control input[type=color]{display:block;width:29px;height:29px;flex:0 0 29px;margin:0;padding:2px;border:1px solid #e5e6ee;border-radius:8px;background:#fff;cursor:pointer}
.chac_color_chips{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.chac_color_chips button{width:27px;height:27px;flex-basis:27px}
.chac_choice_grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:8px}
.chac_choice_grid button{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;min-width:0;min-height:82px;margin:0;padding:10px 4px;border:1px solid #e8e9f1;border-radius:12px;background:#fff;color:#65667c;font-size:10px;font-weight:800;line-height:1.2;cursor:pointer;transition:border-color .15s,background .15s,transform .15s,box-shadow .15s}
.chac_choice_grid button:hover{transform:translateY(-1px);border-color:#c8c3fb}.chac_choice_grid button.is_active{border-color:#8378ed;background:#f5f3ff;color:#5e52d8;box-shadow:inset 0 0 0 1px #d9d5ff}
.chac_choice_art,.chac_outfit_art{display:flex;align-items:center;justify-content:center;width:35px;height:35px;border-radius:11px;background:#f1f1f8;color:#696b83;font-size:20px;font-weight:700}
.chac_choice_grid button.is_active .chac_choice_art,.chac_choice_grid button.is_active .chac_outfit_art{background:#e7e4ff;color:#5e52d8}
.chac_outfit_choices button{min-height:90px}.chac_outfit_art{width:40px;height:40px;font-size:22px}
.chac_pills{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:5px}
.chac_pills button{min-height:34px;margin:0;padding:8px 12px;border:1px solid #e6e7ef;border-radius:10px;background:#fff;color:#66677d;font-size:10px;font-weight:800;cursor:pointer}
.chac_pills button.is_active{border-color:#6558e8;background:#f3f1ff;color:#5e52d8}
.chac_small_title{margin-top:19px;margin-bottom:10px}.chac_small_title h3{font-size:12px}
.chac_accessory_choices{grid-template-columns:repeat(3,minmax(0,1fr))}.chac_accessory_choices button{min-height:72px}
.chac_editor_footer{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:22px;padding-top:15px;border-top:1px solid #ececf3}
.chac_editor_footer>span{display:flex;align-items:center;gap:6px;color:#9596a8;font-size:9px}.chac_editor_footer>span i{color:#7770da}
.chac_next{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:37px;padding:9px 13px;border:0;border-radius:10px;background:#f0efff;color:#6255df;font-size:10px;font-weight:900;cursor:pointer}
.chac_studio button:focus-visible{outline:3px solid #9188ff;outline-offset:2px}
@media(max-width:850px){.chac_workspace{grid-template-columns:minmax(220px,.7fr) minmax(0,1.3fr)}.chac_stage{padding:15px}.chac_editor{padding:18px}.chac_stage_buttons{grid-template-columns:1fr}.chac_btn{min-height:40px}}
@media(max-width:650px){.ch_avatar_modal.chac_studio{max-height:93vh;border-radius:17px}.chac_topbar{padding:13px 15px}.chac_brandmark{width:37px;height:37px;flex-basis:37px}.chac_brand strong{font-size:13px}.chac_live{font-size:8px;padding:6px 7px}.chac_workspace{grid-template-columns:minmax(0,1fr)}.chac_stage{position:relative;top:auto;display:grid;grid-template-columns:minmax(0,1fr);padding:14px 16px 12px;border-right:0;border-bottom:1px solid #e6e5f6}.chac_stage_heading{margin-bottom:9px}.chac_avatar_stage{width:min(100%,320px);min-height:0;margin:0 auto;border-radius:18px}.chac_stage_caption{margin:9px 0 11px}.chac_stage_buttons{grid-template-columns:minmax(0,.9fr) minmax(0,1.2fr);max-width:420px;width:100%;margin:0 auto}.chac_editor{padding:17px 16px 14px}.chac_editor_intro{margin-bottom:14px}.chac_editor_intro h2{font-size:21px}.chac_tabs{margin-bottom:15px}.chac_choice_grid{gap:7px}}
@media(max-width:390px){.chac_live{display:none}.chac_editor{padding:14px 11px}.chac_stage{padding:12px}.chac_choice_grid{grid-template-columns:repeat(2,minmax(0,1fr))}.chac_accessory_choices{grid-template-columns:repeat(3,minmax(0,1fr))}.chac_stage_buttons{grid-template-columns:1fr}.chac_color_grid{gap:6px}.chac_color_control{padding:8px}.chac_tabs button{gap:4px;font-size:10px}.chac_tabs button i{font-size:11px}}
@media(prefers-reduced-motion:reduce){.ch_avatar_modal.chac_studio *{animation:none!important;transition:none!important}}


.chac_topbar{position:relative;padding-right:62px!important}.chac_close{position:absolute!important;top:12px!important;right:12px!important;z-index:999999!important;display:flex!important;align-items:center!important;justify-content:center!important;width:38px!important;height:38px!important;min-width:38px!important;min-height:38px!important;margin:0!important;padding:0!important;border:1px solid #d9dbea!important;border-radius:10px!important;background:#fff!important;color:#303047!important;font-family:Arial,sans-serif!important;font-size:29px!important;font-weight:400!important;line-height:1!important;opacity:1!important;visibility:visible!important;cursor:pointer!important;box-shadow:0 2px 8px rgba(20,20,50,.12)!important}.chac_close span{display:block!important;line-height:1!important;transform:translateY(-1px);pointer-events:none!important}.chac_topbar_actions{padding-right:38px!important}
.chac_topbar_actions{display:flex!important;align-items:center!important;justify-content:flex-end!important;gap:10px!important;flex:0 0 auto!important;position:relative!important;z-index:1000000!important}.chac_close{position:static!important;inset:auto!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;gap:6px!important;width:auto!important;min-width:38px!important;height:38px!important;min-height:38px!important;flex:0 0 auto!important;margin:0!important;padding:0 10px!important;border:1px solid #d9dbea!important;border-radius:10px!important;background:#fff!important;color:#303047!important;font-family:Arial,sans-serif!important;font-size:13px!important;font-weight:700!important;line-height:1!important;opacity:1!important;visibility:visible!important;cursor:pointer!important;box-shadow:0 2px 8px rgba(20,20,50,.12)!important}.chac_close_icon{display:block!important;font-size:25px!important;font-weight:400!important;line-height:1!important;pointer-events:none!important}.chac_close_text{display:inline!important;font-size:12px!important;white-space:nowrap!important;pointer-events:none!important}@media(max-width:420px){.chac_topbar_actions{gap:6px!important}.chac_live{font-size:7px!important;padding:5px!important}.chac_close{padding:0 8px!important}}
</style>
<script data-cfasync="false">
(function($){
  var root=document.querySelector('.chac_studio');
  if(!root||root.getAttribute('data-chac-ready')==='1')return;
  root.setAttribute('data-chac-ready','1');
  /*
   * Behoud de originele CodyChat-modal. Pas alleen de breedte aan.
   */
  /*
   * Toon alleen het avatarvenster: netjes gecentreerd, zonder donkere
   * achtergrondlaag of extra schaduw. Verander geen andere modal-elementen.
   */
  root.style.setProperty('position','fixed','important');
  root.style.setProperty('left','50%','important');
  root.style.setProperty('top','50%','important');
  root.style.setProperty('right','auto','important');
  root.style.setProperty('bottom','auto','important');
  root.style.setProperty('transform','translate(-50%, -50%)','important');
  root.style.setProperty('width','calc(100vw - 32px)','important');
  root.style.setProperty('max-width','1200px','important');
  root.style.setProperty('max-height','calc(100vh - 32px)','important');
  root.style.setProperty('overflow-y','auto','important');
  root.style.setProperty('margin','0','important');
  root.style.setProperty('background','#f9faff','important');
  root.style.setProperty('box-shadow','0 8px 30px rgba(24,24,50,.12)','important');
  root.style.setProperty('border','1px solid #e8e9f1','important');
  root.style.setProperty('z-index','100001','important');
  if(root.parentElement){
    var host=root.parentElement;
    if(host.id==='over' || /over|modal|popup/i.test(String(host.id)+' '+String(host.className))){
      host.style.setProperty('background','transparent','important');
      host.style.setProperty('box-shadow','none','important');
    }
  }
  var closeButton=root.querySelector('#chacClose');\n  if(closeButton){closeButton.addEventListener('click',function(){if(typeof hideOver==='function'){hideOver()}else if(root.parentNode){root.parentNode.removeChild(root)}})}\n  document.addEventListener('keydown',function(event){
    if(event.key==='Escape' && document.body.contains(root) && typeof hideOver==='function'){
      hideOver();
    }
  });
  var canvas=root.querySelector('#chacCanvas'),ctx=canvas.getContext('2d'),svgHost=root.querySelector('#chacAvatarSvg');
  function q(id){return root.querySelector('#chac'+id)}
  function val(id){return q(id).value}
  function esc(s){return String(s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}
  function drawSVG(){
    var skin=val('Skin'),hair=val('Hair'),eyes=val('Eyes'),shirt=val('Shirt'),bg=val('Bg'),hs=val('HairStyle'),face=val('Face'),acc=val('Accessory'),outfit=val('Outfit'),eyeStyle=val('EyeStyle');
    var hairBack='',hairFront='',eyesSvg='',mouth='',clothes='',accessories='';
    if(hs==='long')hairBack='<path d="M91 151Q73 66 128 43Q180 12 232 43Q287 66 269 151L258 244Q249 265 226 248L231 125Q180 91 129 125L134 248Q111 265 102 244Z" fill="'+hair+'"/>';
    else if(hs==='afro')hairBack='<path d="M100 133Q70 107 93 78Q91 48 121 43Q139 13 164 34Q186 8 206 31Q237 18 247 48Q279 55 267 88Q286 111 260 137L245 105Q180 76 115 105Z" fill="'+hair+'"/>';
    else if(hs==='curly')hairBack='<path d="M103 137Q80 94 103 64Q117 31 146 47Q168 19 189 40Q218 25 235 52Q269 48 264 87Q280 111 256 139L239 110Q180 80 121 110Z" fill="'+hair+'"/>';
    else if(hs==='bun')hairBack='<path d="M111 119Q91 65 137 47Q178 25 221 48Q261 67 249 119L233 136Q180 89 127 136Z" fill="'+hair+'"/><circle cx="220" cy="40" r="25" fill="'+hair+'"/>';
    else if(hs==='short'||hs==='fade')hairBack='<path d="M103 147L96 102Q102 40 180 39Q258 40 264 102L257 147Q235 123 204 104Q158 126 103 147Z" fill="'+hair+'"/>';
    else if(hs==='fringe')hairBack='<path d="M103 149L99 102Q104 40 180 39Q256 40 261 102L257 145Q228 137 220 104Q202 135 182 113Q158 145 141 116Q125 143 103 149Z" fill="'+hair+'"/>';
    if(hs==='long')hairFront='<path d="M111 111Q124 53 180 54Q236 53 249 111Q211 91 180 100Q149 91 111 111Z" fill="'+hair+'"/>';
    if(hs==='afro')hairFront='<path d="M104 115Q106 62 180 59Q254 62 256 115Q226 91 202 96Q180 81 158 96Q132 91 104 115Z" fill="'+hair+'"/>';
    if(hs==='curly')hairFront='<path d="M107 115Q111 62 180 61Q249 62 253 115Q225 94 204 96Q181 81 159 96Q133 93 107 115Z" fill="'+hair+'"/>';
    if(hs==='bun')hairFront='<path d="M108 115Q115 64 180 62Q245 64 252 115Q218 90 180 99Q142 90 108 115Z" fill="'+hair+'"/>';
    if(hs==='short'||hs==='fade')hairFront='<path d="M104 125Q108 57 180 53Q252 57 256 125Q225 101 204 94Q158 117 104 125Z" fill="'+hair+'"/>';
    if(hs==='fringe')hairFront='<path d="M104 126Q108 57 180 53Q252 57 256 125Q227 127 219 94Q200 129 181 104Q157 137 140 104Q126 127 104 126Z" fill="'+hair+'"/>';
    if(hs==='fade')hairFront+='<path d="M109 104Q118 66 145 61L132 94Z" fill="#fff" opacity=".17"/>';
    if(eyeStyle==='wink')eyesSvg='<path d="M126 151Q143 164 160 151" fill="none" stroke="'+hair+'" stroke-width="5" stroke-linecap="round"/><ellipse cx="215" cy="151" rx="14" ry="12" fill="#fff"/><ellipse cx="215" cy="153" rx="7" ry="8" fill="'+eyes+'"/><circle cx="215" cy="154" r="3.5" fill="#172033"/><circle cx="212" cy="149" r="2.3" fill="#fff"/>';
    else if(eyeStyle==='sleepy')eyesSvg='<path d="M128 150Q143 144 159 151M201 151Q217 144 232 150" fill="none" stroke="'+hair+'" stroke-width="5" stroke-linecap="round"/><path d="M130 151Q144 157 158 151M202 151Q216 157 230 151" fill="none" stroke="'+skin+'" stroke-width="3"/>';
    else eyesSvg='<ellipse cx="144" cy="151" rx="14" ry="12" fill="#fff"/><ellipse cx="216" cy="151" rx="14" ry="12" fill="#fff"/><ellipse cx="145" cy="153" rx="7" ry="8" fill="'+eyes+'"/><ellipse cx="215" cy="153" rx="7" ry="8" fill="'+eyes+'"/><circle cx="145" cy="154" r="3.5" fill="#172033"/><circle cx="215" cy="154" r="3.5" fill="#172033"/><circle cx="142" cy="149" r="2.3" fill="#fff"/><circle cx="212" cy="149" r="2.3" fill="#fff"/>';
    if(face==='neutral')mouth='<path d="M161 205L199 205" fill="none" stroke="#873f45" stroke-width="4" stroke-linecap="round"/>';
    else if(face==='cool')mouth='<path d="M158 201Q180 211 202 199" fill="none" stroke="#873f45" stroke-width="4" stroke-linecap="round"/>';
    else if(face==='happy')mouth='<path d="M155 199Q180 235 205 199Z" fill="#873f45"/><path d="M164 205Q180 211 196 205L192 214Q180 220 168 214Z" fill="#fff"/>';
    else mouth='<path d="M158 200Q180 222 202 200" fill="none" stroke="#873f45" stroke-width="4" stroke-linecap="round"/>';
    if(outfit==='hoodie')clothes='<path d="M125 264Q145 251 160 250L200 250Q215 251 235 264Q289 274 309 315L327 360H33L51 315Q71 274 125 264Z" fill="'+shirt+'"/><path d="M137 258Q180 316 223 258L208 250H152Z" fill="#111827" opacity=".18"/><path d="M164 291L162 324M196 291L198 324" stroke="#fff" stroke-width="3" stroke-linecap="round" opacity=".75"/>';
    else if(outfit==='jacket')clothes='<path d="M125 264Q145 251 160 250L200 250Q215 251 235 264Q289 274 309 315L327 360H33L51 315Q71 274 125 264Z" fill="'+shirt+'"/><path d="M157 252L180 291L203 252L218 360H142Z" fill="#f8fafc"/><path d="M180 291V360" stroke="#b7bbc9" stroke-width="2"/>';
    else if(outfit==='smart')clothes='<path d="M125 264Q145 251 160 250L200 250Q215 251 235 264Q289 274 309 315L327 360H33L51 315Q71 274 125 264Z" fill="'+shirt+'"/><path d="M143 254L180 290L217 254L207 360H153Z" fill="#f8fafc"/><path d="M180 290L191 310L180 346L169 310Z" fill="#ec4899"/>';
    else clothes='<path d="M125 264Q145 251 160 250L200 250Q215 251 235 264Q289 274 309 315L327 360H33L51 315Q71 274 125 264Z" fill="'+shirt+'"/><path d="M160 252L180 282L200 252" fill="none" stroke="#fff" stroke-width="3" opacity=".7"/>';
    if(acc==='beard')accessories+='<path d="M126 185Q131 232 180 243Q229 232 234 185Q220 211 201 211Q180 223 159 211Q140 209 126 185Z" fill="'+hair+'" opacity=".94"/>';
    if(acc==='glasses')accessories+='<g fill="#dbeafe" fill-opacity=".25" stroke="#293449" stroke-width="4"><rect x="121" y="137" width="46" height="31" rx="9"/><rect x="193" y="137" width="46" height="31" rx="9"/><path d="M167 147H193"/></g>';
    if(acc==='sunglasses')accessories+='<g fill="#111827" stroke="#111827" stroke-width="4"><rect x="121" y="137" width="46" height="31" rx="9"/><rect x="193" y="137" width="46" height="31" rx="9"/><path d="M167 147H193"/></g><path d="M128 143L142 143M200 143L214 143" stroke="#fff" stroke-width="2" opacity=".4"/>';
    if(acc==='cap')accessories+='<path d="M104 91Q110 51 180 48Q250 51 256 91Q219 73 180 75Q141 73 104 91Z" fill="'+hair+'"/><path d="M103 91Q157 75 245 92Q263 96 277 103Q213 111 145 99Z" fill="'+hair+'"/><circle cx="180" cy="61" r="4" fill="#fff" opacity=".8"/>';
    if(acc==='earrings')accessories+='<g fill="#fbbf24" stroke="#fff" stroke-width="2"><circle cx="103" cy="190" r="6"/><circle cx="257" cy="190" r="6"/></g>';
    var svg='<svg xmlns="http://www.w3.org/2000/svg" width="360" height="360" viewBox="0 0 360 360"><defs><linearGradient id="skinGlow" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fff" stop-opacity=".16"/><stop offset="1" stop-color="#8b4c35" stop-opacity=".1"/></linearGradient><linearGradient id="backdrop" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="'+esc(bg)+'"/><stop offset="1" stop-color="#c4b5fd"/></linearGradient></defs><rect width="360" height="360" rx="22" fill="url(#backdrop)"/><circle cx="58" cy="59" r="14" fill="#fff" opacity=".25"/><circle cx="304" cy="80" r="21" fill="#fff" opacity=".22"/><circle cx="300" cy="282" r="10" fill="#fff" opacity=".28"/><circle cx="58" cy="284" r="18" fill="#fff" opacity=".2"/>'+clothes+'<path d="M150 220H210L215 274Q180 294 145 274Z" fill="'+skin+'"/><ellipse cx="103" cy="163" rx="18" ry="29" fill="'+skin+'"/><ellipse cx="257" cy="163" rx="18" ry="29" fill="'+skin+'"/>'+hairBack+'<path d="M180 65C230 65 259 101 256 159C253 211 222 246 180 248C138 246 107 211 104 159C101 101 130 65 180 65Z" fill="'+skin+'"/><path d="M180 65C230 65 259 101 256 159C253 211 222 246 180 248C138 246 107 211 104 159C101 101 130 65 180 65Z" fill="url(#skinGlow)"/><ellipse cx="132" cy="187" rx="17" ry="8" fill="#ef4444" opacity=".12"/><ellipse cx="228" cy="187" rx="17" ry="8" fill="#ef4444" opacity=".12"/>'+hairFront+'<path d="M128 126Q143 118 159 126M201 126Q217 118 232 126" fill="none" stroke="'+hair+'" stroke-width="6" stroke-linecap="round"/>'+eyesSvg+'<path d="M180 157L173 181Q180 187 188 181" fill="none" stroke="#8d5a46" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><ellipse cx="180" cy="199" rx="25" ry="15" fill="#ef4444" opacity=".09"/>'+mouth+accessories+'<path d="M131 115Q140 99 145 100" fill="none" stroke="#fff" stroke-width="5" stroke-linecap="round" opacity=".17"/></svg>';
    svgHost.innerHTML=svg;
    return svg;
  }
  function renderCanvas(callback){
    var svg=drawSVG(),blob=new Blob([svg],{type:'image/svg+xml;charset=utf-8'}),url=URL.createObjectURL(blob),img=new Image();
    img.onload=function(){try{ctx.clearRect(0,0,360,360);ctx.drawImage(img,0,0,360,360);URL.revokeObjectURL(url);if(callback)callback(null)}catch(e){URL.revokeObjectURL(url);if(callback)callback(e)}};
    img.onerror=function(){URL.revokeObjectURL(url);if(callback)callback(new Error('Avatar kon niet worden gerenderd'))};
    img.src=url;
  }
  function redraw(){drawSVG()}
  function setActive(group,button){group.querySelectorAll('button').forEach(function(b){b.classList.toggle('is_active',b===button)})}
  root.querySelectorAll('.chac_tabs button').forEach(function(button){
    button.addEventListener('click',function(){
      var tab=button.getAttribute('data-tab');root.querySelectorAll('.chac_tabs button').forEach(function(b){b.classList.toggle('is_active',b===button)});
      root.querySelectorAll('.chac_panel').forEach(function(p){p.classList.toggle('is_active',p.getAttribute('data-panel')===tab)});
      var idx=['look','hair','style','details'].indexOf(tab)+1;root.querySelector('.chac_step').innerHTML=('0'+idx).slice(-2)+' <i>/</i> 04';
      var next=root.querySelector('#chacNext');next.innerHTML=(idx===4?'Terug naar uiterlijk':'Volgende stap')+' <i class="fa fa-arrow-right"></i>';
    });
  });
  root.querySelectorAll('.chac_swatches').forEach(function(group){group.addEventListener('click',function(e){var b=e.target.closest('button[data-color]');if(!b)return;q(group.getAttribute('data-input')).value=b.getAttribute('data-color');setActive(group,b);redraw()})});
  root.querySelectorAll('.chac_color_chips').forEach(function(group){group.addEventListener('click',function(e){var b=e.target.closest('button[data-color]');if(!b)return;q(group.getAttribute('data-color-target')).value=b.getAttribute('data-color');setActive(group,b);redraw()})});
  root.querySelectorAll('input[type=color]').forEach(function(input){input.addEventListener('input',function(){var dot=input.closest('.chac_color_control').querySelector('.chac_color_dot');if(dot)dot.style.background=input.value;redraw()})});
  root.querySelectorAll('.chac_options,.chac_choice_grid').forEach(function(group){group.addEventListener('click',function(e){var b=e.target.closest('button[data-value]');if(!b)return;var target=q(group.getAttribute('data-target'));if(target)target.value=b.getAttribute('data-value');setActive(group,b);redraw()})});
  root.querySelector('#chacNext').addEventListener('click',function(){var tabs=Array.prototype.slice.call(root.querySelectorAll('.chac_tabs button')),idx=tabs.findIndex(function(b){return b.classList.contains('is_active')});tabs[(idx+1)%tabs.length].click();if(window.matchMedia('(max-width:650px)').matches)root.querySelector('.chac_editor').scrollIntoView({behavior:'smooth',block:'start'})});
  root.querySelector('#chacRandom').addEventListener('click',function(){
    var colors={Skin:['#ffe0c8','#f3c5a0','#d99a70','#b97850','#8e563c','#623b2b'],Hair:['#211b1b','#3b2419','#8b4b2b','#d7a84b','#8b5cf6','#ec4899','#06b6d4'],Eyes:['#3867a8','#4f8a5b','#654321','#202132'],Shirt:['#6366f1','#ec4899','#06b6d4','#16a34a','#f97316','#202132'],Bg:['#dbeafe','#fce7f3','#dcfce7','#ffedd5','#ede9fe']};
    Object.keys(colors).forEach(function(id){q(id).value=colors[id][Math.floor(Math.random()*colors[id].length)]});
    ['HairStyle','Face','Accessory','Outfit','EyeStyle'].forEach(function(id){var el=q(id),group=root.querySelector('[data-target="'+id+'"]'),opts=group.querySelectorAll('button[data-value]'),pick=opts[Math.floor(Math.random()*opts.length)];el.value=pick.getAttribute('data-value');setActive(group,pick)});
    root.querySelectorAll('.chac_color_control').forEach(function(label){var inp=label.querySelector('input[type=color]'),dot=label.querySelector('.chac_color_dot');if(inp&&dot)dot.style.background=inp.value});
    root.querySelectorAll('.chac_swatches button,.chac_color_chips button').forEach(function(b){var id=b.closest('.chac_swatches')?b.closest('.chac_swatches').getAttribute('data-input'):b.closest('.chac_color_chips').getAttribute('data-color-target');b.classList.toggle('is_active',b.getAttribute('data-color')===q(id).value)});
    redraw();
  });
  root.querySelector('#chacSave').addEventListener('click',function(){
    var button=this,status=q('Status');button.disabled=true;status.className='chac_status';status.textContent='Je avatar wordt klaargemaakt…';
    renderCanvas(function(err){
      if(err){button.disabled=false;status.className='chac_status error';status.textContent='De avatar kon niet worden klaargemaakt. Probeer het opnieuw.';return}
      status.textContent='Avatar veilig opslaan…';
      $.ajax({url:(function(){var path=root.getAttribute('data-action-path');var current=window.location.pathname;var base=current.substring(0,current.lastIndexOf('/')+1);var controlMarker=current.indexOf('/control/');if(controlMarker>=0){base=current.substring(0,controlMarker+1)}else{var addonMarker=current.indexOf('/addons/');if(addonMarker>=0){base=current.substring(0,addonMarker+1)}}return window.location.origin+base+path})(),method:'POST',data:{save_custom_avatar:1,image:canvas.toDataURL('image/png')},cache:false,dataType:'text'})
      .done(function(response){if(String(response).trim()==='5'){status.className='chac_status ok';status.textContent='Gelukt! Je nieuwe avatar is opgeslagen.';if(typeof callSuccess==='function'&&typeof system!=='undefined')callSuccess(system.saved||'Opgeslagen');setTimeout(function(){if(typeof hideOver==='function')hideOver();window.location.reload()},650)}else{status.className='chac_status error';var msg=String(response).trim();status.textContent=msg.indexOf('AVATAR_ERROR:')===0?'Opslaan mislukt: '+msg.replace('AVATAR_ERROR:','').replace(/_/g,' ').toLowerCase()+'.':'Opslaan mislukt. Serverantwoord: '+msg.slice(0,140)}})
      .fail(function(xhr){status.className='chac_status error';var detail=String(xhr.responseText||'').trim();status.textContent='Opslaan mislukt (HTTP '+xhr.status+'). '+(detail?detail.slice(0,130):'Controleer de PHP-errorlog en schrijfpermissies van de avatar-map.')})
      .always(function(){button.disabled=false});
    });
  });
  redraw();
})(jQuery);
</script>
<?php echo ob_get_clean(); ?>