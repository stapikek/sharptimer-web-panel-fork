<?php

function getSteamProfile($steamid) {
    $api_key = getSteamAPIKey();
    
    if ($api_key) {
        $encoded_steamid = urlencode($steamid);
        $encoded_key = urlencode($api_key);
        $api_url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$encoded_key}&steamids={$encoded_steamid}";
        
        $response = @file_get_contents($api_url);
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['response']['players'][0])) {
                $player = $data['response']['players'][0];
                return array(
                    'steamid' => $steamid,
                    'personaname' => $player['personaname'] ?? 'Player',
                    'avatar' => $player['avatarfull'] ?? "https://steamcommunity.com/profiles/{$steamid}/avatar/",
                    'profileurl' => $player['profileurl'] ?? 'https://steamcommunity.com/profiles/' . $steamid
                );
            }
        }
    }

    $profile_data = array(
        'steamid' => $steamid,
        'personaname' => 'Player',
        'avatar' => "https://steamcommunity.com/profiles/{$steamid}/avatar/",
        'profileurl' => 'https://steamcommunity.com/profiles/' . $steamid
    );
    
    return $profile_data;
}

function getSteamAvatarUrl($steamid) {
    $api_key = getSteamAPIKey();
    
    if ($api_key) {
        $encoded_steamid = urlencode($steamid);
        $encoded_key = urlencode($api_key);
        $api_url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$encoded_key}&steamids={$encoded_steamid}";
        
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

function getSteamStatus($steamid) {
    $api_key = getSteamAPIKey();
    
    if ($api_key) {
        $encoded_steamid = urlencode($steamid);
        $encoded_key = urlencode($api_key);
        $api_url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$encoded_key}&steamids={$encoded_steamid}";
        
        $response = @file_get_contents($api_url);
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['response']['players'][0])) {
                $player = $data['response']['players'][0];
                $personastate = $player['personastate'] ?? 0;
                
                $status = array(
                    'online' => $personastate > 0,
                    'ingame' => $personastate == 1,
                    'status' => $personastate == 0 ? 'offline' : ($personastate == 1 ? 'ingame' : 'online'),
                    'game' => $player['gameextrainfo'] ?? null
                );
                return $status;
            }
        }
    }
    
    $status = array(
        'online' => false,
        'ingame' => false,
        'status' => 'offline',
        'game' => null
    );
    return $status;
}

function getSteamAPIKey() {
    global $steam_api_key;
    return !empty($steam_api_key) ? $steam_api_key : false;
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
