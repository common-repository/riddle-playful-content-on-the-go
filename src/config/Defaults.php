<?php

define("RIDDLE_URI", "https://www.riddle.com/creator");
define("RIDDLE_API_V2", RIDDLE_URI . "/api/v2");

define ("RIDDLE_PLUGIN_NAME", "Riddle-Plugin");
define ("RIDDLE_PLUGIN_VERSION", "4.6.11");
define ("RIDDLE_SHORTCODE", "rid");
define ("RIDDLE_SHORTCODE_VIEW", "rid-view");
define ("RIDDLE_SHORTCODE_CUSTOM_RESULT_PAGE", "rid-custom-page");

/**
 * These params can be used with the shortcodes
 * The constant gets serialized because PHP 5.6 (and older) doesn't support array constants
 */
define ("RIDDLE_SHORTCODE_PARAMS", 
        serialize( array("seo", "width", "maxWidth", "heightPx", "heightPc", "mode", "disableAutoScrolling") )
    );

define ('LEADERBOARD_MODES', serialize([
    'timeP' => 'Score % & time taken',
    'timeS' => 'Score sums & time taken',
    'percentage' => 'Score % only',
    'sums' => 'Score sums only',
]));