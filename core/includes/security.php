<?php
function isValidSteamID64($steamid) {
    // More strict validation for SteamID64
    return preg_match('/^7656119[0-9]{10}$/', $steamid) && strlen($steamid) === 17;
}

function isValidMapName($mapname) {
    // More strict validation for map names
    return preg_match('/^[a-zA-Z0-9_\-]+$/', $mapname) && 
           strlen($mapname) >= 3 && 
           strlen($mapname) <= 64 &&
           !preg_match('/^[0-9]+$/', $mapname); // Prevent pure numeric map names
}

function isValidSearchQuery($query) {
    // Enhanced validation for search queries
    return !preg_match('/[<>"\']/', $query) && 
           strlen($query) >= 1 && 
           strlen($query) <= 100 && 
           !preg_match('/[;\\\'\"]/', $query) &&
           !preg_match('/\b(union|select|insert|update|delete|drop|create|alter|exec|execute)\b/i', $query);
}

function getSafeSteamID($conn) {
    if (!isset($_GET['steamid'])) {
        return null;
    }
    
    $steamid = trim($_GET['steamid']);
    
    if (!isValidSteamID64($steamid)) {
        return null;
    }
    
    // Return validated SteamID without escaping since we'll use prepared statements
    return $steamid;
}

function getSafeMapName($conn) {
    if (!isset($_GET['map'])) {
        return null;
    }
    
    $mapname = trim($_GET['map']);
    
    if (!isValidMapName($mapname)) {
        return null;
    }
    
    // Return validated map name without escaping since we'll use prepared statements
    return $mapname;
}

function getSafeSearchQuery($conn) {
    if (!isset($_POST['input'])) {
        return null;
    }
    
    $query = trim($_POST['input']);
    
    if (!isValidSearchQuery($query)) {
        return null;
    }
    
    // Return validated query without escaping since we'll use prepared statements
    return $query;
}

function getSafeMapID($conn) {
    if (!isset($_POST['id'])) {
        return null;
    }
    
    $id = trim($_POST['id']);
    
    if (!isValidMapName($id)) {
        return null;
    }
    
    // Return validated map ID without escaping since we'll use prepared statements
    return $id;
}

function mapExists($conn, $mapname) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM PlayerRecords WHERE MapName = ?");
    $stmt->bind_param("s", $mapname);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row['count'] > 0;
}

function playerExists($conn, $steamid) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM PlayerRecords WHERE SteamID = ?");
    $stmt->bind_param("s", $steamid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row['count'] > 0;
}

function sanitizeInput($input) {
    // Remove null bytes and control characters
    $input = str_replace(["\0", "\r", "\n", "\t", "\v", "\f"], '', $input);
    
    // Trim whitespace
    $input = trim($input);
    
    // Remove any remaining control characters
    $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
    
    return $input;
}

function validateInteger($value) {
    return filter_var($value, FILTER_VALIDATE_INT) !== false;
}

function validatePositiveInteger($value) {
    return filter_var($value, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1))) !== false;
}

function logSecurityEvent($event, $details = '') {
    // Log security events for monitoring
    $log_entry = date('Y-m-d H:i:s') . " - Security Event: " . $event;
    if ($details) {
        $log_entry .= " - Details: " . $details;
    }
    $log_entry .= " - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
    
    // Write to security log file
    error_log($log_entry, 3, __DIR__ . '/../logs/security.log');
}
?>
