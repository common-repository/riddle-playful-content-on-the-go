<?php

namespace src\classes\Landingpage;

/**
 * This class prevents non-logged in users from accessing the leaderboard
 */

class RiddleLeaderboardPreviewHandler
{

    private $riddleIds;

    public function __construct($startSession = false)
    {
        if ($startSession && session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->_load();
    }

    public function isAllowed(int $riddleId)
    {
        return in_array($riddleId, $this->riddleIds);
    }

    public function addRiddleId(int $riddleId)
    {
        if ($this->isAllowed($riddleId)) {
            return false;
        }

        $this->riddleIds[] = $riddleId;
        $this->_save();
    }

    private function _load()
    {
        $riddleIds = isset($_SESSION['allowedRiddleIds']) ? json_decode($_SESSION['allowedRiddleIds'], true) : null;
        $this->riddleIds = $riddleIds ? $riddleIds : [];
    }

    private function _save()
    {
        $_SESSION['allowedRiddleIds'] = json_encode($this->riddleIds);
    }

}