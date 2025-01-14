<?php

/**
 * @since 1.0
 */

namespace Riddle\Tools;

use Riddle\Exception\FileNotFoundException;

class RiddleTools
{

    /**
     * @param (string) $innerhtml e.g. lead2.Name.value => searches the array for this element
     */
    public static function getArrayElementFromInnerHtml(string $innerHtml, array $data, string $delimiter = '.')
    {
        $arrayPathElements = explode($delimiter, trim($innerHtml)); // e.g. lead.Name

        foreach ($arrayPathElements as $pathKey) {
            $pathKey = trim(strip_tags($pathKey));
            
            if (!is_array($data) || !array_key_exists($pathKey, $data)) {
                return $innerHtml;
            }

            $data = $data[$pathKey];
        }

        return $data;
    }

    public static function saveFile(string $path, $contents)
    {
        if (file_exists($path) && !\is_writable($path)) {
            throw new \Exception('Can\'t save the lead because the data file is not writable (path: ' . $path . ')');
        }

        $fp = fopen($path, 'w');
        fwrite($fp, $contents);
        fclose($fp);
    }

    public static function getViewPath($app, $view, $extension = 'php')
    {
        return $app->getConfig()->getProperty('viewsPath') . '/' . $view . '.' . $extension;
    }

    public static function getEverythingInTags($string, $startTag = '{', $endTag = '}')
    {
        preg_match_all('/\\' . $startTag . '(.*?)\\' . $endTag . '/', $string, $matches); // get everything in { XYZ }

        if (!$matches) {
            return [];
        }

        return $matches;
    }

    public static function getRenderedViewPath($app, $view)
    {
        return $app->getConfig()->getProperty('rendereredViewsPath') . '/' . $view . '.' . $extension;
    }

    public static function getDataFilePath($app, $fileName)
    {
        return $app->getConfig()->getProperty('dataPath') . '/' .$fileName;
    }

    public static function getViewContents(string $viewPath, $data = [])
    {
        if (!file_exists($viewPath)) {
            throw new FileNotFoundException('Can\'t get the view contents because the view does not exist (path: ' . $viewPath . ')');
        }

        ob_start();
        require $viewPath;

        return ob_get_clean();
    }

    public static function now(string $format = 'Y-m-d H:i:s')
    {
        return (new \DateTime())->format($format);
    }
}
