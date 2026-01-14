<?php
require_once("../core/config.php");
require_once("../core/includes/locale.php");
require_once("../core/includes/path_utils.php");

$page_title = t('rules_page_title') . ' - ' . t('site_title');
$page_description = t('rules_page_title');

$rules_title = t('rules_title');
$rules_general = t('rules_general');
$rules_surf = t('rules_surf');
$rules_violations = t('rules_violations');
$rules_appeals = t('rules_appeals');
$footer_text = t('footer_text');

$rules_general_content = t('rules_general_content');
$rules_surf_content = t('rules_surf_content');
$rules_violations_content = t('rules_violations_content');
$rules_appeals_content = t('rules_appeals_content');

?>
<?php
include("../core/includes/header.php");
?>
<link rel="stylesheet" type="text/css" href="<?php echo getAssetPath('assets/css/rules.css'); ?>?version=1&t=<?php echo time(); ?>">

    <div class="rules-container">
        
        <div class="rules-header">
            <h1><i class="fa-solid fa-book"></i> <?php echo $rules_title; ?></h1>
        </div>
        
        <div class="rules-content">
            
            <div class="rules-section">
                <h2><i class="fa-solid fa-exclamation-triangle"></i> <?php echo $rules_general; ?></h2>
                <div class="rules-list">
                    <ul>
                        <?php foreach ($rules_general_content as $rule): ?>
                            <li><?php echo $rule; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <div class="rules-section">
                <h2><i class="fa-solid fa-water"></i> <?php echo $rules_surf; ?></h2>
                <div class="rules-list">
                    <ul>
                        <?php foreach ($rules_surf_content as $rule): ?>
                            <li><?php echo $rule; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <div class="rules-section">
                <h2><i class="fa-solid fa-gavel"></i> <?php echo $rules_violations; ?></h2>
                <div class="rules-list">
                    <ul>
                        <?php foreach ($rules_violations_content as $rule): ?>
                            <li><?php echo $rule; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <div class="rules-section">
                <h2><i class="fa-solid fa-balance-scale"></i> <?php echo $rules_appeals; ?></h2>
                <div class="rules-list">
                    <ul>
                        <?php foreach ($rules_appeals_content as $rule): ?>
                            <li><?php echo $rule; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
        </div>
        
        <div class="rules-footer">
            <p><i class="fa-solid fa-info-circle"></i> <?php echo $footer_text; ?></p>
        </div>
        
    </div>

    <?php include("../core/includes/footer.php"); ?>

</body>
</html>
