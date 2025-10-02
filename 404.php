<?php
require_once 'core/config.php';
require_once 'core/includes/path_utils.php';
require_once 'core/includes/translations.php';
require_once 'core/includes/header.php';
?>

<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('404_title'); ?> - <?php echo t('site_name'); ?></title>
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/404.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'core/includes/header.php'; ?>
    
    <main class="main-content">
        <div class="wrapper">
            <div class="error-container">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1 class="error-code">404</h1>
                <h2 class="error-title"><?php echo t('404_title'); ?></h2>
                <p class="error-description"><?php echo t('404_description'); ?></p>
                <div class="error-actions">
                    <a href="<?php echo $base_path; ?>" class="btn btn-primary">
                        <i class="fas fa-home"></i>
                        <?php echo t('404_home'); ?>
                    </a>
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <?php echo t('404_back'); ?>
                    </a>
                </div>
                <div class="error-suggestions">
                    <h3><?php echo t('404_suggestions_title'); ?></h3>
                    <ul>
                        <li><a href="<?php echo $base_path; ?>pages/records"><?php echo t('nav_records'); ?></a></li>
                        <li><a href="<?php echo $base_path; ?>pages/rules"><?php echo t('nav_rules'); ?></a></li>
                        <li><a href="<?php echo $base_path; ?>pages/profile"><?php echo t('nav_profile'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <script src="<?php echo $base_path; ?>assets/js/main.js" defer></script>
</body>
</html>
