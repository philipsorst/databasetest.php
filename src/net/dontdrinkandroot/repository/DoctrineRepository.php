<?php

namespace net\dontdrinkandroot\repository;


use Doctrine\DBAL\Connection;

class DoctrineRepository extends AbstractRepository
{

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    public function __construct(Connection $connection, $tableName, $primaryKey)
    {
        parent::__construct($tableName, $primaryKey);
        $this->connection = $connection;
    }

    public function find($id)
    {
        $sql = 'SELECT * FROM `' . $this->tableName . '` WHERE `' . $this->primaryKey . '` = :id';
        $results = $this->connection->fetchAll($sql, array(":id" => $id));

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
        return $this->connection->delete($this->tableName, array($this->primaryKey => $id));
    }

    public function findAll()
    {
        $sql = 'SELECT * FROM `' . $this->tableName . '`';
        return $this->connection->fetchAll($sql);
    }

    public function insert(array $row)
    {
        return $this->connection->insert($this->tableName, $row);
    }
}