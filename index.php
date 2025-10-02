<?php
require_once("core/config.php");
require_once('assets/GameQ/Autoloader.php');
require_once('core/includes/locale.php');
require_once('core/includes/security.php');

$page_title = t('site_title');
$page_description = t('search_placeholder');
$page_keywords = 'surf, csgo, counter-strike, records, leaderboard, surf maps, surf servers';
?>
<?php
include("core/includes/header.php");
?>

<body>
    <div id="top"></div>
    <div class="mobiletoggler" onclick="toggleMobile()">
        <button class="hamburger hamburger--spin" type="button">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>
    </div>
    <div class="mobile-nav">
        <ul>
            <?php
            if (isset($links)) {
                echo $links;
            } else {
                echo "";
            }
            ?>
        </ul>
    </div>

    <?php if ($serverlist === true && !empty($serverq)) { ?>
        <div class="server-container">
            <div class="serverlist">
                <?php

                $GameQ = new \GameQ\GameQ();
                $GameQ->addServers($serverq);
                $GameQ->setOption('timeout', 5);
                $results = $GameQ->process();

                for ($x = 0; $x <= count($serverq) - 1; $x++) {
                    
                    ?>
                    <div class="server" <?php if ($results[$serverq[$x]['host']]['gq_online'] == "0") {
                        echo 'id="offline"';
                    } else {
                        echo 'id="online"';
                    } ?>>
                        <div class="basicinfo">
                            <h4>
                                <?php
                                if (empty($serverq[$x]['fakename'])) {
                                    if ($results[$serverq[$x]['host']]['gq_online'] == "0") {
                                        echo "Dead";
                                    } else {
                                        echo $results[$serverq[$x]['host']]['gq_hostname'];
                                    }
                                } else {
                                    echo $serverq[$x]['fakename'];
                                }
                                ?>
                            </h4>
                            <p><a href="steam://connect/<?php
                            if (empty($serverq[$x]['fakeip'])) {
                                echo $serverq[$x]['host'];
                            } else {
                                echo $serverq[$x]['fakeip'];
                            }
                            ?>">
                                    <?php
                                    if (empty($serverq[$x]['fakeip'])) {
                                        echo $serverq[$x]['host'];
                                    } else {
                                        echo $serverq[$x]['fakeip'];
                                    } ?>
                                </a> </p>
                        </div>
                        <?php if ($results[$serverq[$x]['host']]['gq_online'] == "0") {
                            echo "----";
                        } else { ?>
                            <div class="moreinfo">
                                <p>Map:
                                    <?php echo $results[$serverq[$x]['host']]['map'] ?>
                                </p>
                                <p>Players:
                                    <?php echo $results[$serverq[$x]['host']]['num_players'] ?> /
                                    <?php echo $results[$serverq[$x]['host']]['max_players'] ?>
                                </p>


                            </div>

                        <?php } ?>

                    </div>
                    <?php
                }
    } else {
        echo "";

    }
    ?>


        </div>

    </div>

    <main>
        <div class="wrapper">
            <div class="map-list2">
                <div id="sticky">
                <li class="togglemaps" onclick="toggleMaps()"><i class="fa-solid fa-xmark"></i></li>
                    <ul class="modes">

                        <?php
                        
                        $sqlsurf = "SELECT DISTINCT MapName FROM `PlayerRecords` WHERE MapName LIKE 'SURF%' ORDER BY MapName ASC ";
                        $resultsurf = $conn->query($sqlsurf);
                        
                        $sqlkz = "SELECT DISTINCT MapName FROM `PlayerRecords` WHERE MapName LIKE 'KZ%' ORDER BY MapName ASC ";
                        $resultkz = $conn->query($sqlkz);
                        
                        $sqlbh = "SELECT DISTINCT MapName FROM `PlayerRecords` WHERE MapName LIKE 'BHOP%' ORDER BY MapName ASC ";
                        $resultbh = $conn->query($sqlbh);
                        
                        $sqlother = "SELECT DISTINCT MapName FROM `PlayerRecords` WHERE MapName NOT LIKE 'BHOP%' AND MapName NOT LIKE 'SURF%' AND MapName NOT LIKE 'KZ%' ORDER BY MapName ASC ";
                        $resultother = $conn->query($sqlother);
                        if ($mapdivision === true) {
                            if ($resultsurf->num_rows > 0) {
                                echo '<li class="tablink';
                                if ($tabopened == "surf") {
                                    echo ' active"';
                                } else {
                                    echo '"';
                                }
                                echo 'onclick="openMode(event,' . "'surf'" . ')">SURF</li>';
                            }
                            if ($resultbh->num_rows > 0) {
                                echo '<li class="tablink';
                                if ($tabopened == "bh") {
                                    echo ' active"';
                                } else {
                                    echo '"';
                                }
                                echo 'onclick="openMode(event,' . "'bh'" . ')">BH</li>';
                            }
                            if ($resultkz->num_rows > 0) {
                                echo '<li class="tablink';
                                if ($tabopened == "kz") {
                                    echo ' active"';
                                } else {
                                    echo '"';
                                }
                                echo 'onclick="openMode(event,' . "'kz'" . ')">KZ</li>';
                            }
                            if ($resultother->num_rows > 0) {
                                echo '<li class="tablink';
                                if ($tabopened == "other") {
                                    echo ' active"';
                                } else {
                                    echo '"';
                                }
                                echo 'onclick="openMode(event,' . "'other'" . ')">Other</li>';
                            }
                        } else {
                            echo "";
                        }
                        ?>

                    </ul>
                    <ul class="mappeno" <?php 
                    if ($mapdivision === false){ 
                        echo 'style="display: block"';
                    }else {
                        echo "";
                    }
                    
                    ?>>
                        <?php
                        if ($mapdivision === true) {
                            if ($resultsurf->num_rows > 0) {
                                echo '<div id="surf" class="content';
                                if ($tabopened === "surf") {
                                    echo ' opened">';
                                } else {
                                    echo '">';
                                }
                                while ($row = $resultsurf->fetch_assoc()) {
                                    echo '<li class="selector" data-id="' . $row['MapName'] . '">' . $row['MapName'] . '</li>';
                                } ?>

                                <?php
                                echo '</div>';
                            }
                            if ($resultbh->num_rows > 0) {
                                echo '<div id="bh" class="content';
                                if ($tabopened === "bh") {
                                    echo ' opened">';
                                } else {
                                    echo '">';
                                }
                                while ($row = $resultbh->fetch_assoc()) {
                                    echo '<li class="selector" data-id="' . $row['MapName'] . '">' . $row['MapName'] . '</li>';
                                }
                                echo '</div>';
                            }
                            if ($resultkz->num_rows > 0) {
                                echo '<div id="kz" class="content';
                                if ($tabopened === "kz") {
                                    echo ' opened">';
                                } else {
                                    echo '">';
                                }
                                while ($row = $resultkz->fetch_assoc()) {
                                    echo '<li class="selector" data-id="' . $row['MapName'] . '">' . $row['MapName'] . '</li>';
                                }
                                echo '</div>';
                            }
                            if ($resultother->num_rows > 0) {
                                echo '<div id="other" class="content';
                                if ($tabopened === "other") {
                                    echo ' opened">';
                                } else {
                                    echo '">';
                                }
                                while ($row = $resultother->fetch_assoc()) {
                                    echo '<li class="selector" data-id="' . $row['MapName'] . '">' . $row['MapName'] . '</li>';
                                }
                                echo '</div>';
                            }
                        } else {
                            $sql = "SELECT DISTINCT MapName FROM `PlayerRecords` ORDER BY MapName ASC";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<li class="selector" data-id="' . $row['MapName'] . '">' . $row['MapName'] . '</li>';
                                }

                            }
                        }


                        ?>
                    </ul>

                </div>

            </div>
            <div class="leaderboard">
                <div class="info">
                    <div class="row">
                        <span> <i class="fa-solid fa-ranking-star"></i> </span>
                        <span> <i class="fa-solid fa-person-running"></i> <?php echo t('player'); ?> </span>
                        <span> <i class="fa-solid fa-clock"></i> <?php echo t('time'); ?></span>
                        <span> <i class="fa-solid fa-map"></i> <?php echo t('map'); ?> </span>

                    </div>
                </div>
                <div class="players">
                    <?php
