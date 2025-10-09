<?php
require_once("../core/config.php");
require_once("../core/includes/locale.php");
require_once("../core/includes/path_utils.php");

$current_lang = getCurrentLanguage();

// Определяем заголовки и содержимое в зависимости от языка
if ($current_lang == 'ru') {
    $page_title = 'Правила сервера - ' . t('site_title');
    $page_description = 'Правила сервера';
    
    $rules_title = 'Правила сервера';
    $rules_general = 'Общие правила';
    $rules_surf = 'Правила Surf режима';
    $rules_violations = 'Нарушения и наказания';
    $rules_appeals = 'Обжалование наказаний';
    $footer_text = 'Незнание правил не освобождает от ответственности';
    
    $rules_general_content = [
        'Запрещено использование читов, багов и эксплоитов',
        'Уважайте других игроков и администрацию',
        'Запрещен спам в чате',
        'Не мешайте другим игрокам проходить карты',
        'Следуйте указаниям администрации'
    ];
    
    $rules_surf_content = [
        'Используйте только стандартные настройки движения',
        'Запрещено использование скриптов для автоматического страфинга',
        'Разрешены только стандартные привязки клавиш',
        'Запрещено использование макросов',
        'Рекорды с подозрительным временем будут проверены'
    ];
    
    $rules_violations_content = [
        '<strong>Предупреждение</strong> - за мелкие нарушения',
        '<strong>Мут (1-24 часа)</strong> - за спам или оскорбления',
        '<strong>Кик</strong> - за повторные нарушения',
        '<strong>Бан (1 день - навсегда)</strong> - за серьезные нарушения',
        '<strong>Удаление рекордов</strong> - за использование читов'
    ];
    
    $rules_appeals_content = [
        'Обращения принимаются только через Discord',
        'Предоставьте доказательства вашей невиновности',
        'Решение администрации окончательно',
        'Повторные обращения по одному вопросу игнорируются'
    ];
    
} else {
    $page_title = 'Server Rules - ' . t('site_title');
    $page_description = 'Server Rules';
    
    $rules_title = 'Server Rules';
    $rules_general = 'General Rules';
    $rules_surf = 'Surf Mode Rules';
    $rules_violations = 'Violations and Punishments';
    $rules_appeals = 'Appeal Process';
    $footer_text = 'Ignorance of the rules does not exempt from responsibility';
    
    $rules_general_content = [
        'No cheats, bugs, or exploits allowed',
        'Respect other players and administration',
        'No chat spam',
        'Do not interfere with other players completing maps',
        'Follow administration instructions'
    ];
    
    $rules_surf_content = [
        'Use only standard movement settings',
        'No scripts for automatic strafing',
        'Only standard key bindings allowed',
        'No macros allowed',
        'Records with suspicious times will be reviewed'
    ];
    
    $rules_violations_content = [
        '<strong>Warning</strong> - for minor violations',
        '<strong>Mute (1-24 hours)</strong> - for spam or insults',
        '<strong>Kick</strong> - for repeated violations',
        '<strong>Ban (1 day - permanent)</strong> - for serious violations',
        '<strong>Record deletion</strong> - for cheating'
    ];
    
    $rules_appeals_content = [
        'Appeals are only accepted through Discord',
        'Provide evidence of your innocence',
        'Administration decision is final',
        'Repeated appeals for the same issue are ignored'
    ];
}

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
</body>
</html>
