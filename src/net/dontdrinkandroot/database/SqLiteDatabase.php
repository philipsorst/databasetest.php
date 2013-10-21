<?php


namespace net\dontdrinkandroot\database;


/**
 * Implementation of a SqLite Database.
 * @package net\dontdrinkandroot\database
 */
class SqLiteDatabase extends Database
{

    public function __construct(SqLiteDatabaseConfig $config)
    {
        parent::__construct('sqlite:' . $config->getPath());
    }

}