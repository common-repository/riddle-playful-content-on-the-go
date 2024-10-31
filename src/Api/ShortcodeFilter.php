<?php

namespace src\Api;

use Exception;

class ShortcodeFilter
{
    public const TRANSIENT_NAME_PREFIX = 'rid_embedCode_';
    public const RIDDLE_SHORTCODE = 'rid';
    public const RIDDLE_SHORTCODE_VIEW = 'rid-view';

     /**
     * Adds the wordpress shortcode filter
     * Makes it easier for all of us to include a riddle to your site!
     */
    public function init()
    {
        $callable = [$this, "getShortcodeFilter"];
        \add_shortcode(self::RIDDLE_SHORTCODE, $callable);
        \add_shortcode(self::RIDDLE_SHORTCODE_VIEW, $callable);
    }

    public function getShortcodeFilter($attributes)
    {
         // Either
         //   1. [rid=ID] (saved in the first attribute element)
         //   2. [rid id=ID] (saved in the 'id' attribute key)
         if (isset($attributes[0])) {
            $riddleId = str_replace('=', '', $attributes[0]);
         } elseif (isset($attributes['id'])) {
            $riddleId = $attributes['id'];
         } else {
            return 'Malformed Riddle shortcode; could not extract Riddle ID. Please use [rid=XXX] or [rid id=XXX].';
        }

        // Serialize instead of normal arrays to support PHP < 5
        foreach (unserialize(RIDDLE_SHORTCODE_PARAMS) as $param) {
            $paramName = strtolower($param);
            $params[$param] = isset($attributes[$paramName]) ? $attributes[$paramName] : "";
        }
        
        if ($params["mode"] === "") { // SPECIAL CASE
            $params["mode"] = "dynamic"; // If the mode is equals to "", nothing will be displayed! :(
        }

        $embedCode =  $this->getCachedEmbedCode($riddleId, $params);

        if (null === $embedCode) {
            return 'Something went wrong. The riddle you requested does not exist. If this problem persists please try reconnecting to Riddle.com.';
        }

        return $embedCode;
    }

    public function getCachedEmbedCode($riddleId, array $params)
    {
        if (false !== $embedCode = \get_transient(self::TRANSIENT_NAME_PREFIX.$riddleId)) {
            return $embedCode;
        }

        $riddleLoader = $this->getRiddleLoader();

        if (!$riddleLoader::isAuthorized()) {
            return 'Error! Please connect your Riddle.com again to embed Riddles on WordPress pages.';
        }

        try {
            $embedCode = $riddleLoader->getEmbedCode($riddleId, $params);
        } catch (Exception $ex) {
            return \sprintf('Could not load Embed Code for Riddle %s: %s', $riddleId, $ex->getMessage());
        }

        \set_transient(self::TRANSIENT_NAME_PREFIX.$riddleId, $embedCode, HOUR_IN_SECONDS); // if the embed code was found cache it for one hour

        return $embedCode;
    }

    public function getRiddleLoader(): RiddleLoaderInterface
    {
        if (RiddleLoaderV2::isAuthorized()) {
            return new RiddleLoaderV2();
        }

        global $riddleLoader;

        return $riddleLoader;
    }
}