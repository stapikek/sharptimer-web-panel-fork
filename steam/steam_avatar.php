<?php

require_once("../core/config.php");

function getSteamAvatarData($steamid) {
    $api_key = getSteamAPIKey();
    
    if (empty($api_key)) {
        return [
            'success' => false,
            'avatar_url' => null,
            'fallback' => true,
            'message' => 'Steam API ключ не настроен'
        ];
    }
    
    $api_url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$api_key}&steamids={$steamid}";
    
    $response = @file_get_contents($api_url);
    if (!$response) {
        return [
            'success' => false,
            'avatar_url' => null,
            'fallback' => true,
            'message' => 'Не удалось подключиться к Steam API'
        ];
    }
    
    $data = json_decode($response, true);
    if (!$data || !isset($data['response']['players'][0])) {
        return [
            'success' => false,
            'avatar_url' => null,
            'fallback' => true,
            'message' => 'Профиль Steam не найден'
        ];
    }
    
    $player = $data['response']['players'][0];
    
    return [
        'success' => true,
        'avatar_url' => $player['avatarfull'] ?? null,
        'avatar_medium' => $player['avatarmedium'] ?? null,
        'avatar_small' => $player['avatar'] ?? null,
        'personaname' => $player['personaname'] ?? 'Player',
        'profileurl' => $player['profileurl'] ?? "https://steamcommunity.com/profiles/{$steamid}",
        'fallback' => false,
        'message' => 'Аватарка успешно получена'
    ];
}

/**
 * Получить дефолтную аватарку
 * @param string $steamid SteamID64
 * @return string HTML дефолтной аватарки
 */
function getDefaultAvatar($steamid) {
    $hash = crc32($steamid);
    $colors = [
        ['#667eea', '#764ba2'],
        ['#f093fb', '#f5576c'],
        ['#4facfe', '#00f2fe'],
        ['#43e97b', '#38f9d7'],
        ['#fa709a', '#fee140'],
        ['#a8edea', '#fed6e3'],
        ['#ff9a9e', '#fecfef'],
        ['#ffecd2', '#fcb69f']
    ];
    
    $color_index = abs($hash) % count($colors);
    $gradient = $colors[$color_index];
    
    return "
    <div style='
        width: 120px; 
        height: 120px; 
        border-radius: 50%; 
        background: linear-gradient(135deg, {$gradient[0]} 0%, {$gradient[1]} 100%); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: white; 
        font-size: 48px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border: 3px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
    '>
        <i class='fa-solid fa-user'></i>
        <div style='
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #333;
        '>
            <i class='fa-brands fa-steam'></i>
        </div>
    </div>";
}

/**
 * Получить аватарку игрока (основная функция)
 * @param string $steamid SteamID64
 * @return array Данные аватарки
 */
function getPlayerAvatar($steamid) {
    $steam_data = getSteamAvatarData($steamid);
    
    if ($steam_data['success'] && $steam_data['avatar_url']) {
        return $steam_data;
    }
    
    return [
        'success' => false,
        'avatar_url' => null,
        'fallback' => true,
        'default_html' => getDefaultAvatar($steamid),
        'message' => 'Используется дефолтная аватарка'
    ];
}

?>
