<?php

class AutoLoader
{

    private static $_aDirectories = array();


    public static function registerDirectory($sDirName)
    {

        self::$_aDirectories[] = realpath(__DIR__ . DIRECTORY_SEPARATOR . $sDirName);
    }

    public static function loadClass($className)
    {

        $sRelativePath = str_replace('\\', DIRECTORY_SEPARATOR, $className) . ".php";

        foreach (self::$_aDirectories as $includeDir) {
            $sFile = $includeDir . DIRECTORY_SEPARATOR . $sRelativePath;
            //echo "Trying to load $sFile\n";
            if (file_exists($sFile)) {
                require_once($sFile);
                return;
            }

            echo "Could not find file for $className\n";
        }
    }

}

spl_autoload_register(array('AutoLoader', 'loadClass'));
