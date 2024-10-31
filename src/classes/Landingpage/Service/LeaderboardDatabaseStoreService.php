<?php

namespace src\classes\Landingpage\Service;

use Riddle\Landingpage\Module\Service\LeaderboardStoreService;

class LeaderboardDatabaseStoreService extends LeaderboardStoreService
{
    const LEADS_OPTION_NAME = 'riddle_leaderboard_leads';
    protected $calculatedMigration = false;
    protected $wrongOptionLeads;

    protected function _loadLeaderboardLeadsFromSource(): array
    {
        $option = $this->_getOption();
        $leads = json_decode($option, true) ?? [];

        $oldLeads = parent::_loadLeaderboardLeadsFromSource();
        $this->_migrateOldLeads($leads, $oldLeads);

        $wrongOptionLeads = $this->_pullLeadsFromWrongOption();
        $this->_migrateOldLeads($leads, $wrongOptionLeads);

        return $this->leads = $leads;
    }

    protected function _saveLeaderboardsFile()
    {
        if (file_exists($this->_getLeaderboardLeadsPath())) {
            \rename($this->_getLeaderboardLeadsPath(), $this->_getLeaderboardLeadsPath('_')); // rename the file so it does not get migrated again
        }

        $leadsOptionName = $this->_getOptionsName();

        if (null === $this->_getOption()) {
            \add_option($leadsOptionName, json_encode($this->leads));
        } else {
            \update_option($leadsOptionName, json_encode($this->leads));
        }

        \update_option(self::LEADS_OPTION_NAME, json_encode($this->wrongOptionLeads));
    }

    private function _pullLeadsFromWrongOption(): array
    {
        $option = \get_option(self::LEADS_OPTION_NAME, null);

        if (null === $option) {
            return [];
        }

        $leads = json_decode($option, true) ?? [];
        $foundLeads = [];
        $entries = $leads['entries'] ?? [];
        $found = 0;

        foreach ($entries as $i => $entry) {
            $riddleId = $entry['trunk']['riddle']['id'] ?? -1;

            if ($riddleId === intval($this->_getCurrentRiddleId())) {
                $found++;
                $foundLeads['entries'][] = $entry;
                unset($leads['entries'][$i]);
            }
        }

        $this->wrongOptionLeads = $leads;

        return $foundLeads;
    }

    /**
     * This function gets old leads and adds them to the news leads.
     */
    private function _migrateOldLeads(array &$leads, array $oldLeads)
    {
        if (empty($oldLeads)) {
            return;
        }

        $newKeyTable = $leads['keyTable'] ?? [];
        $entries = $oldLeads['entries'] ?? [];

        foreach ($entries as $entry) {
            $entryKey = $entry['key'];
            $keyIndex = $newKeyTable[$entryKey] ?? false;

            if (false === $keyIndex) { // not yet in the leads in general - just add the lead
                $leads['entries'][] = $entry;

                continue;
            }

            if (!$this->calculatedMigration) { // tedious work: add sums, add dates, ...
                $newEntry = $leads['entries'][$keyIndex];
                $newEntry['sumScoreNumber'] = $newEntry['sumScoreNumber'] + $entry['sumScoreNumber'];
                $newEntry['dates'] = array_merge($entry['dates'], $newEntry['dates']);
            } else { // already summed up values - it only gets loaded more than once
                $newEntry = $entry;
            }

            $leads['entries'][$keyIndex] = $newEntry;
        }

        $this->calculatedMigration = true;
        $this->refresh(); // sort leaderboard and refresh key table
    }

    public function resetLeads(int $riddleId = -1)
    {
        \delete_option($this->_getOptionsName($riddleId));

        $this->leads = [];
        $this->refresh();
    }

    private function _getCurrentRiddleId()
    {
        return $this->module->getApp()->getRiddleId();
    }

    private function _getOption()
    {
        return \get_option(self::LEADS_OPTION_NAME . '_' . $this->_getCurrentRiddleId(), null);
    }

    private function _getOptionsName(int $riddleId = -1)
    {
        $riddleId = -1 !== $riddleId ? $riddleId : $this->module->getApp()->getRiddleId();

        return self::LEADS_OPTION_NAME . '_' . $riddleId;
    }
}