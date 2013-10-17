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
    private static $_oDatabaseManager;

    public static function setUpBeforeClass()
    {
        self::$_oDatabaseManager = new DatabaseManager();
        self::$_oDatabaseManager->registerDatabase("test", new MySqlDatabaseConfig("localhost", 3306, "test", "test", "test"));
    }

    public function getConnection()
    {
        $oDb = self::$_oDatabaseManager->getDatabase("test");
        return $this->createDefaultDBConnection($oDb);
    }

    public function getDataSet()
    {
        $sDbPath = __DIR__ . "/../../../data.xml";
        return $this->createXMLDataSet(realpath($sDbPath));
    }


    public function testFind()
    {
        $db = self::$_oDatabaseManager->getDatabase("test");
        $oRepository = new DatabaseRepository($db, "Article", "id");
        $aResult = $oRepository->find(1);

        $this->assertEquals(1, $aResult["id"]);
        $this->assertEquals("Article One", $aResult["name"]);
        $this->assertEquals(3.99, $aResult["price"]);
    }


    public function testDelete()
    {
        $db = self::$_oDatabaseManager->getDatabase("test");
        $oRepository = new DatabaseRepository($db, "Article", "id");

        $aResult = $oRepository->find(3);
        $this->assertNotNull($aResult);

        $this->assertEquals(1, $oRepository->delete(3));

        $aResult = $oRepository->find(3);
        $this->assertNull($aResult);
    }

}