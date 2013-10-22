<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\database\Database;
use net\dontdrinkandroot\exception\TooManyResultsException;

class DatabaseRepository extends AbstractRepository
{

    /**
     * @var Database
     */
    protected $database;

    public function __construct(Database $database, $tableName, $primaryKey)
    {
        parent::__construct($tableName, $primaryKey);
        $this->database = $database;
    }

    public function find($id)
    {
        $results = $this->database->find(
            $this->tableName,
            '`' . $this->primaryKey . '` = :id',
            array(':id' => $id)
        );

        if (0 == count($results)) {
            return null;
        }

        /* This exception is only thrown if the primary key was not specified correctly */
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
        $sql = 'DELETE FROM `' . $this->tableName . '` ' .
            'WHERE `' . $this->primaryKey . '` = :id';

        /** @var \PDOStatement */
        $statement = $this->database->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->execute();

        return $statement->rowCount();
    }

    public function findAll()
    {
        return $this->database->find($this->tableName, "1 = 1");
    }

    public function insert(array $row)
    {
        return $this->database->insert($this->tableName, $row);
    }

    public function update(array $values, array $where)
    {
        return $this->database->update($this->tableName, $values, $where);
    }

}