<?php


namespace net\dontdrinkandroot\database;

use \PDO;

/**
 * Manages Database Configurations and creates and manages databases on demand.
 * @package net\dontdrinkandroot\database
 */
class DatabaseManager
{

    private $configs = array();

    private $instances = array();


    public function registerDatabase($sName, DatabaseConfig $config)
    {
        $this->configs[$sName] = $config;
    }

    public function getDatabase($name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (!isset($this->configs[$name])) {
            throw new \RuntimeException("No Database with the name $name was found, you need to register it first.");
        }

        $oConfig = $this->configs[$name];
        $oDb = DatabaseFactory::createDatabase($oConfig);
        $oDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $oDb->query('set names utf8');

        $this->instances[$name] = $oDb;

        return $oDb;
    }

}