<?php


namespace net\dontdrinkandroot\database;


/**
 * Configuration options for a MySql database.
 * @package net\dontdrinkandroot\database
 */
class MySqlDatabaseConfig extends DatabaseConfig
{

    private $host;

    private $port = 3306;

    private $userName;

    private $password;

    private $databaseName;

    public function __construct($host, $port, $databaseName, $password, $userName)
    {
        $this->port = $port;
        $this->host = $host;
        $this->password = $password;
        $this->userName = $userName;
        $this->databaseName = $databaseName;
    }

    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    public function setDatabaseName($sDataBaseName)
    {
        $this->databaseName = $sDataBaseName;
    }

    /**
     * @param int $iPort
     */
    public function setPort($iPort)
    {
        $this->port = $iPort;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $sHost
     */
    public function setHost($sHost)
    {
        $this->host = $sHost;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $sPassword
     */
    public function setPassword($sPassword)
    {
        $this->password = $sPassword;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $sUserName
     */
    public function seSUserName($sUserName)
    {
        $this->userName = $sUserName;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

}