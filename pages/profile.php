<?php
require_once("../core/config.php");
require_once("../core/includes/locale.php");
require_once("../core/includes/security.php");
require_once("../core/includes/path_utils.php");
require_once("../steam/steam_api.php");
require_once("../steam/steam_avatar.php");

$steamid = getSafeSteamID($conn);

if (!$steamid) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT DISTINCT `SteamID`, `PlayerName` FROM PlayerRecords WHERE `SteamID` = ? ORDER BY `PlayerName` ASC LIMIT 1");
$stmt->bind_param("s", $steamid);
$stmt->execute();
$result_player = $stmt->get_result();

if ($result_player->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$player_data = $result_player->fetch_assoc();

$avatar_data = getPlayerAvatar($steamid);

$page_title = $player_data['PlayerName'] . " - " . t('site_title');
$page_description = t('player_profile') . " " . $player_data['PlayerName'];

$steam_profile = getSteamProfile($steamid);
$steam_status = getSteamStatus($steamid);
$steam_formats = convertSteamID($steamid);

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

?>
<?php
include("../core/includes/header.php");
?>
<link rel="stylesheet" type="text/css" href="<?php echo getAssetPath('assets/css/profile.css'); ?>?version=1&t=<?php echo time(); ?>">

    <div class="profile-container">
        
        <div class="profile-header">
            <div class="player-avatar" id="player-avatar">
                <?php if ($avatar_data['success'] && $avatar_data['avatar_url']): ?>
                    <img src="<?php echo htmlspecialchars($avatar_data['avatar_url']); ?>" 
                         alt="Steam Avatar" 
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--hover);">
                <?php else: ?>
                    <?php echo $avatar_data['default_html'] ?? getDefaultAvatar($steamid); ?>
                <?php endif; ?>
            </div>
            <div class="player-info">
                <h1><?php echo htmlspecialchars($player_data['PlayerName']); ?></h1>
                <p><strong><?php echo t('steamid64'); ?>:</strong> <?php echo htmlspecialchars($steamid); ?></p>
                <p><strong><?php echo t('steamid3'); ?>:</strong> <?php echo htmlspecialchars($steam_formats['steamid3']); ?></p>
                <p><strong><?php echo t('steam_profile'); ?>:</strong> <a href="<?php echo $steam_profile['profileurl']; ?>" target="_blank" style="color: var(--hover);"><?php echo t('open_in_steam'); ?></a></p>
                <?php if ($steam_status['online']): ?>
                    <p><strong><?php echo t('status'); ?>:</strong> <span style="color: #4CAF50;"><?php echo t('status_' . $steam_status['status']); ?></span></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="player-stats">
            <div class="stat-card">
                <h3><?php echo $stats['total_records']; ?></h3>
                <p><?php echo t('total_records'); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['maps_completed']; ?></h3>
                <p><?php echo t('maps_completed'); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['best_time_ticks'] ? formatTimeFromTicks($stats['best_time_ticks']) : 'N/A'; ?></h3>
                <p><?php echo t('best_time'); ?></p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['avg_time_ticks'] ? formatTimeFromTicks($stats['avg_time_ticks']) : 'N/A'; ?></h3>
                <p><?php echo t('average_time'); ?></p>
            </div>
        </div>
        
        <div class="profile-sections">
            <div class="section">
                <h2><i class="fa-solid fa-trophy"></i> <?php echo t('best_records'); ?></h2>
                <div class="records-list">
                    <?php if ($result_best_records->num_rows > 0): ?>
                        <?php while ($record = $result_best_records->fetch_assoc()): ?>
                            <div class="record-item">
                                <span class="record-map"><?php echo htmlspecialchars($record['MapName']); ?></span>
                                <span class="record-time"><?php echo htmlspecialchars($record['FormattedTime']); ?></span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p><?php echo t('no_records_found'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="section">
                <h2><i class="fa-solid fa-map"></i> <?php echo t('all_maps'); ?></h2>
                <div class="maps-grid">
                    <?php if ($result_player_maps->num_rows > 0): ?>
                        <?php while ($map = $result_player_maps->fetch_assoc()): ?>
                            <div class="map-card" onclick="showMapRecordsInline('<?php echo $steamid; ?>', '<?php echo $map['MapName']; ?>')">
                                <div class="map-name"><?php echo htmlspecialchars($map['MapName']); ?></div>
                                <div class="map-time"><?php echo htmlspecialchars($map['best_time']); ?></div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p><?php echo t('no_maps_found'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        window.profileSteamID = '<?php echo $steamid; ?>';
    </script>
    <script src="<?php echo getAssetPath('assets/js/profile.js'); ?>?version=1&t=<?php echo time(); ?>" defer></script>
</body>
</html>
