<?php

/**
 * This class adds the riddle custom result page shortcode
 * Format: [rid-custom-page id={YOUR_ID}]
 */

namespace src\classes\Landingpage;

class RiddleLandingpageLoader
{
    
    public static function addShortcodeFilter()
    {
        \add_shortcode(RIDDLE_SHORTCODE_CUSTOM_RESULT_PAGE, 'src\classes\Landingpage\RiddleLandingpageLoader::getShortCodeCustomResultPage');
    }

    public static function getShortcodeCustomResultPage($attributes)
    {
        if (isset($attributes[0])) { // Format [rid-custom-page=ID]
            $pageId = str_replace("=", "", $attributes[0]); 
        } else if(isset($attributes["id"])) {  // Format [rid-custom-page id=ID]
            $pageId = $attributes["id"];
        } else {
            return 'Please use the following shortcode structure: [' . RIDDLE_SHORTCODE_CUSTOM_RESULT_PAGE . ' id={YOUR_ID}]';
        }

        if (!is_numeric($pageId)) { // the riddle id has to be numeric
            return $pageId . ' is not numeric, please enter a valid ID for the custom result page shortcode.';
        }

        RiddleLandingpageManager::load();
        $type = RiddleLandingpageManager::getTypeById($pageId);

        if (!$type) {
            return 'The page with the ID ' . $pageId . ' does not exist. Please enter a valid ID for the custom result page shortcode.';
        }

        return $type->render();
    }
    
    private static function _retrieveFromUrl($url)
    {
        $request = \wp_remote_get($url);

        return \wp_remote_retrieve_body($request);
    }

}