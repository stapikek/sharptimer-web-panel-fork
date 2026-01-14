<?php
function getBasePath() {
    $script_dir = dirname($_SERVER['SCRIPT_NAME']);
    $script_dir = rtrim($script_dir, '/');
    
    if (strpos($script_dir, '/pages') !== false || 
        strpos($script_dir, '/api') !== false || 
        strpos($script_dir, '/steam') !== false ||
        strpos($script_dir, '/assets') !== false) {
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
    $base_path = getBasePath();
    if (strpos($base_path, '../') === 0) {
        return $base_path . 'profile.php?steamid=' . urlencode($steamid);
    }
    return $base_path . 'pages/profile.php?steamid=' . urlencode($steamid);
}

function getMapRecordsPath($mapname) {
    $base_path = getBasePath();
    if (strpos($base_path, '../') === 0) {
        return $base_path . 'map_records.php?map=' . urlencode($mapname);
    }
    return $base_path . 'pages/map_records.php?map=' . urlencode($mapname);
}

function getRulesPath() {
    $base_path = getBasePath();
    if ($base_path === '../') {
        return 'rules.php';
    }

    return $base_path . 'pages/rules.php';
}

function getAssetPath($resource) {
    return getBasePath() . ltrim($resource, '/');
}
?>
