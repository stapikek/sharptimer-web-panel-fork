<?php
require_once("../core/config.php");
require_once("../core/includes/locale.php");
require_once("../core/includes/security.php");
require_once("../core/includes/path_utils.php");

$steamid = getSafeSteamID($conn);
$mapname = getSafeMapName($conn);

if (!$steamid || !$mapname) {
    header("Location: index.php");
    exit();
}

$stmt_player = $conn->prepare("SELECT DISTINCT `SteamID`, `PlayerName` FROM PlayerRecords WHERE `SteamID` = ? ORDER BY `PlayerName` ASC LIMIT 1");
$stmt_player->bind_param("s", $steamid);
$stmt_player->execute();
$result_player = $stmt_player->get_result();

if ($result_player->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$player_data = $result_player->fetch_assoc();

$page_title = $player_data['PlayerName'] . " - " . $mapname . " - " . t('site_title');
$page_description = t('map_records') . " " . $player_data['PlayerName'] . " " . t('map') . " " . $mapname;

$stmt_records = $conn->prepare("SELECT `FormattedTime`, `TimerTicks`, `UnixStamp` 
    FROM PlayerRecords 
    WHERE `SteamID` = ? AND `MapName` = ? 
    ORDER BY `TimerTicks` ASC");
$stmt_records->bind_param("ss", $steamid, $mapname);
$stmt_records->execute();
$result_records = $stmt_records->get_result();

$stmt_best = $conn->prepare("SELECT `FormattedTime`, `TimerTicks`, `UnixStamp` 
    FROM PlayerRecords 
    WHERE `SteamID` = ? AND `MapName` = ? 
    ORDER BY `TimerTicks` ASC 
    LIMIT 1");
$stmt_best->bind_param("ss", $steamid, $mapname);
$stmt_best->execute();
$result_best = $stmt_best->get_result();
$best_record = $result_best->fetch_assoc();

$stmt_stats = $conn->prepare("SELECT 
    COUNT(*) as total_attempts,
    MIN(TimerTicks) as best_time_ticks,
    MAX(TimerTicks) as worst_time_ticks,
    AVG(TimerTicks) as avg_time_ticks,
    MIN(UnixStamp) as first_attempt,
    MAX(UnixStamp) as last_attempt
    FROM PlayerRecords 
    WHERE `SteamID` = ? AND `MapName` = ?");
$stmt_stats->bind_param("ss", $steamid, $mapname);
$stmt_stats->execute();
$result_stats = $stmt_stats->get_result();
$stats = $result_stats->fetch_assoc();

function formatTimeFromTicks($ticks) {
    if ($ticks == 0) return "N/A";
    
    $total_seconds = $ticks / 128;
    $minutes = floor($total_seconds / 60);
    $seconds = $total_seconds % 60;
    
    if ($minutes > 0) {
        return sprintf("%d:%06.3f", $minutes, $seconds);
    } else {
        return sprintf("%.3f", $seconds);
    }
}

function formatDate($unix_timestamp) {
    if ($unix_timestamp == 0) return "N/A";
    return date('d.m.Y H:i', $unix_timestamp);
}
?>
<?php
include("../core/includes/header.php");
?>
<link rel="stylesheet" type="text/css" href="<?php echo getAssetPath('assets/css/map-records.css'); ?>?version=1&t=<?php echo time(); ?>">

    <div class="map-records-container">
        
        <div class="map-header">
            <h1><?php echo htmlspecialchars($mapname); ?></h1>
            <div class="player-name"><?php echo htmlspecialchars($player_data['PlayerName']); ?></div>
        </div>
        
        <div class="map-stats">
            <div class="stat-card">
                <h3><?php echo $stats['total_attempts']; ?></h3>
                <p><?php echo t('total_attempts'); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo $best_record ? $best_record['FormattedTime'] : 'N/A'; ?></h3>
                <p><?php echo t('best_time'); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['avg_time_ticks'] ? formatTimeFromTicks($stats['avg_time_ticks']) : 'N/A'; ?></h3>
                <p><?php echo t('average_time'); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['first_attempt'] ? formatDate($stats['first_attempt']) : 'N/A'; ?></h3>
                <p><?php echo t('first_attempt'); ?></p>
            </div>
        </div>
        
        <div class="records-section">
            <h2><i class="fa-solid fa-list"></i> <?php echo t('all_records_on_map'); ?></h2>
            <div class="records-list">
                <?php if ($result_records->num_rows > 0): ?>
                    <?php 
                    $rank = 1;
                    while ($record = $result_records->fetch_assoc()): 
                        $is_best = ($rank === 1);
                    ?>
                        <div class="record-item <?php echo $is_best ? 'best' : ''; ?>">
                            <div>
                                <div class="record-rank">#<?php echo $rank; ?></div>
                                <?php if ($is_best): ?>
                                    <div style="color: #FFD700; font-size: 0.8em; margin-top: 2px;">
                                        <i class="fa-solid fa-trophy"></i> <?php echo t('best_record'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="record-time"><?php echo htmlspecialchars($record['FormattedTime']); ?></div>
                            <div class="record-date"><?php echo formatDate($record['UnixStamp']); ?></div>
                        </div>
                    <?php 
                        $rank++;
                    endwhile; 
                    ?>
                    <?php else: ?>
                        <p><?php echo t('no_records_on_map'); ?></p>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
