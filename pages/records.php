<?php
require_once("../core/config.php");
require_once("../core/includes/locale.php");
require_once("../core/includes/security.php");
require_once("../core/includes/path_utils.php");

$page_title = t('records_page_title') . " - " . t('site_title');
$page_description = t('records_page_title');

$selected_map = 'all';

if (isset($_GET['map'])) {
    $map_param = getSafeMapName($conn);
    if ($map_param) {
        $selected_map = $map_param;
    }
}

if ($selected_map && stripos($selected_map, '_bonus') !== false) {
    $selected_map = 'all';
}

if ($selected_map && $selected_map !== 'all') {
    $stmt_check = $conn->prepare("SELECT COUNT(*) as count FROM playerrecords WHERE MapName = ?");
    $stmt_check->bind_param("s", $selected_map);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $check_data = $result_check->fetch_assoc();
    
    if ($check_data['count'] == 0) {
        $selected_map = 'all';
    }
}

$stmt_maps = $conn->prepare("SELECT DISTINCT MapName FROM playerrecords WHERE MapName NOT LIKE '%\\_bonus%' ORDER BY MapName ASC");
$stmt_maps->execute();
$result_maps = $stmt_maps->get_result();
$maps = [];
if ($result_maps->num_rows > 0) {
    while ($row = $result_maps->fetch_assoc()) {
        $maps[] = $row['MapName'];
    }
}

$records = [];
$limit = 100; 

if ($selected_map && $selected_map !== 'all') {
    $stmt_records = $conn->prepare("
        SELECT 
            PlayerName, 
            MapName, 
            FormattedTime, 
            SteamID,
            TimerTicks
        FROM playerrecords 
        WHERE MapName = ? 
        ORDER BY TimerTicks ASC 
        LIMIT ?
    ");
    $stmt_records->bind_param("si", $selected_map, $limit);
} elseif ($selected_map === 'all') {
    $stmt_records = $conn->prepare("
        SELECT 
            pr.PlayerName, 
            pr.MapName, 
            pr.FormattedTime, 
            pr.SteamID,
            pr.TimerTicks
        FROM playerrecords pr
        INNER JOIN (
            SELECT SteamID, MapName, MIN(TimerTicks) as best_time
            FROM playerrecords
            WHERE MapName NOT LIKE '%\\_bonus%'
            GROUP BY SteamID, MapName
        ) best ON pr.SteamID = best.SteamID 
                AND pr.MapName = best.MapName 
                AND pr.TimerTicks = best.best_time
        WHERE pr.MapName NOT LIKE '%\\_bonus%'
        ORDER BY pr.TimerTicks ASC 
        LIMIT ?
    ");
    $stmt_records->bind_param("i", $limit);
} else {
    $stmt_records = $conn->prepare("
        SELECT 
            pr.PlayerName, 
            pr.MapName, 
            pr.FormattedTime, 
            pr.SteamID,
            pr.TimerTicks
        FROM playerrecords pr
        INNER JOIN (
            SELECT SteamID, MapName, MIN(TimerTicks) as best_time
            FROM playerrecords
            WHERE MapName NOT LIKE '%\\_bonus%'
            GROUP BY SteamID, MapName
        ) best ON pr.SteamID = best.SteamID 
                AND pr.MapName = best.MapName 
                AND pr.TimerTicks = best.best_time
        WHERE pr.MapName NOT LIKE '%\\_bonus%'
        ORDER BY pr.TimerTicks ASC 
        LIMIT ?
    ");
    $stmt_records->bind_param("i", $limit);
}

if (isset($stmt_records)) {
    $stmt_records->execute();
    $result_records = $stmt_records->get_result();
    
    if ($result_records->num_rows > 0) {
        while ($row = $result_records->fetch_assoc()) {
            $records[] = $row;
        }
    }
}
?>
<?php include("../core/includes/header.php"); ?>
<link rel="stylesheet" type="text/css" href="<?php echo getAssetPath('assets/css/style.css'); ?>?version=17&t=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo getAssetPath('assets/css/records.css'); ?>?version=1&t=<?php echo time(); ?>">

    <div class="records-container">
        <div class="records-header">
            <h1><?php echo t('records_page_title'); ?></h1>
        </div>
        
        <div class="filter-section">
            <form method="GET" action="records.php" class="filter-form">
                <div class="filter-group">
                    <label for="map-select"><?php echo t('map'); ?>:</label>
                    <div class="select-wrapper">
                        <select id="map-select" name="map" class="map-dropdown">
                            <option value="all" <?php echo ($selected_map === 'all' || $selected_map === '') ? 'selected' : ''; ?>>
                                <?php echo t('all_maps'); ?>
                            </option>
                            <?php foreach ($maps as $map): ?>
                                <option value="<?php echo htmlspecialchars($map); ?>" 
                                        <?php echo ($selected_map === $map) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($map); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="show-button">
                    <?php echo t('show_records'); ?>
                </button>
            </form>
        </div>
        
        <div class="records-section">
            <?php if (!empty($records)): ?>
                <div class="records-table">
                    <div class="table-header">
                        <div class="col-rank"><?php echo t('place'); ?></div>
                        <div class="col-player"><?php echo t('player'); ?></div>
                        <div class="col-map"><?php echo t('map'); ?></div>
                        <div class="col-time"><?php echo t('time'); ?></div>
                    </div>
                    
                    <?php foreach ($records as $index => $record): ?>
                        <div class="table-row">
                            <div class="col-rank"><?php echo $index + 1; ?></div>
                            <div class="col-player">
                                <a href="profile.php?steamid=<?php echo urlencode($record['SteamID']); ?>">
                                    <?php echo htmlspecialchars($record['PlayerName']); ?>
                                </a>
                            </div>
                            <div class="col-map">
                                <?php echo htmlspecialchars($record['MapName']); ?>
                            </div>
                            <div class="col-time"><?php echo htmlspecialchars($record['FormattedTime']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-records">
                    <p><?php echo t('no_records_found'); ?></p>
                    <?php if ($selected_map && $selected_map !== 'all'): ?>
                        <p><?php echo t('no_records_on_map'); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($records) && count($records) >= $limit): ?>
            <div class="records-note">
                <p><i class="fa-solid fa-info-circle"></i> 
                   Показаны первые <?php echo $limit; ?> рекордов
                </p>
            </div>
        <?php endif; ?>
    </div>

    <?php include("../core/includes/footer.php"); ?>

</body>
</html>
