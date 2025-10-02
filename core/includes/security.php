<?php
function isValidSteamID64($steamid) {
    return preg_match('/^7656119[0-9]{10}$/', $steamid);
}

function isValidMapName($mapname) {
    return preg_match('/^[a-zA-Z0-9_\-]+$/', $mapname);
}

function isValidSearchQuery($query) {
    return !preg_match('/[<>"\']/', $query) && strlen($query) <= 100;
}

function getSafeSteamID($conn) {
    if (!isset($_GET['steamid'])) {
        return null;
    }
    
    $steamid = trim($_GET['steamid']);
    
    if (!isValidSteamID64($steamid)) {
        return null;
    }
    
    return $conn->real_escape_string($steamid);
}

function getSafeMapName($conn) {
    if (!isset($_GET['map'])) {
        return null;
    }
    
    $mapname = trim($_GET['map']);
    
    if (!isValidMapName($mapname)) {
        return null;
    }
    
    return $conn->real_escape_string($mapname);
}

function getSafeSearchQuery($conn) {
    if (!isset($_POST['input'])) {
        return null;
    }
    
    $query = trim($_POST['input']);
    
    if (!isValidSearchQuery($query)) {
        return null;
    }
    
    return $conn->real_escape_string($query);
}

function getSafeMapID($conn) {
    if (!isset($_POST['id'])) {
        return null;
    }
    
    $id = trim($_POST['id']);
    
    if (!isValidMapName($id)) {
        return null;
    }
    
    return $conn->real_escape_string($id);
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
?>
