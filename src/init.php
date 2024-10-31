<?php

/**
 * Load all the dependencies and setup the auoloader.
 */

define ("RIDDLE_PLUGIN_PATH", dirname(dirname(__FILE__)));

// the leaderboard view gets rendered without wordpress functions - let's avoid calling wordpress methods in this case
if (function_exists('plugins_url')) {
    define ("RIDDLE_URL_PATH", plugins_url() . '/' . basename(dirname(dirname(__FILE__))));
    define ("RIDDLE_IMAGE_PATH", RIDDLE_URL_PATH . '/public/images');
    define ("RIDDLE_CSS_PATH", RIDDLE_URL_PATH . '/public/css');
}

require RIDDLE_PLUGIN_PATH . '/src/config/Defaults.php';
require RIDDLE_PLUGIN_PATH . '/src/config/RiddleMenu.php';

require RIDDLE_PLUGIN_PATH . '/src/classes/Autoloader.php'; // Setup Autoloader
spl_autoload_register('src\classes\Autoloader::wordpressRiddleAutoload');

require RIDDLE_PLUGIN_PATH . '/lib/api-client/Autoloader.php'; // Setup Autoloader
spl_autoload_register('Riddle\Api\Autoloader::loadClass');

require RIDDLE_PLUGIN_PATH . '/lib/leaderboard/src/init.php'; // load leaderboard module