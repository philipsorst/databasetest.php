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

        $config = $this->configs[$name];
        $database = DatabaseFactory::createDatabase($config);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->instances[$name] = $database;

        return $database;
    }

}