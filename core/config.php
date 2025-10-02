<?php
// Database connection:
$servername = "localhost";
$username = "user";
$password = "pass";
$dbname = "db";
$conn = new mysqli($servername, $username, $password, $dbname);
if(!$conn){
    die("connection failed" . mysqli_connect_error());
}

// PAGE CONFIG:
#Page title:
$pagetitle = "DECIDE";

# Соцсети (настраиваются здесь)
$social_links = array(
    array(
        'name' => 'Discord',
        'url' => 'https://discord.gg/kn4g2W9XAw',
        'icon' => 'fa-brands fa-discord'
    ),
    // Пример: добавьте нужные сети ниже
    // array('name' => 'VK', 'url' => 'https://vk.com/yourpage', 'icon' => 'fa-brands fa-vk'),
    // array('name' => 'YouTube', 'url' => 'https://youtube.com/@your', 'icon' => 'fa-brands fa-youtube'),
);

#Карта по умолчанию для таблицы лидеров, которая должна загружаться при регистрации на веб-сайте
$defaultmap = "surf_whiteout";

// Разделы карты => true (включен) или false (выключен)
#В списке карт создаются разделы карты для каждого режима (kz, surf, bunnyhop). Если он выключен, разделов не будет.
#Это работает путем поиска карт, которые начинаются с префикса kz_, surf_, bh_,
#поэтому, если карта не имеет его перед своим названием, она будет отображаться в категории без рубрики в конце списка карт.
$mapdivision = true; 

#Какая вкладка с картой должна быть открыта по умолчанию - работает, только если $mapdivision = true
#(может быть surf, bh, kz, other)
$tabopened = "surf";

// Сколько записей должно отображаться в таблице лидеров:
$limit = 100;

#Footer description:
$footerdesc = '

Лучший серф сервер в России

';

// Интеграция с GameQ - создает неполноценный список серверов на странице индекса.
#GameQ (список серверов) true (включен) или false (выключен)
$serverlist = true;

#Список серверов:
#Fakename может быть опущено или пусто, если вы этого не хотите.
#IP должен быть числовым, а не доменным. Если вы предпочитаете отображать домен, а не реальный ip, используйте "fakeip".
$serverq = array(
    0 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27503',
        'fakename' => '',
        'fakeip' => ''
    ),
    1 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27504',
        'fakename' => '',
        'fakeip' => ''
    ),
    2 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27504',
        'fakename' => '',
        'fakeip' => ''
    ),
    3 => array(
        'type' => 'csgo',
        'host' => '194.147.90.190:27504',
        'fakename' => '',
        'fakeip' => ''
    )
);

// API KEYS
// Единая точка хранения ключей API, чтобы не бегать по файлам
$api_keys = [
    'steam' => '', // Steam Web API Key (оставьте пустым, если не используется)
    // добавляйте другие ключи по мере необходимости, например: (скоро)
    // 'faceit' => '',
];

function get_api_key($service) {
    global $api_keys;
    return isset($api_keys[$service]) && $api_keys[$service] !== '' ? $api_keys[$service] : false;
}

?>