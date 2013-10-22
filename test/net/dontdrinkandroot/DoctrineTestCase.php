<?php

namespace net\dontdrinkandroot;


use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class DoctrineTestCase extends DatabaseTestCase
{

    protected static function getDoctrineConnection()
    {
        $config = new Configuration();
        $connectionParams = array('pdo' => self::$databaseManager->getDatabase('test'));
        $conn = DriverManager::getConnection($connectionParams, $config);

        return $conn;
    }

}