<?php

namespace net\dontdrinkandroot\repository;

use \PDO;
use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\database\MySqlDatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;

class DatabaseTest extends PHPUnit_Extensions_Database_TestCase
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
        $oDb = self::$_oDatabaseManager->getDatabase("test");
        $aResults = $oDb->find('Article', '`price` > :price', array('price' => 3.5), array('name', 'price'));

        $this->assertCount(2, $aResults);

        /* This assumes that the ordering is by natural insertion */
        $aResult = $aResults[0];
        $this->assertArrayNotHasKey('id', $aResult);
        $this->assertEquals('Article One', $aResult['name']);
        $this->assertEquals(3.99, $aResult['price']);

        $aResult = $aResults[1];
        $this->assertArrayNotHasKey('id', $aResult);
        $this->assertEquals('Article Two', $aResult['name']);
        $this->assertEquals(4.75, $aResult['price']);
    }

    public function testFindBatch()
    {
        $oDb = self::$_oDatabaseManager->getDatabase("test");

        $oResultIterator = $oDb->findBatch('Article', '1 = 1', array(), null, 2);

        $this->assertTrue($oResultIterator->valid());
        $this->assertEquals(0, $oResultIterator->key());
        $this->assertEquals(1, $oResultIterator->current()['id']);

        $oResultIterator->next();
        $this->assertTrue($oResultIterator->valid());
        $this->assertEquals(1, $oResultIterator->key());
        $this->assertEquals(2, $oResultIterator->current()['id']);

        $oResultIterator->next();
        $this->assertTrue($oResultIterator->valid());
        $this->assertEquals(2, $oResultIterator->key());
        $this->assertEquals(3, $oResultIterator->current()['id']);

        $oResultIterator->next();
        $this->assertFalse($oResultIterator->valid());
    }

    public function testFindBatchEmptyResult() {
        $oDb = self::$_oDatabaseManager->getDatabase("test");

        $oResultIterator = $oDb->findBatch('Article', '1 = 0', array(), null, 2);

        $this->assertFalse($oResultIterator->valid());
    }

    public function testFindBatchSubset()
    {
        $oDb = self::$_oDatabaseManager->getDatabase("test");

        $oResultIterator = $oDb->findBatch('Article', 'id > 1', array(), null, 2);

        $this->assertTrue($oResultIterator->valid());
        $this->assertEquals(0, $oResultIterator->key());
        $this->assertEquals(2, $oResultIterator->current()['id']);

        $oResultIterator->next();
        $this->assertTrue($oResultIterator->valid());
        $this->assertEquals(1, $oResultIterator->key());
        $this->assertEquals(3, $oResultIterator->current()['id']);

        $oResultIterator->next();
        $this->assertFalse($oResultIterator->valid());
    }

}