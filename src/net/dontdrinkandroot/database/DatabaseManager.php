<?php


namespace net\dontdrinkandroot\database;
use \PDO;

class DatabaseManager {

    private $_aConfigs = array();

    private $_aInstances = array();


    public function registerDatabase($sName, DatabaseConfig $oConfig) {

        $this->_aConfigs[$sName] = $oConfig;
    }

    public function getDatabase($sName) {

        if (isset($this->_aInstances[$sName])) {
            return $this->_aInstances[$sName];
        }

        if (!isset($this->_aConfigs[$sName])) {
            throw new \RuntimeException("No Database with the name $sName was found, you need to register it first.");
        }

        $oConfig = $this->_aConfigs[$sName];
        $oDb = new Database($oConfig);
        $oDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $oDb->query('set names utf8');

        $this->_aInstances[$sName] = $oDb;

        return $oDb;
    }

}