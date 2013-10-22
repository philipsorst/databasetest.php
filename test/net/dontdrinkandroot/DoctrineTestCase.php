<?php

namespace net\dontdrinkandroot;


use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class DoctrineTestCase extends DatabaseTestCase
{

    protected function getDoctrineConnection()
    {
        $config = new Configuration();
        $connectionParams = array('pdo' => $this->getDatabase());
        $conn = DriverManager::getConnection($connectionParams, $config);

        return $conn;
    }

}