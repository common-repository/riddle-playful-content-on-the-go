<?php

namespace src\controller;

use src\classes\Controller;
use src\classes\Landingpage\RiddleLandingpageManager;
use src\classes\Landingpage\Type\LeaderboardType;

class RiddleLeaderboardBaseController extends Controller
{
    protected static function _insertParamsFromQuery(LeaderboardType &$type)
    {
        if (isset($_GET['riddleId'])) {
            $type->setValue('id', intval($_GET['riddleId']));
        }

        if (isset($_GET['slug'])) {
            $type->setValue('slug', urldecode($_GET['slug']));
        }
    }

    protected static function _getTypeFromSubpage(string $subpage): ?LeaderboardType
    {
        if ('create' === $subpage) {
            $typeName = self::_getGETValue('type');

            return RiddleLandingpageManager::getTypeInstance($typeName);
        }

        if (in_array($subpage, ['edit', 'leaderboard', 'leads'])) {
            $pageId = self::_getGETValue('id');
            
            return RiddleLandingpageManager::getTypeById($pageId);
        }

        return null;
    }

    protected static function _getTypeFromQuery(string $subpage): ?LeaderboardType
    {
        if (isset($_GET['type']) && 'create' === $subpage) {
            $typeName = self::_getGETValue('type');
            
            return RiddleLandingpageManager::getTypeInstance($typeName);
        }

        if (isset($_GET['id'])) {
            return RiddleLandingpageManager::getTypeById($_GET['id']);
        }

        return null;
    }
}