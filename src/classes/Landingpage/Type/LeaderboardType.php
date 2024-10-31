<?php

namespace src\classes\Landingpage\Type;

use src\classes\Landingpage\Service\LeaderboardDatabaseStoreService;
use src\classes\Landingpage\RiddleLeaderboardPluginHandler;

class LeaderboardType extends LandingpageType
{

    protected $type = 'leaderboard';

    public function getOptions()
    {
        return array_merge([
            'id',
            'slug',
        ], array_keys($this->getDefaultValues()));
    }

    public function getLeaderboardConfigProperties()
    {
        return [
            'viewsPath' => RIDDLE_PLUGIN_PATH . '/src/views/leaderboard-views',
            'styleSheetsPath' => RIDDLE_PLUGIN_PATH . '/public/css',
            'fieldsDelimiter' => '<!>',
        ];
    }

    public function getLeaderboardHandler(bool $acceptData = true, bool $preview = false)
    {
        $handler = new RiddleLeaderboardPluginHandler($this, $this->getValue('id'), $acceptData, $preview);
        $handler->getApp()->setRiddleId($this->getValue('id'));
        $leaderboardModule = $handler->getApp()->getLeaderboardModule();
        $leaderboardModule->setStoreService(new LeaderboardDatabaseStoreService($leaderboardModule)); // saving leads in the database

        return $handler;
    }

    public function render(bool $acceptData = true, bool $preview = false)
    {
        return $this->getLeaderboardHandler($acceptData, $preview)->start();
    }

    public function getLeadfieldNames()
    {
        $names = json_decode(htmlspecialchars_decode($this->getValue('leadfieldNames')), true);

        if (is_array($names)) { // to support the old format
            return $names;
        }
        
        return explode(',', $this->getValue('leadfieldNames'));
    }

    public function getLeadfieldLabels()
    {
        $rawLabels = stripslashes(html_entity_decode($this->getValue('leadfieldLabels')));

        return json_decode($rawLabels, true);
    }

    public function getLeadFields()
    {
        $rawFields = $this->getLeadfieldNames();
        $leadFields = [];

        //remap some fields for easier use ('percentage' is easier to type than 'resultData.scorePercentage')
        $fieldAliases = [
            'percentage' => 'resultData.scorePercentage',
            'time' => 'trunk.timeTaken',
            'latestScore' => 'latestScoreNumber',
            'scoreSum' => 'sumScoreNumber',
        ];

        foreach ($rawFields as $rawField) {
            if (isset($fieldAliases[$rawField])) {
                $rawField = $fieldAliases[$rawField];
            } else if ('index' !== $rawField) {
                $rawField = 'lead2.' . $rawField . '.value';
            }

            $leadFields[] = $rawField;
        }

        return $leadFields;
    }

    public function getLeadTableHead()
    {
        $rawFields = $this->getLeadfieldNames();
        $labels = $this->getLeadfieldLabels();

        $leadHead = [];

        foreach ($rawFields as $rawField) {
            if (isset($labels[$rawField]) && '' !== $labels[$rawField]) {
                $rawField = $labels[$rawField];
            } else if ('percentage' === $rawField) {
                $rawField = 'Score';
            } else if ('index' === $rawField) {
                $rawField = '#';
            }

            $leadHead[] = $rawField;
        }

        return $leadHead;
    }

    /**
     * add the lead fields to the values so the template can use them
     */
    public function getValues(): array
    {
        return array_merge(
            parent::getValues(),
            [
                'leadFields' => $this->getLeadFields(),
                'leadHead' => $this->getLeadTableHead(),
            ]
        );
    }

    public function getLeads()
    {
        return $this->getLeaderboardHandler()->getApp()->getLeaderboardModule()->getStoreService()->getLeaderboardLeads();
    }

    public function hasLeads(): bool
    {
        $leads = $this->getLeads();

        return $leads && is_array($leads) && !empty($leads);
    }

    public function getDefaultValues(): array
    {
        return [
            'leaderboardMode' => 'percentage',
            'leadfieldNames' => 'index,percentage',
            'leadfieldNamesOrder' => '',
            'leadfieldLabels' => '',
            'amountEntries' => 10, // display 10 entries by default

            'leaderboardPictureURL' => 'null', // default: no image
            'leaderboardPictureAlt' => 'Leaderboard Image',

            'yourScoreText' => 'Your Score: ',

            'leaderboardHeading' => 'LEADERBOARD',
            'leaderboardText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',

            'betterThanMin' => 20,
            'betterThanTemplate' => 'You did better than %%PERCENTAGE%%%!',

            'placementTemplate' => 'Your ranking: %%PLACEMENT%% out of %%TOTAL%%',

            'missedPlaceTemplate' => 'You\'ve missed out on the top 10 by %%PERCENTAGE%%% - try again to be one of the best.',
            'missedPlaceIndexTemplate' => 10,

            'emptyMessage' => 'No entries yet. Take the quiz to enter now!',
        ];
    }

}