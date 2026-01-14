<?php
$ceo_config_path = __DIR__ . '/ceo.json';
$ceo = [
    'title' => 'DECIDE',
    'description' => 'Поиск по никнейму или SteamID64',
    'keywords' => 'surf, cs2, counter-strike, records, leaderboard',
    'author' => 'DECIDE',
    'og_image' => 'https://i.imgur.com/6gHn8TN.png',
    'theme_color' => '#f52f2f'
];

if (file_exists($ceo_config_path)) {
    $json = file_get_contents($ceo_config_path);
    $data = json_decode($json, true);
    if (is_array($data)) {
        if (isset($data['default']) && is_array($data['default'])) {
            $ceo = array_merge($ceo, $data['default']);
        }
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        if (isset($data['pages'][$script]) && is_array($data['pages'][$script])) {
            $ceo = array_merge($ceo, $data['pages'][$script]);
        }
    }
}

if (isset($page_title)) { $ceo['title'] = $page_title; }
if (isset($page_description)) { $ceo['description'] = $page_description; }
if (isset($page_keywords)) { $ceo['keywords'] = $page_keywords; }

$seo_title = $ceo['title'];
$seo_description = $ceo['description'];
$seo_keywords = $ceo['keywords'];
$seo_author = $ceo['author'];
$seo_og_image = $ceo['og_image'];
$seo_theme_color = $ceo['theme_color'];
?>

