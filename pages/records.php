<?php
require_once("../core/config.php");
require_once("../core/includes/locale.php");
require_once("../core/includes/security.php");
require_once("../core/includes/path_utils.php");

$page_title = t('records_page_title') . " - " . t('site_title');
$page_description = t('records_page_title');

// Получаем выбранную карту из GET параметра
$selected_map = isset($_GET['map']) ? $_GET['map'] : '';

// Проверяем и санитизируем выбранную карту
// Исключаем бонусные карты
if ($selected_map && stripos($selected_map, '_bonus') !== false) {
    $selected_map = '';
}

if ($selected_map && $selected_map !== 'all') {
    // Проверяем, что карта существует в базе
    $stmt_check = $conn->prepare("SELECT COUNT(*) as count FROM PlayerRecords WHERE MapName = ?");
    $stmt_check->bind_param("s", $selected_map);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $check_data = $result_check->fetch_assoc();
    
    if ($check_data['count'] == 0) {
        $selected_map = ''; // Сбрасываем если карта не найдена
    }
}

// Получаем список всех карт для выпадающего меню (исключая бонусные)
$sql_maps = "SELECT DISTINCT MapName FROM PlayerRecords WHERE MapName NOT LIKE '%\\_bonus%' ORDER BY MapName ASC";
$result_maps = $conn->query($sql_maps);
$maps = [];
if ($result_maps->num_rows > 0) {
    while ($row = $result_maps->fetch_assoc()) {
        $maps[] = $row['MapName'];
    }
}

// Получаем рекорды в зависимости от выбранной карты
$records = [];
$limit = 100; // Ограничение на количество записей

if ($selected_map && $selected_map !== 'all') {
    // Рекорды для конкретной карты
    $stmt_records = $conn->prepare("
        SELECT 
            PlayerName, 
            MapName, 
            FormattedTime, 
            SteamID,
            TimerTicks
        FROM PlayerRecords 
        WHERE MapName = ? 
        ORDER BY TimerTicks ASC 
        LIMIT ?
    ");
    $stmt_records->bind_param("si", $selected_map, $limit);
} elseif ($selected_map === 'all') {
    // Все рекорды (лучший рекорд каждого игрока на каждой карте)
    $stmt_records = $conn->prepare("
        SELECT 
            pr.PlayerName, 
            pr.MapName, 
            pr.FormattedTime, 
            pr.SteamID,
            pr.TimerTicks
        FROM PlayerRecords pr
        INNER JOIN (
            SELECT SteamID, MapName, MIN(TimerTicks) as best_time
            FROM PlayerRecords
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
    // По умолчанию показываем топ рекорды со всех карт
    $stmt_records = $conn->prepare("
        SELECT 
            pr.PlayerName, 
            pr.MapName, 
            pr.FormattedTime, 
            pr.SteamID,
            pr.TimerTicks
        FROM PlayerRecords pr
        INNER JOIN (
            SELECT SteamID, MapName, MIN(TimerTicks) as best_time
            FROM PlayerRecords
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
<link rel="stylesheet" type="text/css" href="<?php echo $base_path; ?>assets/css/style.css?version=17&t=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $base_path; ?>assets/css/records.css?version=1&t=<?php echo time(); ?>">

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
                            <option value=""><?php echo t('select_map'); ?></option>
                            <option value="all" <?php echo ($selected_map === 'all') ? 'selected' : ''; ?>>
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
                                <a href="map_records.php?map=<?php echo urlencode($record['MapName']); ?>">
                                    <?php echo htmlspecialchars($record['MapName']); ?>
                                </a>
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

</body>
</html>
