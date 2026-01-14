<?php
 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php';
$default_language = isset($default_language) ? $default_language : 'ru';

if (!in_array($default_language, ['ru', 'en'])) {
    $default_language = 'ru';
}

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ru', 'en'])) {
    $_SESSION['language'] = $_GET['lang'];
    $current_language = $_GET['lang'];
} elseif (isset($_SESSION['language']) && in_array($_SESSION['language'], ['ru', 'en'])) {
    $current_language = $_SESSION['language'];
} else {
    $current_language = $default_language;
    $_SESSION['language'] = $current_language;
}

function loadTranslations($lang) {
    $json_file = __DIR__ . '/../../lang/' . $lang . '.json';
    if (file_exists($json_file)) {
        $data = json_decode(file_get_contents($json_file), true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            $extra_file = __DIR__ . '/../../lang/' . $lang . '.extra.json';
            if (file_exists($extra_file)) {
                $extra = json_decode(file_get_contents($extra_file), true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($extra)) {
                    $data = array_merge($data, $extra);
                }
            }

            return $data;
        }
    }
    return [];
}

$translations = loadTranslations($current_language);

function t($key, $params = []) {
    global $translations;

    if (!isset($translations[$key])) {
        return $key;
    }

    $value = $translations[$key];

    if (is_array($value) && empty($params)) {
        return $value;
    }

    $text = (string)$value;
    foreach ($params as $param_key => $param_value) {
        $text = str_replace('{' . $param_key . '}', $param_value, $text);
    }

    return $text;
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
    if ($path === '/surf/' || $path === '/surf') {
        return '/surf/' . ($new_query ? '?' . $new_query : '');
    }
    
    return $path . ($new_query ? '?' . $new_query : '');
}

function getCurrentLanguage() {
    global $current_language;
    return $current_language;
}

?>
