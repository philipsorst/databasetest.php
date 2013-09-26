<?php

namespace net\dontdrinkandroot\database;
use \PDO;

class Database extends PDO {

    public function __construct( DatabaseConfig $oConfig )
    {
        parent::__construct(
            'mysql:host=' . $oConfig->getHost() . ';' .
            'port=' . $oConfig->getPort() . ';' .
            'dbname=' . $oConfig->getDatabaseName(),
            $oConfig->getUserName(),
            $oConfig->getPassword());
    }
}