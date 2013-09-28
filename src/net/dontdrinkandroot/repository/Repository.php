<?php

namespace net\dontdrinkandroot\database\repository;

use net\dontdrinkandroot\database\Database;


class Repository {

    /**
     * @var Database
     */
    private $_oDatabase;

    private $_sTableName;

    private $_sPrimaryKey;


    public function __construct( Database $oDatabase, $sTableName, $sPrimaryKey ) {

        $this->_oDatabase = $oDatabase;
        $this->_sTableName = (string) $sTableName;
        $this->_sPrimaryKey = (string) $sPrimaryKey;
    }


    public function find( $mId ) {

        $sSql = 'SELECT * FROM `' . $this->_sTableName . '` ' .
                'WHERE `' . $this->_sPrimaryKey . '` = :id';
        $oStatement = $this->_oDatabase->prepare( $sSql) ;
        $oStatement->bindParam( ':id', $mId );
        $oStatement->execute();
        $aResults = $oStatement->fetchAll();
        print_r($aResults);
    }


    public function delete( $mId ) {

    }

}