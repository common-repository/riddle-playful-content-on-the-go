<?php

namespace src\controller;

use Riddle\Api\Exception\ApiException;
use Riddle\Api\Exception\ApiRequestException;
use src\Api\RiddleLoaderV2;
use src\Api\V1\RiddleLoader;
use src\classes\Controller;
use src\classes\UserSettings;

/**
 * V2 Plugin Controller which is used to handle all Riddle admin actions.
 */
class AdminController extends Controller
{
    /**
     * Gets a stateless Riddle Loader. Should only be used in non-authenticated contexts.
     * Otherwise use RiddleLoaderV2::getRiddleLoader() to make it more reliable by returning null when the plugin is unauthorized.
     */
    public static function getRiddleLoader(): RiddleLoaderV2
    {
        return new RiddleLoaderV2();
    }

    public static function riddleList() 
    {
        $selectedTeamId = UserSettings::getSelectedTeam(null); // return 'null' if no team is selected

        if (isset($_GET['subpage'])) {
            return RiddleAdminController::riddleSubpage(); // redirect to Legacy Controller to continue the support of leaderboards and all that stuff
        }

        $showV2Riddles = RiddleLoaderV2::getLoader()->shouldShowV2Riddles();

        try {
            $client = RiddleLoaderV2::getLoader()->getAPIClient();
            $riddles = $showV2Riddles ? $client->riddle()->list($selectedTeamId, null, null, null, null, 'modified', 'DESC') : $client->riddleV1()->list($selectedTeamId);
            $projects = $client->project()->list();
        } catch (ApiException $ex) {
            return self::view('v2/error/index.php', ['error' => 'Could not load riddles & teams from the API: '.$ex->getMessage()]);
        }

        return self::view('v2/riddle/list.php', [
            'teams' => $projects,
            'riddles' => $riddles,
            'selectedTeam' => $selectedTeamId,
            'showV2Riddles' => $showV2Riddles,
        ]);
    }

    /**
     * Page responsible for initiating the OAuth flow.
     */
    public static function connect()
    {
        if (null !== $error = static::_getGETValue('error')) {
            return self::view('v2/error/index.php', ['error' => $error]);
        }

        $redirectUri = \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu');
        $authUrl = static::getRiddleLoader()->getAPIClient()->oauth()->getAuthUrl($redirectUri);

        return self::view('v2/connect/index.php', [
            'hasOldToken' => !static::getRiddleLoader()->isAuthorized() && RiddleLoader::isAuthorized(), // if new Riddle Loader is unauthorized & the old Riddle loader is authorized - display warning with an alert to reconnect again
            'authUrl' => $authUrl,
        ]);
    }

    public static function help() 
    {
        return self::view('pages/help.php');
    }

    // == PROCESS FUNCTIONS
    // These functions basically run everytime a Riddle Admin Page loads and they all process different things, such as saving the selected team, processing the OAuth code and all that stuff.

    /**
     * This action listens to the redirect from the Riddle OAuth flow.
     * If the request contains a code in the query we try to fetch a new access token.
     */
    public static function processConnectOauthCallbackCode()
    {
        if (null !== $code = static::_getGETValue('code')) {
            try {
                $accessToken = static::getRiddleLoader()->getAPIClient()->oauth()->fetchAccessToken($code);
            } catch (\Exception $ex) {
                return self::redirectToAdminpage("riddle-admin-menu&error=".\urlencode($ex->getMessage())); // Redirect to "Connect Account" page
            }

            UserSettings::destroySettings(); // destroy all settings before setting the new access token - this makes sure we delete old V1 tokens as well
            UserSettings::saveAccessToken($accessToken);

            return self::redirectToAdminpage("riddle-admin-menu"); // Redirect to "My Riddles" page after fetching the access token successfuly
        }
    }

    /**
     * This action listens to the disconnect query value.
     * If the right condition is given the access token gets revoked and removed from the WP database.
     */
    public static function processDisconnect()
    {
        $disconnect = boolval(self::_getGETValue('disconnect'));

        if ($disconnect) {
            RiddleLoaderV2::getLoader()->disconnect(); // revokes the access token + deletes the user option from the WordPress database

            return self::redirectToAdminpage("riddle-admin-menu"); // Redirect to "Connect Account" page where the user can reconnect
        }
    }

    /**
     * Updates all the values a user could change on the Riddle List.
     * At the moment: Selected Team + whether to show 2.0 or 1.0 Riddles.
     */
    public static function processUserValues(): void
    {
        if (isset($_POST['submitted'])) { // the hidden form field lets us better detect whether the form was submitted
            if (isset($_POST['team'])) {
                $teamId = self::_getPOSTValue('team', false);
    
                if ($teamId) {
                    UserSettings::setSelectedTeam($teamId);
                } elseif($teamId === '') {
                    UserSettings::removeSelectedTeam();
                }
            }
        }

        if (null !== $apiVersion = $_GET['apiVersion'] ?? null) {
            $showV2 = \intval($apiVersion) === 2;
            RiddleLoaderV2::getLoader()->showV2Riddles($showV2);
        }
    }
}