$selected_map = $defaultmap;
$map_param = getSafeMapName($conn);
if ($map_param && mapExists($conn, $map_param)) {
    $selected_map = $map_param;
}
                    
                    $stmt = $conn->prepare("SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `MapName` FROM PlayerRecords WHERE MapName = ? ORDER BY `TimerTicks` ASC LIMIT ?");
                    $stmt->bind_param("si", $selected_map, $limit);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $i = 0;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $i++;
                            echo '<a href="pages/profile.php?steamid=' . htmlspecialchars($row['SteamID']) . '"><div';
                            if ($i % 2 == 0) {
                                echo ' id="stripped"';
                            } else {
                                echo "";
                            }
                            echo ' class="row">';
                            echo '<span>' . $i . '</span>';
                            echo '<span>' . htmlspecialchars($row['PlayerName']) . '</span>';
                            echo '<span>' . htmlspecialchars($row['FormattedTime']) . '</span>';
                            echo '<span>' . htmlspecialchars($row['MapName']) . '</span>';
                            echo '</div></a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

    </main>
    <footer>
        <div class="wrapper">
            <div>
                <h3>
                    <?php echo $pagetitle ?>
                </h3>
                <p>
                    <?php echo $footerdesc ?>
                </p>
            </div>
            <ul class="social-links">
                <?php if (!empty($social_links) && is_array($social_links)):
                    foreach ($social_links as $link):
                        $name = htmlspecialchars($link['name']);
                        $url = htmlspecialchars($link['url']);
                        $icon = htmlspecialchars($link['icon']);
                ?>
                    <li><a href="<?php echo $url; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo $name; ?>">
                        <i class="<?php echo $icon; ?>"></i>
                    </a></li>
                <?php endforeach; endif; ?>
            </ul>
        </div>


    </footer>
    
    <script src="assets/js/main.js" defer></script>
    <script src="assets/js/index-inline.js" defer></script>
</body>

</html>
