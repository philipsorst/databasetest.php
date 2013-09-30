<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\database\Database;
use net\dontdrinkandroot\exception\TooManyResultsException;


class Repository
{

    /**
     * @var MySqlDatabase
     */
    private $_oDatabase;

    private $_sTableName;

    private $_sPrimaryKey;


    public function __construct(Database $oDatabase, $sTableName, $sPrimaryKey)
    {
        $this->_oDatabase = $oDatabase;
        $this->_sTableName = (string)$sTableName;
        $this->_sPrimaryKey = (string)$sPrimaryKey;
    }


    /**
     * Find an entity by its primary key.
     *
     * @param $mId The id of the entity to find.
     * @return An array containing the corresponding row of the entity or null if none was found.
     * @throws \net\dontdrinkandroot\exception\TooManyResultsException Throw if more than one result was found.
     */
    public function find($mId)
    {
        $sSql = 'SELECT * FROM `' . $this->_sTableName . '` ' .
                'WHERE `' . $this->_sPrimaryKey . '` = :id';

        /** @var \PDOStatement */
        $oStatement = $this->_oDatabase->prepare($sSql);
        $oStatement->bindParam(':id', $mId);
        $oStatement->execute();

        $aResults = $oStatement->fetchAll();

        if (0 == count($aResults)) {
            return null;
        }

        /* This exception should is only thrown if the primary key was not specified correctly */
        if (1 < count($aResults)) {
            throw new TooManyResultsException('Found ' . count($aResults) . ' results but only max one was expected');
        }

        return $aResults[0];
    }


    /**
     * Deletes an entity by its primary key.
     *
     * @param $mId The id of the entity to delete.
     * @return int The number of rows that were affected, this should be 0 or 1 depending on whether
     * the entity existed and the primary key was correctly specified.
     */
    public function delete($mId)
    {
        $sSql = 'DELETE FROM `' . $this->_sTableName . '` ' .
                'WHERE `' . $this->_sPrimaryKey . '` = :id';

        /** @var \PDOStatement */
        $oStatement = $this->_oDatabase->prepare($sSql);
        $oStatement->bindParam(':id', $mId);
        $oStatement->execute();

        return $oStatement->rowCount();
    }

}