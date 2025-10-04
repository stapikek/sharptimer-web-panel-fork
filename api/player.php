<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once(__DIR__ . "/../core/config.php");
require_once(__DIR__ . "/../steam/steam_api.php");
require_once(__DIR__ . "/../core/includes/locale.php");
require_once(__DIR__ . "/../core/includes/security.php");

$steamid = getSafeSteamID($conn);

// Debug: API request
debug_log("API player.php called with SteamID: " . ($steamid ?: 'invalid'));

if (!$steamid) {
    debug_error("Invalid SteamID provided");
    http_response_code(400);
    echo json_encode(['error' => 'Invalid SteamID']);
    exit();
}

if (!isValidSteamID64($steamid)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid SteamID format']);
    exit();
}

try {
    $stmt_player = $conn->prepare("SELECT DISTINCT `SteamID`, `PlayerName` FROM PlayerRecords WHERE `SteamID` = ? ORDER BY `PlayerName` ASC LIMIT 1");
    $stmt_player->bind_param("s", $steamid);
    $stmt_player->execute();
    $result_player = $stmt_player->get_result();

    if ($result_player->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Player not found']);
        exit();
    }

    $player_data = $result_player->fetch_assoc();

    $stmt_stats = $conn->prepare("SELECT 
        COUNT(*) as total_records,
        COUNT(DISTINCT MapName) as maps_completed,
        MIN(TimerTicks) as best_time_ticks,
        AVG(TimerTicks) as avg_time_ticks
        FROM PlayerRecords WHERE `SteamID` = ?");
    $stmt_stats->bind_param("s", $steamid);
    $stmt_stats->execute();
    $result_stats = $stmt_stats->get_result();
    $stats = $result_stats->fetch_assoc();

    $stmt_best = $conn->prepare("SELECT `MapName`, `FormattedTime`, `TimerTicks` 
        FROM PlayerRecords 
        WHERE `SteamID` = ? 
        ORDER BY `TimerTicks` ASC 
        LIMIT 10");
    $stmt_best->bind_param("s", $steamid);
    $stmt_best->execute();
    $result_best_records = $stmt_best->get_result();
    $best_records = [];

    if ($result_best_records->num_rows > 0) {
        while ($record = $result_best_records->fetch_assoc()) {
            $best_records[] = $record;
        }
    }

    $stmt_maps = $conn->prepare("SELECT DISTINCT `MapName`, 
        (SELECT `FormattedTime` FROM PlayerRecords pr2 
         WHERE pr2.SteamID = ? AND pr2.MapName = PlayerRecords.MapName 
         ORDER BY pr2.TimerTicks ASC LIMIT 1) as best_time,
        (SELECT `TimerTicks` FROM PlayerRecords pr2 
         WHERE pr2.SteamID = ? AND pr2.MapName = PlayerRecords.MapName 
         ORDER BY pr2.TimerTicks ASC LIMIT 1) as best_time_ticks
        FROM PlayerRecords 
        WHERE `SteamID` = ? 
        ORDER BY `MapName` ASC");
    $stmt_maps->bind_param("sss", $steamid, $steamid, $steamid);
    $stmt_maps->execute();
    $result_player_maps = $stmt_maps->get_result();
    $player_maps = [];

    if ($result_player_maps->num_rows > 0) {
        while ($map = $result_player_maps->fetch_assoc()) {
            $player_maps[] = $map;
        }
    }

    $steam_profile = getSteamProfile($steamid);
    $steam_status = getSteamStatus($steamid);
    $steam_formats = convertSteamID($steamid);

    $response = [
        'success' => true,
        'language' => getCurrentLanguage(),
        'translations' => [
            'total_records' => t('total_records'),
            'maps_completed' => t('maps_completed'),
            'best_time' => t('best_time'),
            'average_time' => t('average_time'),
            'best_records' => t('best_records'),
            'all_maps' => t('all_maps'),
            'no_records_found' => t('no_records_found'),
            'no_maps_found' => t('no_maps_found')
        ],
        'player' => [
            'steamid' => $steamid,
            'name' => $player_data['PlayerName'],
            'steam_formats' => $steam_formats,
            'steam_profile' => $steam_profile,
            'steam_status' => $steam_status
        ],
        'statistics' => [
            'total_records' => (int)$stats['total_records'],
            'maps_completed' => (int)$stats['maps_completed'],
            'best_time' => $stats['best_time_ticks'] ? formatTimeFromTicks($stats['best_time_ticks']) : null,
            'average_time' => $stats['avg_time_ticks'] ? formatTimeFromTicks($stats['avg_time_ticks']) : null
        ],
        'best_records' => $best_records,
        'maps' => $player_maps
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}

// Функция для форматирования времени
function formatTimeFromTicks($ticks) {
    if ($ticks == 0) return null;
    
    $total_seconds = $ticks / 128; // Предполагаем 128 тиков в секунду
    $minutes = floor($total_seconds / 60);
    $seconds = $total_seconds % 60;
    
    if ($minutes > 0) {
        return sprintf("%d:%06.3f", $minutes, $seconds);
    } else {
        return sprintf("%.3f", $seconds);
    }
}

?>
