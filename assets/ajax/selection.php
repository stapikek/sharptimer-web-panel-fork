<?php
require_once(__DIR__ . "/../../core/config.php");
require_once(__DIR__ . "/../../core/includes/locale.php");
require_once(__DIR__ . "/../../core/includes/security.php");

$i = 0;
$id = getSafeMapID($conn);

if (!$id) {
    echo "<div id='strangerdanger' class='row'>" . t('invalid_map') . "</div>";
    exit();
}

$stmt = $conn->prepare("SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `MapName` FROM PlayerRecords WHERE MapName = ? ORDER BY `TimerTicks` ASC LIMIT ?");
$stmt->bind_param("si", $id, $limit);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $i++;
        echo '<a href="../pages/profile.php?steamid='.htmlspecialchars($row['SteamID']).'"><div';
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
