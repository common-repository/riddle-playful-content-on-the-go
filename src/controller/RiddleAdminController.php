<?php

namespace src\controller;

use Riddle\Api\Exception\ApiRequestException;
use src\classes\Controller;
use src\classes\UserSettings;

class RiddleAdminController extends Controller
{   
    public static function riddleSubpage() 
    {
        $subpage = $_GET['subpage'];

        try {
            if ('leads' === $subpage) {
                return RiddleCRPController::riddleLeaderboardEntries();
            }
    
            return RiddleCRPController::riddleCustomResultPages();
        } catch (ApiRequestException $ex) {
            return self::view('v2/error/index.php', ['error' => 'Could not load Riddle Custom Result Page Contents from the API: '.$ex->getMessage()]);
        }
    }

    public static function riddleProcessDisconnect()
    {
        $disconnect = boolval(self::_getGETValue('disconnect'));

        if ($disconnect) {
            UserSettings::disconnect();

            return self::redirectToAdminpage("riddle-admin-menu"); // Redirect to "Connect Account" page
        }
    }
}