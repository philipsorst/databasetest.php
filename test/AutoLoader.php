<?php

class AutoLoader {

    private static $_aDirectories = array();


    public static function registerDirectory( $sDirName ) {

        self::$_aDirectories[] = realpath(__DIR__ . DIRECTORY_SEPARATOR . $sDirName);
    }

    public static function loadClass( $className ) {

        $sRelativePath = str_replace( '\\', DIRECTORY_SEPARATOR, $className ) . ".php";

        foreach( self::$_aDirectories as $includeDir ) {
            $sFile =  $includeDir . DIRECTORY_SEPARATOR . $sRelativePath;
            if ( file_exists( $sFile ) ) {
                require_once($sFile);
                return;
            }
        }
    }

}

spl_autoload_register(array('AutoLoader', 'loadClass'));