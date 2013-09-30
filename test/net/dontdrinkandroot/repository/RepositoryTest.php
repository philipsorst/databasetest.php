<?php

namespace net\dontdrinkandroot\database\repository;

use \PDO;
use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\database\MySqlDatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;

class RepositoryTest extends PHPUnit_Extensions_Database_TestCase {

//    private static $_oDatabaseManager;
//
//    public static function setUpBeforeClass()
//    {
//        self::$_oDatabaseManager = new DatabaseManager();
//    }

    public function getConnection()
    {
        $oDatabaseManager = new DatabaseManager();
        $oPdo = new PDO('sqlite::memory:');
        return $this->createDefaultDBConnection($oPdo, ':memory');
    }

    public function getDataSet()
    {

    }


    public function testFind()
    {

        $databaseManager = new DatabaseManager();
        $databaseConfig = new MySqlDatabaseConfig( "localhost", 3306, "test", "test", "test" );
        $databaseManager->registerDatabase("test", $databaseConfig);

        $db = $databaseManager->getDatabase("test");
        $oRepository = new Repository( $db, "Article", "id");
        $oRepository->find(1);
    }

}