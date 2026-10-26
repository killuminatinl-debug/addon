<?php
require_once __DIR__ . '/../../../system/config_session.php';
header('Content-Type: application/json; charset=utf-8');
function chac_fail($message, $code = 400) { http_response_code($code); echo json_encode(array('success'=>false,'error'=>$message)); exit; }
if (empty($data['user_id']) || !isset($mysqli) || !($mysqli instanceof mysqli)) chac_fail('Niet ingelogd.', 403);
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
if (!function_exists('imagecreatefromstring')) chac_fail('PHP GD is vereist om de avatar te verwerken.', 500);
$im = @imagecreatefromstring($binary);
if (!$im) chac_fail('PNG kon niet worden verwerkt.');
$folder = __DIR__ . '/saved';
if (!is_dir($folder) && !@mkdir($folder, 0755, true)) { imagedestroy($im); chac_fail('Kan opslagmap niet aanmaken.', 500); }
$userId = (int)$data['user_id'];
$fileName = 'user_' . $userId . '.png';
$target = $folder . '/' . $fileName;
$tmp = $target . '.tmp';
if (!imagepng($im, $tmp, 7)) { imagedestroy($im); chac_fail('Avatar opslaan mislukt.', 500); }
imagedestroy($im);
if (!@rename($tmp, $target)) { @unlink($tmp); chac_fail('Avatarbestand kon niet worden geplaatst.', 500); }
$relative = 'addons/Chathype_Avatar_Creator/system/saved/' . $fileName;
$stmt = $mysqli->prepare('UPDATE boom_users SET user_tumb = ? WHERE user_id = ? LIMIT 1');
if (!$stmt) chac_fail('Database-update mislukt: ' . $mysqli->error, 500);
$stmt->bind_param('si', $relative, $userId);
if (!$stmt->execute()) { $stmt->close(); chac_fail('Profielfoto kon niet worden bijgewerkt.', 500); }
$stmt->close();
echo json_encode(array('success'=>true,'avatar'=>$relative));
