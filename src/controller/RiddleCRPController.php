<?php

/**
 * Riddle custom result page manager / controller
 */

namespace src\controller;

use src\classes\UserSettings;
use src\classes\Landingpage\RiddleLandingpageManager;

class RiddleCRPController extends RiddleLeaderboardBaseController
{
    /**
     * Controller that receives all requests.
     * Its job is to redirect it to the appropiate pages and render the right views
     */
    public static function riddleCustomResultPages()
    {
        $subpage = self::_getSubpage();

        if (!$subpage) { // no subpage is selected
            return self::redirectToAdminpage('riddle-admin-menu');
        }

        $subpageParts = explode('-', $subpage);

        if (2 > count($subpageParts)) {
            return self::redirectToAdminpage('riddle-admin-menu&subpage=creator-'.$subpageParts[0].'&id=' . $_GET['id'] ?? -1);
        }

        if ('creator' === $subpageParts[0]) {
            $controller = new RiddleLeaderboardCreatorController();

            return $controller->renderCreatorView($subpageParts[1]);
        }

        return self::redirectToAdminpage('riddle-admin-menu');
    }

    public static function riddleLeaderboardEntries()
    {
        global $riddleLoader;

        $type = RiddleLandingpageManager::getTypeById($_GET['id'] ?? -1);

        if (!$type) {
            return self::redirectToAdminpage('riddle-admin-menu');
        }

        if (isset($_GET['action'])) {
            return RiddleLeaderboardLeadController::handleLeadOperation($_GET['action'], $type);
        }

        return self::view('pages/leaderboard-entries.php', [
            'type' => $type,
            'riddle' => $riddleLoader->getRiddle($type->getValue('id')),
        ]);
    }

    /**
     * This function gets loaded once WP was loaded
     */
    public static function riddleHandleLandingpageOperation()
    {
        $subpage = explode('-', self::_getSubpage())[1] ?? null;

        if (!$subpage) {
            return false;
        }

        if (!in_array($subpage, ['create', 'edit', 'leads', 'leaderboard']) || !isset($_POST['riddle_type_sent'])) {
            return false;
        }
        
        $type = self::_getTypeFromQuery($subpage);

        if (!$type) {
            return false; // invalid type; just skip this action
        }

        $id = $type->update();
        RiddleLandingpageManager::save();
        $redirectSubpage = 'create' !== $subpage ? $subpage : 'edit'; // do not redirect to create again => infinite loop

        return self::redirectToAdminpage('riddle-admin-menu&subpage=creator-'.$redirectSubpage.'&id=' . $id); // back to the edit page
    }

    public static function riddleHandleLandingpageDeletion()
    {
        $subpage = self::_getSubpage();

        if ('delete' !== $subpage) {
            return false;
        }

        $type = self::_getTypeFromQuery($subpage);

        if (!$type) {
            return false;
        }

        $id = isset($_GET['id']) ? $_GET['id'] : -1;
        RiddleLandingpageManager::delete($id);

        return self::redirectToAdminpage('riddle-admin-menu'); // back to the CRP list
    }
}