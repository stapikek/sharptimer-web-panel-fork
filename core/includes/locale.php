<?php
 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$supported_languages = ['ru', 'en'];

// Получаем язык по умолчанию из config.php
require_once __DIR__ . '/../config.php';
$default_language = isset($default_language) ? $default_language : 'ru';

// Проверяем, что язык по умолчанию поддерживается
if (!in_array($default_language, $supported_languages)) {
    $default_language = 'ru'; // Fallback на русский
}

if (isset($_GET['lang']) && in_array($_GET['lang'], $supported_languages)) {
    $_SESSION['language'] = $_GET['lang'];
    $current_language = $_GET['lang'];
} elseif (isset($_SESSION['language']) && in_array($_SESSION['language'], $supported_languages)) {
    $current_language = $_SESSION['language'];
} else {
    $current_language = $default_language;
    $_SESSION['language'] = $current_language;
}

function loadTranslations($lang) {
    $translation_file = __DIR__ . "/translations/{$lang}.php";
    if (file_exists($translation_file)) {
        return include($translation_file);
    }
    return [];
}

$translations = loadTranslations($current_language);

function t($key, $params = []) {
    global $translations;
    
    if (isset($translations[$key])) {
        $text = $translations[$key];
        
        foreach ($params as $param_key => $param_value) {
            $text = str_replace('{' . $param_key . '}', $param_value, $text);
        }
        
        return $text;
    }
    
    return $key;
}

function getLangUrl($lang) {
    $current_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $parsed_url = parse_url($current_url);
    $path = $parsed_url['path'];
    $query = isset($parsed_url['query']) ? $parsed_url['query'] : '';
    
    parse_str($query, $query_params);
    unset($query_params['lang']);
    
    $query_params['lang'] = $lang;
    
    $new_query = http_build_query($query_params);
    
    // Если это главная страница, возвращаем полный путь
    if ($path === '/surf/' || $path === '/surf') {
        return '/surf/' . ($new_query ? '?' . $new_query : '');
    }
    
    return $path . ($new_query ? '?' . $new_query : '');
}

function getCurrentLanguage() {
    global $current_language;
    return $current_language;
}

function getSupportedLanguages() {
    global $supported_languages;
    return $supported_languages;
}

function getDefaultLanguage() {
    global $default_language;
    return $default_language;
}

?>
