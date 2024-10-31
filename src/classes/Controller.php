<?php

namespace src\classes;

class Controller 
{
    public static function view(string $view, $data = null) 
    {
        $viewPath = RIDDLE_PLUGIN_PATH . "/src/views/$view";

        if(!file_exists($viewPath)) {
            die('VIEW does not exist: ' . $viewPath);
            return false;
        }

        return require $viewPath;
    }

    public static function redirectToAdminpage(string $page) 
    {
        \wp_redirect(self::getAdminUrl($page));
        exit();
    }

    public static function getAdminUrl(string $page)
    {
        return \admin_url('admin.php?page=' . $page);
    }

    protected static function _getSubpage(string $getName = 'subpage')
    {
        return self::_getGETValue($getName);
    }

    protected static function _getGETValue(string $getName, $default = null)
    {
        return $_GET[$getName] ?? $default;
    }

    protected static function _getPOSTValue($postName, $filter = true)
    {
        if (!isset($_POST[$postName])) {
            return null;
        }

        $value = trim($_POST[$postName]);
        
        return $filter ? filter_var($value, FILTER_SANITIZE_STRING) : $value;
    }
}