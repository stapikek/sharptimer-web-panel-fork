<?php
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/locale.php");
require_once(__DIR__ . "/path_utils.php");
require_once(__DIR__ . "/session.php");
$seo_title = null; $seo_description = null; $seo_keywords = null; $seo_author = null; $seo_og_image = null; $seo_theme_color = null;
require_once(__DIR__ . "/../ceo.php");
$base_path = getBasePath();

// Авторизация уже проверяется через функции в session.php
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
    <link rel="stylesheet" type="text/css" href="<?php echo $base_path; ?>assets/css/style.css?version=17&t=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_path; ?>assets/css/header.css?version=1&t=<?php echo time(); ?>">
    <link href="<?php echo $base_path; ?>assets/dist/hamburgers.css?version=2&t=<?php echo time(); ?>" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $base_path; ?>assets/images/favicon-32x32.png">
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
</head>

<body>
    
    <div class="header-layout">
        <div class="unified-header">
            <div class="unified-header-content">
                <div class="header-content">
                    <div class="logo-section">
                        <h1><a href="<?php echo $base_path; ?>index.php"><?php echo $pagetitle; ?></a></h1>
                    </div>
                    <div class="navigation-buttons">
                        <a href="<?php echo getRulesPath(); ?>" class="nav-button">
                            <i class="fa-solid fa-book"></i>
                            <?php echo t('nav_rules'); ?>
                        </a>
                        
                        <a href="<?php echo $base_path; ?>pages/records.php" class="nav-button">
                            <i class="fa-solid fa-trophy"></i>
                            <?php echo t('nav_records'); ?>
                        </a>
                        
                        <!-- Авторизация Steam удалена -->
                        
                        <?php if (isset($show_profile_link) && $show_profile_link): ?>
                        <a href="<?php echo $base_path; ?>pages/profile.php?steamid=<?php echo $steamid; ?>" class="nav-button">
                            <i class="fa-solid fa-user-circle"></i>
                            <?php echo t('nav_profile'); ?>
                        </a>
                        <?php endif; ?>
                        <?php if (isset($show_back_link) && $show_back_link): ?>
                        <a href="<?php echo $back_url; ?>" class="nav-button">
                            <i class="fa-solid fa-arrow-left"></i>
                            <?php echo t('back'); ?>
                        </a>
                        <?php endif; ?>
                        
                        <!-- Search Button -->
                        <button class="search-toggle-button" onclick="toggleSearch()">
                            <i class="fa-solid fa-search"></i>
                        </button>
                        
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
                    </div>
                </div>
                
                <div class="search-container" id="searchContainer">
                    <div class="search-box">
                        <i class="fa-solid fa-search"></i>
                        <input id="search" type="search" placeholder="<?php echo t('search_placeholder'); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?php echo $base_path; ?>assets/js/site-inline.js?version=1&t=<?php echo time(); ?>" defer></script>
    <script src="<?php echo $base_path; ?>assets/js/main.js?version=1&t=<?php echo time(); ?>" defer></script>
    <script src="<?php echo $base_path; ?>assets/js/header-inline.js?version=1&t=<?php echo time(); ?>" defer></script>
