<?php
/**
 * DECIDE Surf Server - Main Configuration
 * 
 * This file contains the main configuration for the DECIDE surf server.
 * Database credentials are loaded from a separate file for security.
 * 
 * Security Note: Never commit actual database credentials to version control.
 * Use the db_config_template.php as a template and create your own db_config.php
 */

// Load database configuration
$db_config_file = __DIR__ . '/db_config.php';

if (file_exists($db_config_file)) {
    // Load actual database configuration
    require_once $db_config_file;
} else {
    // Fallback to template configuration (for development)
    // IMPORTANT: Replace these with your actual database credentials
    $servername = "localhost";
    $username = "your_db_user";
    $password = "your_secure_password";
    $dbname = "surf";
    $dbport = 3306;
    $dbcharset = "utf8mb4";
    
    // Log warning about missing config file
    error_log("WARNING: db_config.php not found. Using fallback configuration. Please create db_config.php from db_config_template.php");
}

// Enhanced database connection handling
try {
    // Use port if specified, otherwise use default
    $port = isset($dbport) ? $dbport : 3306;
    
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Database connection error: " . $conn->connect_error);
    }
    
    // Set charset (use configured charset or default to utf8mb4)
    $charset = isset($dbcharset) ? $dbcharset : "utf8mb4";
    $conn->set_charset($charset);
    
    // Check database availability
    if (!$conn->select_db($dbname)) {
        throw new Exception("Failed to select database: " . $dbname);
    }
    
} catch (Exception $e) {
    // Log error for debugging
    error_log("Database connection error: " . $e->getMessage());
    
    // Show user-friendly error message
    $db_error_message = "Database is temporarily unavailable. Please try again later.";
    
    // If debug mode is enabled, show detailed information
    if (isset($debug_enabled) && $debug_enabled) {
        $db_error_message .= "<br><small>Error details: " . htmlspecialchars($e->getMessage()) . "</small>";
    }
    
    // Output error page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Connection Error - <?php echo isset($pagetitle) ? $pagetitle : 'DECIDE'; ?></title>
        <link rel="stylesheet" type="text/css" href="assets/css/db-error.css">
    </head>
    <body class="db-error-body">
        <div class="db-error-container">
            <div class="db-error-icon">⚠️</div>
            <div class="db-error-title">Connection Problem</div>
            <div class="db-error-message">
                <?php echo $db_error_message; ?>
            </div>
            <a href="javascript:location.reload()" class="db-retry-button">Try Again</a>
            <div class="db-status-indicator-text">
                Status: Database Unavailable
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Function to check database connection status
function checkDatabaseConnection() {
    global $conn;
    
    if (!$conn || $conn->connect_error) {
        return false;
    }
    
    // Check if we can execute a simple query
    $result = $conn->query("SELECT 1");
    return $result !== false;
}

// Function to get database status
function getDatabaseStatus() {
    global $conn, $dbname;
    
    if (!$conn || $conn->connect_error) {
        return [
            'status' => 'error',
            'message' => 'No database connection',
            'details' => $conn ? $conn->connect_error : 'Connection not established'
        ];
    }
    
    try {
        // Check database availability
        $result = $conn->query("SELECT 1");
        if ($result === false) {
            return [
                'status' => 'error',
                'message' => 'Database unavailable',
                'details' => $conn->error
            ];
        }
        
        // Get server information
        $server_info = $conn->server_info;
        $db_name = $dbname; // Use variable from configuration
        
        return [
            'status' => 'ok',
            'message' => 'Database available',
            'details' => "Server: $server_info, Database: $db_name"
        ];
        
    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Error checking database',
            'details' => $e->getMessage()
        ];
    }
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

Best surf server in Russia

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

// STEAM API CONFIGURATION
// Steam Web API Key for getting avatars and player information
// 
// SETUP INSTRUCTIONS:
// 1. Go to: https://steamcommunity.com/dev/apikey
// 2. Log in to your Steam account
// 3. Fill out the form (Domain Name: your domain)
// 4. Copy the received key
// 5. Paste the key between quotes below
//
// Example: $steam_api_key = '1234567890ABCDEF1234567890ABCDEF';
$steam_api_key = ''; // Paste your Steam API key here

// DEBUG CONFIGURATION
// Enable/disable debug mode
// true = debug enabled, false = debug disabled
$debug_enabled = false;

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