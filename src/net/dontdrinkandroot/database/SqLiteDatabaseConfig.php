<?php


namespace net\dontdrinkandroot\database;


/**
 * Configuration options for a SqLite Database.
 * @package net\dontdrinkandroot\database
 */
class SqLiteDatabaseConfig extends DatabaseConfig
{

    private $path;


    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

}