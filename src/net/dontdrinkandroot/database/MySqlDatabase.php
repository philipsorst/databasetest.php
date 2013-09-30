<?php

namespace net\dontdrinkandroot\database;
use \PDO;

/**
 * Implementation of a MySQL Database.
 * @package net\dontdrinkandroot\database
 */
class MySqlDatabase extends Database {

    public function __construct( MySqlDatabaseConfig $oConfig )
    {
        parent::__construct(
            'mysql:host=' . $oConfig->getHost() . ';' .
            'port=' . $oConfig->getPort() . ';' .
            'dbname=' . $oConfig->getDatabaseName(),
            $oConfig->getUserName(),
            $oConfig->getPassword());
    }
}