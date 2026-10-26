<?php
require_once __DIR__ . '/../../../system/config_session.php';
require_once __DIR__ . '/settings.php';
header('Content-Type: application/json; charset=utf-8');
function chac_json_fail($m,$s){http_response_code($s);echo json_encode(array('success'=>false,'error'=>$m));exit;}
if($_SERVER['REQUEST_METHOD']!=='POST')chac_json_fail('Ongeldige aanvraag.',405);
if(empty($data['user_id'])||!chac_enabled())chac_json_fail('Log in en controleer of Avatar Studio aanstaat.',403);
if(!isset($mysqli)||!($mysqli instanceof mysqli))chac_json_fail('Databaseverbinding ontbreekt.',500);
$in=json_decode(file_get_contents('php://input'),true);
if(!is_array($in)||empty($in['image'])||!is_string($in['image'])||!preg_match('#^data:image/png;base64,#',$in['image']))chac_json_fail('Ongeldige PNG ontvangen.',400);
$bin=base64_decode(substr($in['image'],strpos($in['image'],',')+1),true);
if($bin===false||strlen($bin)<100||strlen($bin)>1200000||substr($bin,0,8)!=="\\x89PNG\\r\\n\\x1a\\n")chac_json_fail('PNG is ongeldig of te groot.',400);
$info=@getimagesizefromstring($bin);if(!$info||(int)$info[0]!==360||(int)$info[1]!==360||$info['mime']!=='image/png')chac_json_fail('Avatar moet PNG 360x360 zijn.',400);
$uid=(int)$data['user_id'];$dir=realpath(__DIR__.'/../../../avatar');if(!$dir||!is_dir($dir)||!is_writable($dir))chac_json_fail('Map /avatar bestaat niet of is niet schrijfbaar.',500);
$name='chathype_avatar_'.$uid.'.png';$dest=$dir.DIRECTORY_SEPARATOR.$name;$tmp=$dest.'.tmp';if(file_put_contents($tmp,$bin,LOCK_EX)!==strlen($bin)){@unlink($tmp);chac_json_fail('Bestand opslaan mislukt.',500);}if(!@rename($tmp,$dest)){@unlink($tmp);chac_json_fail('Avatar kon niet worden geplaatst.',500);}
$stmt=$mysqli->prepare('UPDATE boom_users SET user_tumb = ? WHERE user_id = ? LIMIT 1');if(!$stmt){@unlink($dest);chac_json_fail('Database-update mislukt.',500);} $stmt->bind_param('si',$name,$uid);if(!$stmt->execute()){$stmt->close();@unlink($dest);chac_json_fail('Profielfoto bijwerken mislukt.',500);}$stmt->close();if(function_exists('redisUpdateUser'))redisUpdateUser($uid);echo json_encode(array('success'=>true,'avatar'=>$name));
