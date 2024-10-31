<?php

namespace src\classes\Landingpage;

use src\classes\Landingpage\Type\LandingpageType;
use src\classes\Landingpage\Type\LeaderboardType;

class RiddleLandingpageManager
{

    private static $LANDINGPAGES_ARRAY_OPTION = 'riddle_landingpages_array';
    private static $LANDINGPAGES_ID_COUNTER_OPTION = 'riddle_landingpages_id_counter';

    private static $idCounter = 0;
    private static $landingpages = false;

    public static function isLoaded()
    {
        return self::$landingpages !== false;
    }

    public static function load($forceLoad = false)
    {
        if (!$forceLoad && self::isLoaded()) {
            return self::$landingpages;
        }

        self::$landingpages = \json_decode(\get_option(self::$LANDINGPAGES_ARRAY_OPTION), true);

        if (!self::$landingpages) {
            self::$landingpages = [];
        }

        self::$idCounter = \get_option(self::$LANDINGPAGES_ID_COUNTER_OPTION, 0);

        return self::$landingpages;
    }

    public static function update(LandingpageType $type)
    {
        self::load();
        $id = $type->getId();
        
        if (-1 === $id) {
            $id = self::_increaseIdCounter();
        }

        self::$landingpages[$id] = [
            'type' => $type->getType(),
            'values' => $type->getValues(),
        ];

        return $id;
    }

    public static function save()
    {
        self::load();

        \update_option(self::$LANDINGPAGES_ARRAY_OPTION, json_encode(self::$landingpages));
    }

    public static function delete(int $id)
    {
        self::load();

        if (!isset(self::$landingpages[$id])) {
            return false;
        }

        unset(self::$landingpages[$id]);
        self::save();
    }

    private static function _increaseIdCounter()
    {
        self::load();

        self::$idCounter++;
        \update_option(self::$LANDINGPAGES_ID_COUNTER_OPTION, self::$idCounter);

        return self::$idCounter;
    }

    public static function getTypeById(int $id) 
    {
        self::load();

        if (!isset(self::$landingpages[$id])) {
            return null;
        }

        $typeDetails = self::$landingpages[$id];
        $type = self::getTypeInstance($typeDetails['type'], $id);
        $type->setValues($typeDetails['values']);

        return $type;
    }

    public static function getTypeByRiddleId(int $riddleId)
    {
        foreach (self::getLandingpages() as $page) {
            if($page['values']['id'] === $riddleId) {
                return $page;
            }
        }

        return null;
    }

    public static function getLandingpages()
    {
        return self::load();
    }

    public static function getLandingpagesRiddleIdArray()
    {
        $pages = [];

        foreach (self::getLandingpages() as $id => $page) {
            $pages[$page['values']['id']] = self::getTypeById($id);
        }

        return $pages;
    }

    /**
     * Get a landingpage type by string
     */
    public static function getTypeInstance(string $type, int $id = -1)
    {
        if ('leaderboard' === $type) {
            return new LeaderboardType($id);
        }

        return null;
    }

}