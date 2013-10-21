<?php


namespace net\dontdrinkandroot\database;


/**
 * Instantiates databases on the DatabaseConfig given.
 * @package net\dontdrinkandroot\database
 */
class DatabaseFactory
{

    public static function createDatabase(DatabaseConfig $config)
    {
        if ($config instanceof MySqlDatabaseConfig) {

            $database = new MySqlDatabase($config);
            $database->query('set names utf8');

            return $database;

        } elseif ($config instanceof SqLiteDatabaseConfig) {

            return new SqLiteDatabase($config);
        }

        throw new RuntimeException("Unsupported database config " . get_class($config));
    }

}