<?php
require_once __DIR__ . '/../../../system/config_session.php';
require_once __DIR__ . '/settings.php';
header('Content-Type: application/json; charset=utf-8');
function chac_fail($message, $code = 400) { http_response_code($code); echo json_encode(array('success'=>false,'error'=>$message)); exit; }
if (empty($data['user_id']) || !chathypeCreatorEnabled() || !isset($mysqli) || !($mysqli instanceof mysqli)) chac_fail('Avatar Creator staat uit of je bent niet ingelogd.', 403);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') chac_fail('Ongeldige aanvraag.', 405);
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input) || empty($input['image']) || !is_string($input['image'])) chac_fail('Geen avatar ontvangen.');
if (!preg_match('#^data:image/png;base64,#', $input['image'])) chac_fail('Alleen PNG-afbeeldingen zijn toegestaan.');
$encoded = substr($input['image'], strpos($input['image'], ',') + 1);
$binary = base64_decode($encoded, true);
if ($binary === false || strlen($binary) > 1500000) chac_fail('Afbeelding is ongeldig of te groot.');
$info = @getimagesizefromstring($binary);
if (!$info || $info[0] !== 360 || $info[1] !== 360 || $info['mime'] !== 'image/png') chac_fail('Afbeeldingsformaat klopt niet.');
if (!function_exists('imagecreatefromstring') || !function_exists('imagepng')) chac_fail('PHP GD is vereist om avatars op te slaan.', 500);
$im = @imagecreatefromstring($binary);
if (!$im) chac_fail('PNG kon niet worden verwerkt.');
$avatarDir = realpath(__DIR__ . '/../../../avatar');
if (!$avatarDir || !is_dir($avatarDir) || !is_writable($avatarDir)) { imagedestroy($im); chac_fail('De CodyChat-map avatar/ moet bestaan en schrijfbaar zijn.', 500); }
$userId = (int)$data['user_id'];
$fileName = 'chathype_creator_' . $userId . '.png';
$target = $avatarDir . DIRECTORY_SEPARATOR . $fileName;
$tmp = $target . '.tmp';
if (!imagepng($im, $tmp, 7)) { imagedestroy($im); chac_fail('Avatar opslaan mislukt.', 500); }
imagedestroy($im);
if (!@rename($tmp, $target)) { @unlink($tmp); chac_fail('Avatarbestand kon niet worden geplaatst.', 500); }
$stmt = $mysqli->prepare('UPDATE boom_users SET user_tumb = ? WHERE user_id = ? LIMIT 1');
if (!$stmt) { @unlink($target); chac_fail('Database-update kon niet worden voorbereid.', 500); }
$stmt->bind_param('si', $fileName, $userId);
if (!$stmt->execute()) { $stmt->close(); @unlink($target); chac_fail('Profielfoto kon niet worden bijgewerkt.', 500); }
$stmt->close();
if (function_exists('redisUpdateUser')) redisUpdateUser($userId);
echo json_encode(array('success'=>true,'avatar'=>$fileName));
