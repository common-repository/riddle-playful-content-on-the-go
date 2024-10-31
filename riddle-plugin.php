<?php
/*
Plugin Name: Riddle Quiz Maker
Plugin URI: https://wordpress.org/plugins/riddle-quiz-maker/
Description: Collect leads and engage your audience with 15 types of quizzes, personality tests, surveys, and more on Riddle.com - then use our plug-in to easily embed them into any page or post.
Version: 4.6.11
Author: Riddle Technologies AG
Author URI: https://www.riddle.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

require dirname(__FILE__) . '/src/init.php';

$riddlePlugin = new src\classes\RiddlePlugin();

$riddleCredentials = src\classes\UserSettings::loadCredentials();
$riddleApiKey = $riddleCredentials['apikey'];
$riddleBearer = $riddleCredentials['bearer'];
$accessToken = $riddleCredentials['accessToken'];

/**
 * This plugin supports both Riddle API versions.
 */
if (null !== $riddleBearer) {
    $riddleLoader = new src\Api\V1\RiddleLoader($riddleApiKey, $riddleBearer);
} else {
    $riddleLoader = new src\Api\RiddleLoaderV2();
}

$riddlePlugin->run();

// === activate / deactivate hooks ===

\register_activation_hook(__FILE__, 'activatePlugin');
\register_deactivation_hook(__FILE__, 'deactivatePlugin');

function activatePlugin() {
    src\classes\UserSettings::addDefaults();
}

function deactivatePlugin() {
    if (src\Api\RiddleLoaderV2::isAuthorized()) {
        try {
            $riddleLoaderV2 = src\Api\RiddleLoaderV2::getLoader();
            $riddleLoaderV2->getAPIClient()->accessToken()->revoke(); // this removes the access token from the Riddle.com database, rendering it useless.
        } catch (\Throwable $ex) {} // the token might have been revoked already, so we can ignore this exception.
    }

    try {
        src\classes\UserSettings::destroySettings();
    } catch (\Throwable $ex) {}
}