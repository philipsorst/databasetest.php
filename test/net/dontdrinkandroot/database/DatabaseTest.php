<?php

namespace net\dontdrinkandroot\repository;

use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\database\MySqlDatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;
use net\dontdrinkandroot\schema;

class DatabaseTest extends PHPUnit_Extensions_Database_TestCase
{

    /**
     * @var DatabaseManager
     */
    private static $databaseManager;

    public static function setUpBeforeClass()
    {
        self::$databaseManager = new DatabaseManager();
        self::$databaseManager->registerDatabase(
            "test",
            new MySqlDatabaseConfig("localhost", 3306, "test", "test", "test")
        );
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
        $aResults = $database->find(
            schema\Tables::ARTICLE,
            '`price` > :price',
            array('price' => 3.5),
            array('name', 'price')
        );

        $this->assertCount(2, $aResults);

        /* This assumes that the ordering is by natural insertion */
        $result = $aResults[0];
        $this->assertArrayNotHasKey('id', $result);
        $this->assertEquals('Article One', $result['name']);
        $this->assertEquals(3.99, $result['price']);

        $result = $aResults[1];
        $this->assertArrayNotHasKey('id', $result);
        $this->assertEquals('Article Two', $result['name']);
        $this->assertEquals(4.75, $result['price']);
    }

    public function testFindBatch()
    {
        $database = self::$databaseManager->getDatabase("test");

        $resultIterator = $database->findBatch('Article', '1 = 1', array(), null, 2);

        $this->assertTrue($resultIterator->valid());
        $this->assertEquals(0, $resultIterator->key());
        $this->assertEquals(1, $resultIterator->current()['id']);

        $resultIterator->next();
        $this->assertTrue($resultIterator->valid());
        $this->assertEquals(1, $resultIterator->key());
        $this->assertEquals(2, $resultIterator->current()['id']);

        $resultIterator->next();
        $this->assertTrue($resultIterator->valid());
        $this->assertEquals(2, $resultIterator->key());
        $this->assertEquals(3, $resultIterator->current()['id']);

        $resultIterator->next();
        $this->assertFalse($resultIterator->valid());
    }

    public function testFindBatchEmptyResult()
    {
        $database = self::$databaseManager->getDatabase("test");

        $resultIterator = $database->findBatch('Article', '1 = 0', array(), null, 2);

        $this->assertFalse($resultIterator->valid());
    }

    public function testFindBatchSubset()
    {
        $database = self::$databaseManager->getDatabase("test");

        $resultIterator = $database->findBatch('Article', 'id > 1', array(), null, 2);

        $this->assertTrue($resultIterator->valid());
        $this->assertEquals(0, $resultIterator->key());
        $this->assertEquals(2, $resultIterator->current()['id']);

        $resultIterator->next();
        $this->assertTrue($resultIterator->valid());
        $this->assertEquals(1, $resultIterator->key());
        $this->assertEquals(3, $resultIterator->current()['id']);

        $resultIterator->next();
        $this->assertFalse($resultIterator->valid());
    }
}