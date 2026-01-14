<?php
/**
 * DECIDE Surf Server - Main Configuration
 * 
 * ⚠️ IMPORTANT FOR PRODUCTION:
 * Before deploying to production, configure all database and API credentials below.
 * Use environment variables or secure configuration management in production.
 * 
 * This file contains the main configuration for the DECIDE surf server.
 * 
*/

// Database Configuration
$servername = "your_db_host_here";
$username = "your_db_user_here";
$password = "your_secure_password_here";
$dbname = "your_database_name_here";
$dbport = 3306; 
$dbcharset = "utf8mb4";

try {
    $port = isset($dbport) ? $dbport : 3306;
    
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    
    if ($conn->connect_error) {
        throw new Exception("Database connection error: " . $conn->connect_error);
    }
    
    $charset = isset($dbcharset) ? $dbcharset : "utf8mb4";
    $conn->set_charset($charset);
    
    $conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    
    if (!$conn->select_db($dbname)) {
        throw new Exception("Failed to select database: " . $dbname);
    }
    
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    
    $db_error_message = "Database is temporarily unavailable. Please try again later.";
    
    if (isset($debug_enabled) && $debug_enabled) {
        $db_error_message .= "<br><small>Error details: " . htmlspecialchars($e->getMessage()) . "</small>";
    }
    
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

function checkDatabaseConnection() {
    global $conn;
    
    if (!$conn || $conn->connect_error) {
        return false;
    }
    
    $result = $conn->query("SELECT 1");
    return $result !== false;
}

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
        $result = $conn->query("SELECT 1");
        if ($result === false) {
            return [
                'status' => 'error',
                'message' => 'Database unavailable',
                'details' => $conn->error
            ];
        }

        $server_info = $conn->server_info;
        $db_name = $dbname; 
        
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

$footerdesc = '';

// GameQ integration - creates a server list on the index page (limited functionality).
#GameQ (server list) true (enabled) or false (disabled)
$serverlist = true;

#Server list:
#Fakename can be omitted or left empty if not needed.
#IP must be numeric, not a domain. If you prefer to display a domain instead of the real IP, use "fakeip".
$serverq = array(
    0 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27501',
        'fakename' => '',
        'fakeip' => ''
    ),
    1 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27502',
        'fakename' => '',
        'fakeip' => ''
    ),
    2 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27503',
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
$steam_api_key = 'PASTE_YOUR_STEAM_API_KEY_HERE'; // Replace with your actual Steam API Key




function debug_log($message, $type = 'log') { }
function debug_info($message) { }
function debug_warn($message) { }
function debug_error($message) { }
function debug_sql($query, $params = []) { }
function debug_performance($start_time, $operation = 'Operation') { }

?>
