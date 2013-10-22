<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\database\Database;
use net\dontdrinkandroot\exception\TooManyResultsException;

class DatabaseRepository extends AbstractRepository
{

    /**
     * @var Database
     */
    protected $dataBase;

    public function __construct(Database $database, $tableName, $primaryKey)
    {
        parent::__construct($tableName, $primaryKey);
        $this->dataBase = $database;
    }

    public function find($id)
    {
        $results = $this->dataBase->find(
            $this->tableName,
            '`' . $this->primaryKey . '` = :id',
            array(':id' => $id)
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