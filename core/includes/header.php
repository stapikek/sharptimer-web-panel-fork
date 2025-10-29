<?php
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/locale.php");
require_once(__DIR__ . "/path_utils.php");
require_once(__DIR__ . "/session.php");
$seo_title = null; $seo_description = null; $seo_keywords = null; $seo_author = null; $seo_og_image = null; $seo_theme_color = null;
require_once(__DIR__ . "/../../core/ceo.php");

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? '';
$base_url = $host ? ($scheme . '://' . $host) : '';

// Debug: Page load start
$debug_start_time = microtime(true);
debug_log("Page load started: " . ($_SERVER['REQUEST_URI'] ?? 'unknown'));

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    
    <meta name="description" content="<?php echo htmlspecialchars($seo_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($seo_keywords); ?>">
    <meta name="author" content="<?php echo htmlspecialchars($seo_author); ?>">
    <meta name="robots" content="index, follow">
    <meta name="language" content="<?php echo getCurrentLanguage(); ?>">
    
    
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#f52f2f">
    <meta name="msapplication-TileColor" content="#f52f2f">
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?php echo getAssetPath('assets/css/style.css'); ?>?version=17&t=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo getAssetPath('assets/css/header.css'); ?>?version=1&t=<?php echo time(); ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo getAssetPath('assets/images/favicon-32x32.png'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($seo_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seo_description); ?>">
    <meta property="og:url" content="">
    <meta property="og:image" content="<?php echo htmlspecialchars($seo_og_image); ?>">
    <meta property="og:image:secure_url" content="<?php echo htmlspecialchars($seo_og_image); ?>">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:footer" content="SharpTimer">
    <meta name="theme-color" content="<?php echo htmlspecialchars($seo_theme_color); ?>">
    <title><?php echo htmlspecialchars($seo_title); ?></title>
    
    <!-- Предварительная загрузка темы для предотвращения мигания -->
    <script src="<?php echo getAssetPath('assets/js/theme-preloader.js'); ?>?version=1&t=<?php echo time(); ?>"></script>
</head>

<body>
    
    <?php
    // Индикатор статуса базы данных (только в режиме отладки)
    if (isset($debug_enabled) && $debug_enabled) {
        $db_status = getDatabaseStatus();
        $status_class = $db_status['status'] === 'ok' ? 'db-ok' : 'db-error';
        $status_text = $db_status['status'] === 'ok' ? 'БД OK' : 'БД ERROR';
        ?>
        <div class="db-status-indicator <?php echo $status_class; ?>" title="<?php echo htmlspecialchars($db_status['message']); ?>">
            <i class="fa-solid fa-database"></i>
            <span><?php echo $status_text; ?></span>
        </div>
        <link rel="stylesheet" type="text/css" href="<?php echo getAssetPath('assets/css/db-status.css'); ?>?version=1&t=<?php echo time(); ?>">
        <?php
    }
    ?>
    
    <div class="header-layout">
        <div class="unified-header">
            <div class="unified-header-content">
                <div class="header-content">
                    <div class="logo-section">
                        <h1><a href="<?php echo getHomePath(); ?>"><?php echo $pagetitle; ?></a></h1>
                    </div>
                    
                    <div class="search-section">
                        <div class="search-box">
                            <i class="fa-solid fa-search"></i>
                            <input id="search" type="search" placeholder="<?php echo t('search_placeholder'); ?>">
                        </div>
                    </div>
                    
                    <div class="navigation-buttons">
                        <a href="<?php echo getRulesPath(); ?>" class="nav-button">
                            <i class="fa-solid fa-book"></i>
                            <?php echo t('nav_rules'); ?>
                        </a>
                        
                        <a href="<?php echo getBasePath(); ?>pages/records.php" class="nav-button">
                            <i class="fa-solid fa-trophy"></i>
                            <?php echo t('nav_records'); ?>
                        </a>
                        
                        <!-- Language Switcher -->
                        <div class="language-switcher">
                            <button class="language-button" onclick="toggleLanguageDropdown()">
                                <i class="fa-solid fa-globe"></i>
                                <?php echo t('language'); ?>
                                <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <div class="language-dropdown" id="languageDropdown">
                                <a href="<?php echo getLangUrl('ru'); ?>" class="language-option <?php echo getCurrentLanguage() == 'ru' ? 'active' : ''; ?>">
                                    <i class="fa-solid fa-flag"></i>
                                    <?php echo t('language_russian'); ?>
                                </a>
                                <a href="<?php echo getLangUrl('en'); ?>" class="language-option <?php echo getCurrentLanguage() == 'en' ? 'active' : ''; ?>">
                                    <i class="fa-solid fa-flag"></i>
                                    <?php echo t('language_english'); ?>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Theme Switcher -->
                        <button class="theme-switcher" onclick="toggleTheme()" title="<?php echo t('theme_switch'); ?>" aria-label="<?php echo t('theme_switch'); ?>">
                            <div class="theme-switcher-inner">
                                <i class="fa-solid fa-moon theme-icon-dark" id="theme-icon-dark"></i>
                                <i class="fa-solid fa-sun theme-icon-light" id="theme-icon-light"></i>
                            </div>
                            <div class="theme-switcher-track">
                                <div class="theme-switcher-thumb"></div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?php echo getAssetPath('assets/js/site-inline.js'); ?>?version=1&t=<?php echo time(); ?>" defer></script>
    <script src="<?php echo getAssetPath('assets/js/main.js'); ?>?version=1&t=<?php echo time(); ?>" defer></script>
    
    <?php
    // Debug: Page load completion
    if (isset($debug_start_time)) {
        debug_performance($debug_start_time, "Page load");
        debug_log("Header loaded successfully");
    }
    ?>
