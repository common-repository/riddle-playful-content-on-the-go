<?php

namespace src\classes\Landingpage;

use Riddle\Core\RiddleLeaderboardHandler;
use Riddle\Landingpage\RiddleInjectedData;
use Riddle\Landingpage\RiddleData;
use src\classes\Landingpage\RiddleRandomLeadsGenerator;
use src\classes\Landingpage\Type\LeaderboardType;

class RiddleLeaderboardPluginHandler extends RiddleLeaderboardHandler
{

    private $leaderboard;
    private $randomLeadsGenerator;
    private $preview;

    private $randomLeadsCount = 20;
    private $leads;
    private $randomLead;

    public function __construct(LeaderboardType $leaderboard, int $riddleFallbackId = -1, bool $acceptData = true, bool $preview)
    {
        $this->leaderboard = $leaderboard;
        $this->preview = $preview;

        if ($preview) {
            $this->randomLeadsGenerator = $this->_getRiddleRandomLeadsGenerator();
            $this->randomLeadsGenerator->prepareLeads($this->randomLeadsCount);
            $this->randomLead = new RiddleData(json_decode(json_encode($this->randomLeadsGenerator->getLeadAt(13)))); // json_decode & encode to get a StdClass from an array
        }

        parent::__construct($riddleFallbackId, $acceptData);

        if ($preview) {
            $this->app->setData($this->randomLead);
        }
    }

    /**
     * Overriding!
     */
    public function loadUserConfig()
    {
        $props = $this->leaderboard->getLeaderboardConfigProperties();
        $values = $this->leaderboard->getValues();
        $this->app->getConfig()->addProperties(array_merge($values, $props));
    }

    /**
     * Skip the secret.
     */
    protected function _authenticate()
    {
        return true;
    }

    /**
     * Inject the leaderboard values into the leaderboard to render the values the user entered
     */
    protected function _getRenderer($riddleData)
    {
        $renderer = parent::_getRenderer($riddleData);
        $renderer->injectData($this->_getInjectedData());

        return $renderer;
    }

    protected function _getRiddleData()
    {
        if ($this->preview) {
            return $this->randomLead;
        }

        return parent::_getRiddleData();
    }

    private function _getInjectedData()
    {
        $injectedData = new RiddleInjectedData(
            $this->leaderboard->getValues(), 
            $this->leaderboard->getDefaultValues()
        );

        return $injectedData;
    }

    public function isPreview()
    {
        return $this->preview;
    }

    public function getEntries()
    {
        if (!$this->preview || null === $this->randomLeadsGenerator) {
            return null;
        }

        return $this->randomLeadsGenerator->getLeaderboardLeads();
    }

    public function getLeads()
    {
        if (!$this->preview|| null === $this->randomLeadsGenerator) {
            return null;
        }

        return $this->randomLeadsGenerator->getLeads();
    }

    private function _getRiddleRandomLeadsGenerator()
    {
        $leadfieldNames = $this->leaderboard->getValue('leadfieldNames');
        $leadfieldNames = is_array($leadfieldNames)
            ? $leadfieldNames
            : explode(',', $leadfieldNames);

        return new RiddleRandomLeadsGenerator(
            $this->leaderboard->getValue('id'), 
            $this->leaderboard->getValue('slug'), 
            $leadfieldNames
        );
    }

}