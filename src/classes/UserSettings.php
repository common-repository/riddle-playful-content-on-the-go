<?php

namespace src\classes;

use src\Api\V1\RiddleLoader;

class UserSettings
{
    // V1 Options
    public const CONNECTED_OPTION = 'riddle_connected';
    public const API_KEY_OPTION = 'riddle_apikey';
    public const BEARER_OPTION = 'riddle_bearer';

    // V2 Options
    public const ACCESSTOKEN_OPTION = 'riddle_accessToken';
    public const SHOW_V2_RIDDLES = 'riddle_showV2Riddles';

    public const SELECTED_TEAM_OPTION = 'riddle_selected_team';

    private static $key;
    private static $bearer;
    private static $accessToken;
    private static $selectedTeam;

    public static function addDefaults() 
    {
        \add_option(self::ACCESSTOKEN_OPTION);
    }

    public static function destroySettings() 
    {
        \delete_option(self::CONNECTED_OPTION);
        \delete_option(self::API_KEY_OPTION);
        \delete_option(self::BEARER_OPTION);
        \delete_option(self::SELECTED_TEAM_OPTION);
        \delete_option(self::ACCESSTOKEN_OPTION);
    }

    public static function loadCredentials() : array
    {
        self::$key = \get_option(self::API_KEY_OPTION, '');
        self::$bearer = \get_option(self::BEARER_OPTION, '');
        self::$accessToken = \get_option(self::ACCESSTOKEN_OPTION, '');

        return [
            "apikey" => self::$key,
            "bearer" => self::$bearer,
            'accessToken' => self::$accessToken,
        ];
    }

    public static function saveAccessToken(string $accessToken)
    {
        \update_option(self::ACCESSTOKEN_OPTION, $accessToken);
    }

    public static function setSelectedTeam(int $teamId)
    {
        self::$selectedTeam = $teamId;
        \update_option(self::SELECTED_TEAM_OPTION, $teamId);
    }

    public static function removeSelectedTeam(): void
    {
        \delete_option(self::SELECTED_TEAM_OPTION);
    }

    public static function getSelectedTeam($default = -1): ?int
    {
        if (self::$selectedTeam) {
            return self::$selectedTeam;
        }
        
        $teamId = \get_option(self::SELECTED_TEAM_OPTION, $default);

        if (-1 === $teamId) { // to better work with legacy values... this means NULL
            return null;
        }

        return $teamId;
    }

    public static function disconnect()
    {
        \update_option(self::CONNECTED_OPTION, false);
        \delete_option(self::SELECTED_TEAM_OPTION);
    }
}