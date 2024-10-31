<?php

namespace src\Api\V1;

use src\Api\RiddleLoaderInterface;
use src\classes\RiddlePlugin;
use src\classes\UserSettings;

/**
 * This Loader is used with the V2 Riddle API (still Creator 1).
 * A new Loader exists in ../RiddleLoaderV2 for better authentication, even with OAuth :)
 */
class RiddleLoader implements RiddleLoaderInterface
{
    private $api;
    private $riddleParams;

    public function __construct(?string $token, ?string $bearer)
    {
        $this->token = $token;
        $this->bearer = $bearer;

        $this->api = new Api($bearer, $token);
        $this->riddleParams = [];
    }

    public static function isAuthorized(): bool
    {
        $credentials = UserSettings::loadCredentials();

        return $credentials['bearer'] !== '' && $credentials['bearer'] != '';
    }

    public function getTeamlist()
    {
        $teams = $this->api->getTeams();

        if ($teams === false) {
            return false;
        }
        
        if (!is_array($teams)) {
            return [];
        }

        foreach ($teams as $team)  {
            $this->teams[$team["id"]] = $team["name"];
        }

        return $this->teams;
    }

    public function setActiveTeam($teamId)
    {
        $teamIdValue = \in_array($teamId, [null, -1], true) ? null : $teamId;
        $this->riddleParams['teamId'] = $teamIdValue;
    }

    public function updateActiveTeam()
    {
        $teamId = UserSettings::getSelectedTeam();

        if ($teamId) {
            $this->setActiveTeam($teamId);
        }
    }

    public function getRiddles()
    {
        $riddles = $this->api->getRiddleList($this->riddleParams);

        if ($riddles === false) {
            return false;
        }
        
        if (!is_array($riddles)) {
            return [];
        }

        return $riddles['items'];
    }

    /**
     * At the moment this solution is rather bad and will be improved once there's an endpoint to get information about a single riddle ID
     */
    public function getRiddle($riddleId): ?array
    {
        $riddles = $this->getRiddles();

        foreach ($riddles as $riddle) {
            if ($riddle['id'] === $riddleId) {
                return $riddle;
            }
        }

        return null;
    }

    public function getEmbedCode($riddleId, array $params): ?string
    {
        $params['riddleId'] = $riddleId; // for backwards compatibility
        $embed = $this->api->getRiddleEmbedCode($params);

        if (substr($embed, 0, 4) != "<div") { // if the embed code doesn't start with <div, it doesn't exist
            return null;
        }

        return $embed;
    }

    public function getApi(): Api
    {
        return $this->api;
    }
}