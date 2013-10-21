<?php

namespace net\dontdrinkandroot\database;

use \PDO;

/**
 * Implementation of a MySQL Database.
 * @package net\dontdrinkandroot\database
 */
class MySqlDatabase extends Database
{

    public function __construct(MySqlDatabaseConfig $config)
    {
        parent::__construct(
            'mysql:host=' . $config->getHost() . ';' .
            'port=' . $config->getPort() . ';' .
            'dbname=' . $config->getDatabaseName(),
            $config->getUserName(),
            $config->getPassword()
        );
    }
}