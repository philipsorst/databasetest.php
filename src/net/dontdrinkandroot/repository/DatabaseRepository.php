<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\database\Database;
use net\dontdrinkandroot\exception\TooManyResultsException;

class DatabaseRepository implements Repository
{

    /**
     * @var Database
     */
    protected $dataBase;

    protected $tableName;

    protected $primaryKey;

    public function __construct(Database $database, $tableName, $primaryKey)
    {
        $this->dataBase = $database;
        $this->tableName = (string)$tableName;
        $this->primaryKey = (string)$primaryKey;
    }

    /**
     * Find an entity by its primary key.
     *
     * @param mixed $mId The id of the entity to find.
     *
     * @return array An array containing the corresponding row of the entity or null if none was found.
     * @throws \net\dontdrinkandroot\exception\TooManyResultsException Thrown if more than one result was found.
     */
    public function find($mId)
    {
        $results = $this->dataBase->find(
            $this->tableName,
            '`' . $this->primaryKey . '` = :id',
            array(':id' => $mId)
        );

        if (0 == count($results)) {
            return null;
        }

        /* This exception should is only thrown if the primary key was not specified correctly */
        if (1 < count($results)) {
            throw new TooManyResultsException(
                'Found ' . count($results) .
                ' results but only max one was expected'
            );
        }

        return $results[0];
    }

    /**
     * Deletes an entity by its primary key.
     *
     * @param mixed $id The id of the entity to delete.
     *
     * @return int The number of rows that were affected, this should be 0 or 1 depending on whether
     * the entity existed and the primary key was correctly specified.
     */
    public function delete($id)
    {
        $sSql = 'DELETE FROM `' . $this->tableName . '` ' .
            'WHERE `' . $this->primaryKey . '` = :id';

        /** @var \PDOStatement */
        $statement = $this->dataBase->prepare($sSql);
        $statement->bindParam(':id', $id);
        $statement->execute();

        return $statement->rowCount();
    }


}