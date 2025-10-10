<?php
// Database connection:
$servername = "localhost";
$username = "user";
$password = "pass";
$dbname = "db";
$conn = new mysqli($servername, $username, $password, $dbname);
if(!$conn){
    die("connection failed" . mysqli_connect_error());
}
// PAGE CONFIG:
#Page title:
$pagetitle = "DECIDE";

# Social Media (configure here)
$social_links = array(
    array(
        'name' => 'Discord',
        'url' => 'https://discord.gg/kn4g2W9XAw',
        'icon' => 'fa-brands fa-discord'
    ),
    // Example: add the required networks below
// array('name' => 'VK', 'url' => 'https://vk.com/yourpage', 'icon' => 'fa-brands fa-vk'),
// array('name' => 'YouTube', 'url' => 'https://youtube.com/@your', 'icon' => 'fa-brands fa-youtube'),
);

#Default map for the leaderboard that should load upon website registration
$defaultmap = "surf_whiteout";

// Map sections => true (enabled) or false (disabled)
#In the map list, map sections are created for each mode (kz, surf, bunnyhop). If disabled, there will be no sections.
#This works by looking for maps that start with the kz_, surf_, bh_ prefixes,
#so if a map doesn't have one before its name, it will be displayed in the uncategorized section at the end of the map list.
$mapdivision = true; 

#Which map tab should be open by default - works only if $mapdivision = true
#(can be surf, bh, kz, other)
$tabopened = "surf";

// How many records should be displayed on the leaderboard:
$limit = 100;

#Footer description:
$footerdesc = '

Лучший серф сервер в России

';

// GameQ integration - creates a server list on the index page (limited functionality).
#GameQ (server list) true (enabled) or false (disabled)
$serverlist = true;

#Server list:
#Fakename can be omitted or left empty if not needed.
#IP must be numeric, not a domain. If you prefer to display a domain instead of the real IP, use "fakeip".
$serverq = array(
    0 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27503',
        'fakename' => '',
        'fakeip' => ''
    ),
    1 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27504',
        'fakename' => '',
        'fakeip' => ''
    ),
    2 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27504',
        'fakename' => '',
        'fakeip' => ''
    ),
    3 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27504',
        'fakename' => '',
        'fakeip' => ''
    )
);

// DEFAULT LANGUAGE
// Available languages: 'ru', 'en'
// Change the value below to set the default language for the site
// Examples: $default_language = 'ru'; (Russian) or $default_language = 'en'; (English)
$default_language = 'en';

// DEBUG CONFIGURATION
// Enable/disable debug mode
// true = debug enabled, false = debug disabled
$debug_enabled = false;

// DEBUG API KEYS
// Centralized API key storage to avoid scattering across files
$api_keys = [
    'steam' => '', // Steam Web API Key (leave empty if not used)
// add other keys as needed, for example:
// 'faceit' => '', - coming soon
];

function get_api_key($service) {
    global $api_keys;
    return isset($api_keys[$service]) && $api_keys[$service] !== '' ? $api_keys[$service] : false;
}

// DEBUG FUNCTIONS
// Debug logging functions for console output
function debug_log($message, $type = 'log') {
    global $debug_enabled;
    
    if (!$debug_enabled) {
        return;
    }
    
    // Prevent debug output for JavaScript files and AJAX requests
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $content_type = headers_sent() ? '' : (headers_list()['Content-Type'] ?? '');
    
    if (strpos($request_uri, '.js') !== false || 
        strpos($content_type, 'application/javascript') !== false ||
        strpos($content_type, 'application/json') !== false ||
        (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
        return;
    }
    
    $timestamp = date('H:i:s');
    $formatted_message = "[$timestamp] DEBUG: " . $message;
    
    // Output to console
    echo "<script>console." . $type . "('" . addslashes($formatted_message) . "');</script>\n";
}

function debug_info($message) {
    debug_log($message, 'info');
}

function debug_warn($message) {
    debug_log($message, 'warn');
}

function debug_error($message) {
    debug_log($message, 'error');
}

function debug_sql($query, $params = []) {
    global $debug_enabled;
    
    if (!$debug_enabled) {
        return;
    }
    
    $formatted_query = $query;
    if (!empty($params)) {
        $formatted_query .= " | Params: " . json_encode($params);
    }
    
    debug_log("SQL: " . $formatted_query, 'info');
}

function debug_performance($start_time, $operation = 'Operation') {
    global $debug_enabled;
    
    if (!$debug_enabled) {
        return;
    }
    
    $execution_time = microtime(true) - $start_time;
    $memory_usage = memory_get_usage(true);
    $memory_peak = memory_get_peak_usage(true);
    
    debug_log("$operation completed in " . round($execution_time * 1000, 2) . "ms | Memory: " . 
              round($memory_usage / 1024 / 1024, 2) . "MB | Peak: " . 
              round($memory_peak / 1024 / 1024, 2) . "MB", 'info');
}

?>