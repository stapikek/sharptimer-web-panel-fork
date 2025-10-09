<?php 
    require_once(__DIR__ . "/../../core/config.php");
    require_once(__DIR__ . "/../../core/includes/locale.php");
    require_once(__DIR__ . "/../../core/includes/security.php");
    require_once(__DIR__ . "/../../core/includes/path_utils.php");
    
    $i = 0;
    $input = getSafeSearchQuery($conn);
    
    // Debug: Live search
    debug_log("Live search called with query: " . ($input ?: 'empty'));
    
    if (!$input) {
        debug_warn("Invalid search query provided");
        echo "<div id='strangerdanger' class='row'>" . t('invalid_search') . "</div>";
        exit();
    }
    
    $search_term = "%{$input}%";
    $stmt = $conn->prepare("SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `MapName` FROM PlayerRecords WHERE `PlayerName` LIKE ? OR `SteamID` LIKE ? ORDER BY `TimerTicks`");
    debug_sql("SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `MapName` FROM PlayerRecords WHERE `PlayerName` LIKE ? OR `SteamID` LIKE ? ORDER BY `TimerTicks`", [$search_term, $search_term]);
    $stmt->bind_param("ss", $search_term, $search_term);
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
            echo "<div id='strangerdanger' class='row'>".t('player_not_found')."</div>";
        }
?>