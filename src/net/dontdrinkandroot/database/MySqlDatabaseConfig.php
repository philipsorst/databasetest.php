<?php


namespace net\dontdrinkandroot\database;


class MySqlDatabaseConfig extends DatabaseConfig {

    private $_sHost;

    private $_iPort = 3306;

    private $_sUserName;

    private $_sPassword;

    private $_sDatabaseName;

    public function __construct($_sHost, $_iPort, $sDatabaseName, $_sPassword, $_sUserName)
    {
        $this->_iPort = $_iPort;
        $this->_sHost = $_sHost;
        $this->_sPassword = $_sPassword;
        $this->_sUserName = $_sUserName;
        $this->_sDatabaseName = $sDatabaseName;
    }

    public function getDatabaseName()
    {
        return $this->_sDatabaseName;
    }

    public function setDatabaseName($sDataBaseName)
    {
        $this->_sDatabaseName = $sDataBaseName;
    }

    /**
     * @param int $iPort
     */
    public function setPort($iPort)
    {
        $this->_iPort = $iPort;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->_iPort;
    }

    /**
     * @param string $sHost
     */
    public function setHost($sHost)
    {
        $this->_sHost = $sHost;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->_sHost;
    }

    /**
     * @param string $sPassword
     */
    public function setPassword($sPassword)
    {
        $this->_sPassword = $sPassword;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_sPassword;
    }

    /**
     * @param string $sUserName
     */
    public function seSUserName($sUserName)
    {
        $this->_sUserName = $sUserName;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->_sUserName;
    }



}