<?php
if (!defined('BOOM')) {
    require_once __DIR__ . '/../../../system/config_addons.php';
}
require_once __DIR__ . '/addons_function.php';
header('X-Content-Type-Options: nosniff');
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
    http_response_code(500); exit('Database unavailable');
}
if (!isset($data) || !is_array($data)) $data = array();
if (!isset($addons) || !is_array($addons)) $addons = array();
$slug = basename(dirname(__DIR__));
$frameDir = dirname(__DIR__) . '/files/frames/';
function afb_out_json($value) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($value);
    exit;
}
function afb_post_num($key, $default = 0) {
    return isset($_POST[$key]) && is_scalar($_POST[$key]) ? (int)$_POST[$key] : $default;
}
function afb_uid() {
    global $data;
    return isset($data['user_id']) ? (int)$data['user_id'] : 0;
}
function afb_is_admin() {
    return function_exists('canManageAddons') && canManageAddons();
}
function afb_token_valid() {
    return isset($_POST['token']) && is_scalar($_POST['token']) && trim((string)$_POST['token']) !== '';
}
function afb_get_frame($id) {
    global $mysqli;
    $s = $mysqli->prepare('SELECT id, price, rank, method, tumb FROM avatar_frame WHERE id=? LIMIT 1');
    if (!$s) return null;
    $s->bind_param('i', $id);
    if (!$s->execute()) { $s->close(); return null; }
    $r = $s->get_result();
    $row = $r ? $r->fetch_assoc() : null;
    $s->close();
    return $row ?: null;
}
function afb_valid_name($name) {
    return is_string($name) && $name !== '' && basename($name) === $name && preg_match('/\.(png|jpe?g|gif|webp)$/i', $name);
}
function afb_json_fail($code = 0) { afb_out_json($code); }

