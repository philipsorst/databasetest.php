<?php


namespace net\dontdrinkandroot\database;


/**
 * Configuration options for a SqLite Database.
 * @package net\dontdrinkandroot\database
 */
class SqLiteDatabaseConfig extends DatabaseConfig
{

    private $_sPath;


    public function __construct( $sPath )
    {
        $this->_sPath = $sPath;
    }

    public function getPath()
    {
        return $this->_sPath;
    }

    public function setPath( $sPath )
    {
        $this->_sPath = $sPath;
    }

}