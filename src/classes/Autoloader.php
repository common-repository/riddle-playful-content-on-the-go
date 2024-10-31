<?php

namespace src\classes;

class Autoloader
{
    public static function wordpressRiddleAutoload($class) 
    {
        $class = str_replace("\\", "/", $class); // src\classes => src/classes
        $classesPath = RIDDLE_PLUGIN_PATH . "/$class.php";
        
        if (!file_exists($classesPath)) { // If the file doesn't exist => exit
            return false;
        }

        require_once( $classesPath );
    }
}