<?php
/**
 * Debug Test Page
 * Demonstration of debug system functionality
 */

require_once("core/config.php");

// Enable debug for testing
$debug_enabled = true;

$page_title = "Debug Test";
$page_description = "Debug system testing";
$page_keywords = "debug, test, console";
?>
<?php include("core/includes/header.php"); ?>

<div class="container">
    <h1>🔧 Debug System Test</h1>
    
    <div class="debug-section">
        <h2>📊 Debug Functions Test</h2>
        
        <button onclick="testDebugLog()">Test debug_log()</button>
        <button onclick="testDebugInfo()">Test debug_info()</button>
        <button onclick="testDebugWarn()">Test debug_warn()</button>
        <button onclick="testDebugError()">Test debug_error()</button>
        <button onclick="testDebugSQL()">Test debug_sql()</button>
        <button onclick="testDebugPerformance()">Test debug_performance()</button>
    </div>
    
    <div class="debug-section">
        <h2>⚙️ Debug Configuration</h2>
        <p><strong>Debug Enabled:</strong> <?php echo $debug_enabled ? '✅ Yes' : '❌ No'; ?></p>
        <p><strong>Current Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
    
    <div class="debug-section">
        <h2>📝 Instructions</h2>
        <ol>
            <li>Open browser console (F12)</li>
            <li>Click the test buttons above</li>
            <li>Check console for debug messages</li>
            <li>To enable/disable debug, edit <code>core/config.php</code> and change <code>$debug_enabled</code></li>
        </ol>
    </div>
</div>

<style>
.debug-section {
    margin: 20px 0;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: var(--glass-bg);
}

.debug-section h2 {
    margin-top: 0;
    color: var(--fontcolor);
}

button {
    margin: 5px;
    padding: 10px 15px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    opacity: 0.8;
}

code {
    /* background: #f4f4f4; */
    padding: 2px 4px;
    border-radius: 3px;
    font-family: monospace;
}
</style>

<script>
function testDebugLog() {
    // Make AJAX request to trigger debug_log
    fetch('debug_ajax.php?action=debug_log', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.text())
    .then(data => {
        console.log("Debug log test completed - check console for messages");
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function testDebugInfo() {
    fetch('debug_ajax.php?action=debug_info', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.text())
    .then(data => {
        console.log("Debug info test completed - check console for messages");
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function testDebugWarn() {
    fetch('debug_ajax.php?action=debug_warn', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.text())
    .then(data => {
        console.log("Debug warn test completed - check console for messages");
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function testDebugError() {
    fetch('debug_ajax.php?action=debug_error', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.text())
    .then(data => {
        console.log("Debug error test completed - check console for messages");
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function testDebugSQL() {
    fetch('debug_ajax.php?action=debug_sql', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.text())
    .then(data => {
        console.log("Debug SQL test completed - check console for messages");
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function testDebugPerformance() {
    fetch('debug_ajax.php?action=debug_performance', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.text())
    .then(data => {
        console.log("Debug performance test completed - check console for messages");
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Page load debug
console.log("Debug test page loaded successfully");
</script>

<?php
// Page load debug - execute only once when page loads
debug_log("Debug test page loaded successfully");
?>

<?php include("core/includes/footer.php"); ?>
