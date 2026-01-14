<?php
function isValidSteamID64($steamid) {
    return preg_match('/^7656119[0-9]{10}$/', $steamid) && strlen($steamid) === 17;
}

function isValidMapName($mapname) {
    return preg_match('/^[a-zA-Z0-9_\-]+$/', $mapname) && 
           strlen($mapname) >= 3 && 
           strlen($mapname) <= 64 &&
           !preg_match('/^[0-9]+$/', $mapname) &&
           strpos($mapname, '..') === false && 
           strpos($mapname, '  ') === false;
}

function isValidSearchQuery($query) {
    return !preg_match('/[<>"\']/', $query) && 
           strlen($query) >= 1 && 
           strlen($query) <= 100 && 
           !preg_match('/[;\\\'\"]/', $query) &&
           !preg_match('/\b(union|select|insert|update|delete|drop|create|alter|exec|execute|load|into|outfile|script)\b/i', $query) &&
           !preg_match('/--|#|\/\*|\*\//', $query) &&
           !preg_match('/\s{2,}/', $query);
}

function getSafeSteamID($conn) {
    if (!isset($_GET['steamid'])) {
        return null;
    }
    
    $steamid = trim($_GET['steamid']);
    
    if (!isValidSteamID64($steamid)) {
        return null;
    }
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
    return $id;
}

function mapExists($conn, $mapname) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM playerrecords WHERE MapName = ?");
    $stmt->bind_param("s", $mapname);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row['count'] > 0;
}

function playerExists($conn, $steamid) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM playerrecords WHERE SteamID = ?");
    $stmt->bind_param("s", $steamid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row['count'] > 0;
}

function sanitizeInput($input) {
    $input = str_replace(["\0", "\r", "\n", "\t", "\v", "\f"], '', $input);
    
    $input = trim($input);
    
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
    $log_entry = date('Y-m-d H:i:s') . " - Security Event: " . $event;
    if ($details) {
        $log_entry .= " - Details: " . $details;
    }
    $log_entry .= " - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
    
    error_log($log_entry, 3, __DIR__ . '/../logs/security.log');
}
?>
