<?php

namespace net\dontdrinkandroot\repository;

use \PDO;
use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\database\MySqlDatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;

class RepositoryTest extends PHPUnit_Extensions_Database_TestCase
{

    /**
     * @var DatabaseManager
     */
    private static $databaseManager;

    public static function setUpBeforeClass()
    {
        self::$databaseManager = new DatabaseManager();
        self::$databaseManager->registerDatabase("test", new MySqlDatabaseConfig("localhost", 3306, "test", "test", "test"));
    }

    public function getConnection()
    {
        $database = self::$databaseManager->getDatabase("test");
        return $this->createDefaultDBConnection($database);
    }

    public function getDataSet()
    {
        $databasePath = __DIR__ . "/../../../data.xml";
        return $this->createXMLDataSet(realpath($databasePath));
    }


    public function testFind()
    {
        $database = self::$databaseManager->getDatabase("test");
        $oRepository = new DatabaseRepository($database, "Article", "id");
        $result = $oRepository->find(1);

        $this->assertEquals(1, $result["id"]);
        $this->assertEquals("Article One", $result["name"]);
        $this->assertEquals(3.99, $result["price"]);
    }


    public function testDelete()
    {
        $database = self::$databaseManager->getDatabase("test");
        $repository = new DatabaseRepository($database, "Article", "id");

        $result = $repository->find(3);
        $this->assertNotNull($result);

        $this->assertEquals(1, $repository->delete(3));

        $result = $repository->find(3);
        $this->assertNull($result);
    }

}