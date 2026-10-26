<?php
if (!defined('BOOM')) { die(); }
require_once __DIR__ . '/system/settings.php';
if (!chac_enabled()) { echo '<div class="page_full"><div class="page_element">Avatar Studio is uitgeschakeld.</div></div>'; return; }
if (empty($data['user_id'])) { echo '<div class="page_full"><div class="page_element">Log in om je avatar te maken.</div></div>'; return; }
include __DIR__ . '/files/avatar_studio.php';
