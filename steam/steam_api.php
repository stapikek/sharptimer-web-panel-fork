<?php

function getSteamProfile($steamid) {
    $profile_data = array(
        'steamid' => $steamid,
        'personaname' => 'Player',
        'avatar' => getSteamAvatarUrl($steamid),
        'profileurl' => 'https://steamcommunity.com/profiles/' . $steamid
    );
    
    return $profile_data;
}

function getSteamAvatarUrl($steamid) {
    $api_key = getSteamAPIKey();
    
    if ($api_key) {
        $api_url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$api_key}&steamids={$steamid}";
        
        $response = @file_get_contents($api_url);
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['response']['players'][0]['avatarfull'])) {
                return $data['response']['players'][0]['avatarfull'];
            }
        }
    }
    
    return "https://steamcommunity.com/profiles/{$steamid}/avatar/";
}

function isSteamProfilePublic($steamid) {
    $profile_url = 'https://steamcommunity.com/profiles/' . $steamid;
    
    $headers = @get_headers($profile_url, 1);
    if ($headers && strpos($headers[0], '200') !== false) {
        return true;
    }
    
    return false;
}

function getSteamStatus($steamid) {
    $status = array(
        'online' => false,
        'ingame' => false,
        'status' => 'offline',
        'game' => null
    );
    return $status;
}

function getSteamAPIKey() {
    if (function_exists('get_api_key')) {
        return get_api_key('steam');
    }
    return false;
}

function convertSteamID($steamid) {
    if (!isValidSteamID64($steamid)) {
        return false;
    }
    $steamid3 = '[U:1:' . (intval($steamid) - 76561197960265728) . ']';
    $steamid32 = intval($steamid) - 76561197960265728;
    
    return array(
        'steamid64' => $steamid,
        'steamid3' => $steamid3,
        'steamid32' => $steamid32,
        'steamid_legacy' => 'STEAM_0:' . ($steamid32 % 2) . ':' . floor($steamid32 / 2)
    );
}

?>
