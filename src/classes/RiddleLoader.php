<?php

namespace src\classes;

class RiddleLoader
{

    private $plugin;

    /**
     * type: string, token from Riddle
     */
    public $token;

    /**
     * type: array, API delivers the data
     */
    public $teams, $riddles;

    /**
     * type: string, GET parameter for the RiddleItems of a given Team-ID
     *      
     * */
    public $activeTeam = "";

    /**
     * Constructor of the RiddleLoader
     * 
     * @param $token API token from Riddle (source: https://www.riddle.com/creator/plugins)
     */
    public function __construct(RiddlePlugin $plugin, $token) {
        $this->plugin = $plugin;
        $this->token = $token;
        $this->teams = [];
    }

    /**
     * Get all teams of a riddle account
     * 
     * @return array Array which contains all teams; Arraykey = TeamID, Value = Teamname 
     */
    public function getTeamlist()
    {
        // Get teams of riddle; returns JSON
        $response = self::_retrieveFromUrl(RIDDLE_API . "team/get-list?token=" . $this->token);
        $teamList = json_decode($response, true);

        if (!$teamList) {  // if the JSON decode process went wrong
            return false;
        }

        // create an array for the teams => easier
        foreach ($teamList as $team)  {
            $this->teams[$team["id"]] = $team["name"];
        }

        return $this->teams;
    }

    /**
     * Setting a team as an active team (=> shows only riddle teams with this specific team id)
     * 
     * @param $teamId int ID of the team
     */
    public function setActiveTeam($teamId)
    {
        $this->activeTeam = $teamId ? '&teamId=' . $teamId : '';
    }

    /**
     * Get all riddles from an account
     * @return array array with riddles
     */
    public function getRiddles()
    {
        $response = self::_retrieveFromUrl(RIDDLE_API . "riddle/get-list?token=" . $this->token . $this->activeTeam);
        $array = json_decode($response, true);
        
        if (!$array) { // if the json decode process went wrong
            return false;
        }

        $this->riddles = $array["items"];

        return $this->riddles;
    }

    public function getEmbedCode($params)
    {
        $url = RIDDLE_API . "riddle/get-embed-code?token=" . $this->token  . "&secret=" . $this->activeTeam . "&" . http_build_query($params);
        $embed = self::_retrieveFromUrl($url);

        if (substr($embed, 0, 4) !== "<div") { // if the embed code doesn't start with <div, it doesn't exist
            return false;
        }

        return $embed;
    }

    public function addRiddleBlock()
    {
        $script = $this->plugin->addScript('https://cdn.riddle.com/website/wp-plugin/js/riddle-gutenberg-block.js', false, array( 'wp-blocks', 'wp-element', 'wp-editor' ), false);
        \register_block_type('riddle-plugin/riddle-gutenberg-block', ['editor_script' => $script]);
    }

    /**
     * Adds the wordpress shortcode filter
     * Makes it easier for all of us to include a riddle to your site!
     */
    public function addShortcodeFilter()
    {
        $callable = array($this, "getShortcodeFilter");
        \add_shortcode(RIDDLE_SHORTCODE, $callable);
        \add_shortcode(RIDDLE_SHORTCODE_VIEW, $callable);
    }

    public function getShortcodeFilter($attributes)
    {
        /**
         * the 0th element exists if the user has entered following structure:
         * [rid=ID]
         * the 0th Element is =ID. So you have to cut "=" away and continue processing the ID :)
         */

        if (isset($attributes[0])) {
            $riddleId = str_replace("=", "", $attributes[0]);
        } else if(isset($attributes["id"])) {  // Format [rid id=ID]
            $riddleId = $attributes["id"];
        } else {
            return "Something went wrong. Please try again in a few seconds.";
        }

        if (!is_numeric($riddleId)) { // the riddle id has to be numeric
            return $riddleId . ' is not numeric';
        }
        
        $params = array( "riddleId" => $riddleId );

        // Serialize instead of normal arrays to support PHP < 5
        foreach (unserialize(RIDDLE_SHORTCODE_PARAMS) as $param) {
            $paramName = strtolower($param);
            $params[$param] = isset($attributes[$paramName]) ? $attributes[$paramName] : "";
        }
        
        if ($params["mode"] === "") { // SPECIAL CASE
            $params["mode"] = "dynamic"; // If the mode is equals to "", nothing will be displayed! :(
        }

        $embedCode = $this->getEmbedCode($params);
        if (!$embedCode) {
            return "Something went wrong. The riddle you requested doesn't exist.";
        }

        return $embedCode;
    }
    
    private static function _retrieveFromUrl($url)
    {
        $request = \wp_remote_get($url);

        return \wp_remote_retrieve_body($request);
    }

}