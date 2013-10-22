<?php


namespace net\dontdrinkandroot\repository;


abstract class AbstractRepository implements Repository
{

    protected $tableName;

    protected $primaryKey;

    public function __construct($tableName, $primaryKey)
    {
        $this->tableName = (string)$tableName;
        $this->primaryKey = (string)$primaryKey;
    }

}