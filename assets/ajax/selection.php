<?php
require_once("../../core/config.php");
require_once("../../core/includes/locale.php");
require_once("../../core/includes/security.php");
require_once("../../core/includes/path_utils.php");

$i = 0;
$id = getSafeMapID($conn);

if (!$id || empty($id)) {
    echo "<div id='strangerdanger' class='row'>" . t('invalid_map') . "</div>";
    exit();
}

$limit = (int)100;
if ($limit <= 0 || $limit > 1000) {
    $limit = 100;
}

$stmt = $conn->prepare("SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `MapName` FROM playerrecords WHERE MapName = ? ORDER BY `TimerTicks` ASC LIMIT ?");
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
