<?php
if (isset($addons) && is_array($addons) && (!function_exists('boomAllow') || boomAllow(isset($addons['addons_access']) ? $addons['addons_access'] : 100))) {
    $afbSlug = basename((string)$addons['addons']);
?>
<script data-cfasync="false">
(function($){
  window.avatar_frame_box = function(){
    $.post('addons/<?php echo rawurlencode($afbSlug); ?>/system/action.php',{get_box:1},function(html){showModal(html,480);}).fail(function(){if(window.callError)callError(system.error);});
  };
  window.update_frame = function(){
    $.post('addons/<?php echo rawurlencode($afbSlug); ?>/system/action.php',{get_user_frames_list:1,token:utk},function(rows){
      if(typeof rows==='string'){try{rows=JSON.parse(rows);}catch(e){rows=[];}}
      if(!Array.isArray(rows))rows=[];
      window.afbFrameRows=rows;
      if(window.update_frame_userlist)window.update_frame_userlist();
      if(window.update_frame_chat)window.update_frame_chat();
    },'json');
  };
  window.update_frame_userlist = function(){
    $('.user_item_avatar').each(function(){
      var $a=$(this), id=String($a.parent().attr('data-id')||'');
      $a.children('.frame_class_userlist').remove();
      (window.afbFrameRows||[]).filter(function(x){return String(x.user_id)===id;}).forEach(function(x){
        $('<img>',{class:'frame_class_userlist',alt:'',src:'addons/<?php echo rawurlencode($afbSlug); ?>/files/frames/'+encodeURIComponent(x.tumb)}).appendTo($a);
      });
    });
  };
  window.update_frame_chat = function(){
    $('.chat_avatar').each(function(){
      var $a=$(this), id=String($a.attr('data-id')||'');
      $a.children('.frame_class_chat').remove();
      (window.afbFrameRows||[]).filter(function(x){return String(x.user_id)===id;}).forEach(function(x){
        $('<img>',{class:'frame_class_chat',alt:'',src:'addons/<?php echo rawurlencode($afbSlug); ?>/files/frames/'+encodeURIComponent(x.tumb)}).appendTo($a);
      });
    });
  };
  $(function(){
    if(typeof appInputMenu==='function')appInputMenu('addons/<?php echo rawurlencode($afbSlug); ?>/files/icon.svg','avatar_frame_box();');
    window.update_frame();
    if(window.afbFrameTimer)clearInterval(window.afbFrameTimer);
    window.afbFrameTimer=setInterval(window.update_frame,60000);
  });
})(jQuery);
</script>
<style><?php @include __DIR__ . '/style.css'; ?></style>
<?php } ?>
