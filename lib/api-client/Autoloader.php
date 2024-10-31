<?php

namespace Riddle\Api;

class Autoloader
{
    public static function loadClass($className)
    {
        if (class_exists($className)) {
            return false;
        }

        $className = str_replace('\\', '/', $className);
        $className = str_replace('Riddle/Api', '', $className);
        $classPath = __DIR__ . '/' . $className . '.php';

        if (file_exists($classPath)) {
            require_once($classPath);

            return true;
        }

        return false;
    }
}
