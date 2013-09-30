<?php


namespace net\dontdrinkandroot\database;


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