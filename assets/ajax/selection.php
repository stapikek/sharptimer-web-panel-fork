<?php
require_once("../../core/config.php");
require_once("../../core/includes/locale.php");
require_once("../../core/includes/security.php");
require_once("../../core/includes/path_utils.php");

$i = 0;
$id = getSafeMapID($conn);

// Debug: Map selection
debug_log("Map selection called with map: " . ($id ?: 'invalid'));

if (!$id) {
    debug_warn("Invalid map ID provided");
    echo "<div id='strangerdanger' class='row'>" . t('invalid_map') . "</div>";
    exit();
}

// Use safe limit from config
$limit = 100; // Default limit, can be made configurable

$stmt = $conn->prepare("SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `MapName` FROM PlayerRecords WHERE MapName = ? ORDER BY `TimerTicks` ASC LIMIT ?");
debug_sql("SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `MapName` FROM PlayerRecords WHERE MapName = ? ORDER BY `TimerTicks` ASC LIMIT ?", [$id, $limit]);
$stmt->bind_param("si", $id, $limit);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $i++;
        echo '<a href="' . getProfilePath($row['SteamID']) . '"><div';
        if($i % 2 == 0){
            echo ' id="stripped"';
        }
        else{echo "";}
        echo ' class="row">';
        echo '<span>'.$i.'</span>';
        echo '<span>';
        echo htmlspecialchars($row['PlayerName']).'</span>';
        echo '<span>'.htmlspecialchars($row['FormattedTime']).'</span>';
        echo '<span>'.htmlspecialchars($row['MapName']).'</span>';
        echo '</div></a>';
    }
}
else{
    echo "<div id='strangerdanger' class='row'>".t('no_records')."</div>";
}
?>
