<?php

namespace src\controller;

use DateTime;
use src\classes\Landingpage\RiddleLandingpageManager;
use src\classes\Landingpage\Type\LeaderboardType;

class RiddleLeaderboardLeadController extends RiddleLeaderboardBaseController
{
    public static function handleLeadOperation(string $operation, LeaderboardType $type)
    {
        if (!in_array($operation, ['reset'])) {
            return self::_redirectToLeadsOverview();
        }

        return self::{$operation . 'LeaderboardLeads'}($type);
    }
    public static function resetLeaderboardLeads(LeaderboardType $type)
    {
        $storeService = $type->getLeaderboardHandler()->getApp()->getLeaderboardModule()->getStoreService();
        $storeService->resetLeads($type->getValue('id'));

        return self::redirectToAdminpage('riddle-admin-menu&subpage=leads&id=' . $type->getId());
    }

    public static function handleLeaderboardLeadsDownload()
    {
        if ('leads' !== ($_GET['subpage'] ?? '') || 'download' !== ($_GET['action'] ?? '')) {
            return false;
        }

        $type = RiddleLandingpageManager::getTypeById($_GET['id'] ?? -1);

        if (!$type) {
            return false;
        }

        $app = $type->getLeaderboardHandler()->getApp();
        $riddleId = $app->getRiddleId();
        $storeService = $app->getLeaderboardModule()->getStoreService();
        $leads = $storeService->getLeaderboardLeads();

        header(sprintf('Content-disposition: attachment; filename=leaderboard-leads-%s.json', $riddleId));
        header('Content-Type: application/json');
        die(json_encode($leads));
    }

    private static function _getType()
    {
        $type = self::_getTypeFromSubpage('edit');

        if (!$type) {
            return self::redirectToAdminpage("riddle-admin-menu");
        }

        return $type;
    }

    private static function _redirectToLeadsOverview()
    {
        return self::redirectToAdminpage('riddle-admin-menu&subpage=creator-edit&id=' . self::_getType()->getId());
    }
}