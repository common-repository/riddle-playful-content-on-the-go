<?php

namespace src\classes;

class RiddleLoaderV2 extends RiddleLoader
{
    private $api;

    private $riddleParams;

    public function __construct(RiddlePlugin $plugin, $token, $bearer)
    {
        parent::__construct($plugin, $token);

        $this->api = new ApiV2($bearer, $token);
        $this->riddleParams = [];
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
        if (-1 !== $teamId) {
            $this->riddleParams['teamId'] = $teamId;
        }
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
    public function getRiddle(int $riddleId): ?array
    {
        $riddles = $this->getRiddles();

        foreach ($riddles as $riddle) {
            if ($riddle['id'] === $riddleId) {
                return $riddle;
            }
        }

        return null;
    }

    public function getEmbedCode($params)
    {
        $embed = $this->api->getRiddleEmbedCode($params);
        
        if (substr($embed, 0, 4) != "<div") { // if the embed code doesn't start with <div, it doesn't exist
            return false;
        }

        return $embed;
    }

    public function getApi(): ApiV2
    {
        return $this->api;
    }
}