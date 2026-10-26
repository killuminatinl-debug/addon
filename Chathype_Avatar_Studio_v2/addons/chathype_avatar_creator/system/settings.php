<?php
if (!function_exists('chac_enabled')) {
 function chac_enabled() {
  global $mysqli;
  if (!isset($mysqli) || !($mysqli instanceof mysqli)) return true;
  $q = @$mysqli->query("SELECT custom1 FROM boom_addons WHERE addons = 'chathype_avatar_creator' LIMIT 1");
  if (!$q) return true;
  $row = $q->fetch_assoc(); $q->free();
  return !$row || !isset($row['custom1']) || (string)$row['custom1'] !== '0';
 }
}