if (isset($_POST['get_user_frames_list'])) {
    $rows = array();
    $q = $mysqli->query("SELECT user_id, avatar_frame FROM boom_users WHERE avatar_frame > 0");
    if ($q) {
        while ($u = $q->fetch_assoc()) {
            $f = afb_get_frame((int)$u['avatar_frame']);
            if ($f && afb_valid_name($f['tumb']) && is_file($frameDir . $f['tumb'])) {
                $rows[] = array('user_id' => (string)$u['user_id'], 'tumb' => $f['tumb']);
            }
        }
        $q->free();
    }
    afb_out_json($rows);
}
if (isset($_POST['get_box'])) {
    if (!afb_uid()) { http_response_code(401); exit('Log in required'); }
    $addons['addons'] = $slug;
    $endpoint = json_encode('addons/' . rawurlencode($slug) . '/system/action.php');
    echo '<div class="pad15"><h3>Avatarframes</h3>';
    echo frames_box_list();
    echo '<div class="afb-current"><button type="button" class="reg_button default_btn" id="afb-remove-frame">Verwijder mijn frame</button></div></div>';
    echo '<script>(function($){var endpoint=' . $endpoint . ';$(document).off("click.afbChoose", ".afb-grid:not(.afb-admin-grid) .afb-card").on("click.afbChoose", ".afb-grid:not(.afb-admin-grid) .afb-card", function(){var id=parseInt($(this).attr("data-id"),10)||0;if(id){$.post(endpoint,{set_frame:id,token:utk},function(r){if(r===1||r==="1"){hideModal();if(typeof update_frame==="function")update_frame();if(typeof update_frame_userlist==="function")update_frame_userlist();if(typeof update_frame_chat==="function")update_frame_chat();if(window.callSuccess)callSuccess(system.saved);}else{if(window.callError)callError(system.error);}},"json").fail(function(){if(window.callError)callError(system.error);});}});$(document).off("click.afbRemove","#afb-remove-frame").on("click.afbRemove","#afb-remove-frame",function(){$.post(endpoint,{unset_frame:1,token:utk},function(r){if(r===1||r==="1"){hideModal();if(typeof update_frame==="function")update_frame();if(window.callSuccess)callSuccess(system.saved);}else{if(window.callError)callError(system.error);}}).fail(function(){if(window.callError)callError(system.error);});});})(jQuery);</script>';
    echo '<style>'; @include dirname(__DIR__) . '/files/style.css'; echo '</style>';
    exit;
}
if (isset($_POST['set_frame'])) {
    $uid = afb_uid(); $id = afb_post_num('set_frame');
    if (!$uid || !$id || !afb_token_valid()) afb_json_fail();
    $f = afb_get_frame($id);
    if (!$f || !afb_valid_name($f['tumb']) || !is_file($frameDir . $f['tumb'])) afb_json_fail();
    // Applying a frame is free. Wallet charging is not implemented without the exact wallet API.
    $s = $mysqli->prepare('UPDATE boom_users SET avatar_frame=? WHERE user_id=?');
    if (!$s) afb_json_fail();
    $s->bind_param('ii', $id, $uid); $ok = $s->execute(); $s->close();
    afb_out_json($ok ? 1 : 0);
}
if (isset($_POST['unset_frame'])) {
    $uid = afb_uid();
    if (!$uid || !afb_token_valid()) exit('0');
    $s = $mysqli->prepare('UPDATE boom_users SET avatar_frame=0 WHERE user_id=?');
    if (!$s) exit('0');
    $s->bind_param('i', $uid); $ok = $s->execute(); $s->close();
    exit($ok ? '1' : '0');
}
if (isset($_POST['add_frame'])) {
    if (!afb_is_admin() || !afb_token_valid()) afb_json_fail();
    if (!isset($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) afb_json_fail(2);
    $f = $_FILES['file'];
    if (!isset($f['error'], $f['size']) || $f['error'] !== UPLOAD_ERR_OK || $f['size'] < 1 || $f['size'] > 5 * 1024 * 1024) afb_json_fail(2);
    $img = @getimagesize($f['tmp_name']);
    $types = array('image/png'=>'png','image/jpeg'=>'jpg','image/gif'=>'gif','image/webp'=>'webp');
    if (!$img || !isset($types[$img['mime']])) afb_json_fail(2);
    if (!is_dir($frameDir) || !is_writable($frameDir)) afb_json_fail();
    try { $name = 'frame_' . bin2hex(random_bytes(12)) . '.' . $types[$img['mime']]; }
    catch (Exception $e) { $name = 'frame_' . md5(uniqid('', true)) . '.' . $types[$img['mime']]; }
    if (!move_uploaded_file($f['tmp_name'], $frameDir . $name)) afb_json_fail();
    $s = $mysqli->prepare('INSERT INTO avatar_frame (price,rank,method,tumb) VALUES (0,1,1,?)');
    if (!$s) { @unlink($frameDir . $name); afb_json_fail(); }
    $s->bind_param('s', $name); $ok = $s->execute(); $s->close();
    if (!$ok) @unlink($frameDir . $name);
    afb_out_json($ok ? 1 : 0);
}
if (isset($_POST['delete_frame'])) {
    if (!afb_is_admin() || !afb_token_valid()) exit('0');
    $id = afb_post_num('delete_frame'); $f = afb_get_frame($id);
    if (!$f) exit('0');
    $s = $mysqli->prepare('UPDATE boom_users SET avatar_frame=0 WHERE avatar_frame=?');
    if ($s) { $s->bind_param('i', $id); $s->execute(); $s->close(); }
    $s = $mysqli->prepare('DELETE FROM avatar_frame WHERE id=?');
    if (!$s) exit('0');
    $s->bind_param('i', $id); $ok = $s->execute(); $s->close();
    if ($ok && afb_valid_name($f['tumb']) && strpos($f['tumb'], 'frame_') === 0) @unlink($frameDir . $f['tumb']);
    exit($ok ? '1' : '0');
}
http_response_code(400);
exit('Unknown action');
