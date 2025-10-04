<?php
/**
 * Debug AJAX Handler
 * Handles AJAX requests for debug testing
 */

require_once("core/config.php");

// Enable debug for testing
$debug_enabled = true;

// Get action from GET parameter
$action = $_GET['action'] ?? '';

// Execute debug functions based on action
switch ($action) {
    case 'debug_log':
        debug_log("Test debug_log() function called from AJAX");
        break;
        
    case 'debug_info':
        debug_info("Test debug_info() function called from AJAX");
        break;
        
    case 'debug_warn':
        debug_warn("Test debug_warn() function called from AJAX");
        break;
        
    case 'debug_error':
        debug_error("Test debug_error() function called from AJAX");
        break;
        
    case 'debug_sql':
        debug_sql("SELECT * FROM test_table WHERE id = ?", [123]);
        break;
        
    case 'debug_performance':
        $start = microtime(true);
        usleep(100000); // Simulate 100ms work
        debug_performance($start, "Test Performance");
        break;
        
    default:
        debug_log("Unknown debug action: " . $action);
        break;
}

// Return success response
echo json_encode(['status' => 'success', 'action' => $action]);
?>
