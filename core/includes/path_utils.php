<?php
function getBasePath() {
    $script_dir = dirname($_SERVER['SCRIPT_NAME']);
    $script_dir = rtrim($script_dir, '/');
    
    if (strpos($script_dir, '/pages') !== false || strpos($script_dir, '/api') !== false) {
        return '../';
    }
    
    return '';
}

function getRootPath() {
    $base_path = getBasePath();
    return $base_path;
}

function getHomePath() {
    return getBasePath() . 'index.php';
}

function getProfilePath($steamid) {
    return getBasePath() . 'pages/profile.php?steamid=' . urlencode($steamid);
}

function getMapRecordsPath($mapname) {
    return getBasePath() . 'pages/map_records.php?map=' . urlencode($mapname);
}

function getRulesPath() {
    return getBasePath() . 'pages/rules.php';
}

function getMyProfilePath() {
    return getBasePath() . 'pages/my_profile.php';
}

function getSteamLoginPath() {
    return getBasePath() . 'steam/login.php';
}

function getApiPath($endpoint) {
    return getBasePath() . 'api/' . $endpoint;
}

function getAssetPath($resource) {
    return getBasePath() . ltrim($resource, '/');
}
?>
