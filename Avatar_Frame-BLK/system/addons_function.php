<?php
if (!function_exists('afb_h')) {
    function afb_h($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('afb_frames')) {
    function afb_frames($admin = false) {
        global $mysqli, $addons;
        if (!isset($mysqli) || !($mysqli instanceof mysqli)) return '<div class="pad15">Database niet beschikbaar.</div>';
        $q = $mysqli->query("SELECT id, price, rank, method, tumb FROM avatar_frame ORDER BY id DESC");
        if (!$q) return '<div class="pad15">Frames konden niet worden geladen. Voer eerst de addon-installatie uit.</div>';
        $slug = isset($addons['addons']) ? basename((string)$addons['addons']) : 'Avatar_Frame-BLK';
        $out = '<div class="afb-grid">';
        $count = 0;
        while ($row = $q->fetch_assoc()) {
            $name = basename((string)$row['tumb']);
            if (!preg_match('/\.(png|jpe?g|gif|webp)$/i', $name)) continue;
            $url = 'addons/' . rawurlencode($slug) . '/files/frames/' . rawurlencode($name);
            $out .= '<button type="button" class="afb-card' . ($admin ? ' afb-admin-card' : '') . '" data-id="' . (int)$row['id'] . '" data-img="' . afb_h($url) . '" aria-label="Frame kiezen">';
            $out .= '<img loading="lazy" src="' . afb_h($url) . '" alt="Avatarframe">';
            if ($admin) $out .= '<span>Frame #' . (int)$row['id'] . '</span>';
            $out .= '</button>';
            $count++;
        }
        $q->free();
        $out .= '</div>';
        if (!$count) $out = '<div class="pad15">Nog geen frames. Voeg eerst afbeeldingen toe via het beheer.</div>';
        return $out;
    }
}
if (!function_exists('frames_list_admin')) {
    function frames_list_admin() { return afb_frames(true); }
}
if (!function_exists('frames_box_list')) {
    function frames_box_list() { return afb_frames(false); }
}
