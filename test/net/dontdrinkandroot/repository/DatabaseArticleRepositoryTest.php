<?php

namespace net\dontdrinkandroot\repository;

use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\database\MySqlDatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;
use net\dontdrinkandroot\schema;

class DatabaseArticleRepositoryTest extends PHPUnit_Extensions_Database_TestCase
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

    public function testFindArticlesWithPriceGreaterThan()
    {
        $database = self::$databaseManager->getDatabase("test");
        $repository = new DatabaseArticleRepository($database);
        $results = $repository->findArticlesWithPriceGreaterThan(3.5);

        $this->assertCount(2, $results);

        /* This assumes that the ordering is by natural insertion */
        $result = $results[0];
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Article One', $result['name']);
        $this->assertEquals(3.99, $result['price']);

        $result = $results[1];
        $this->assertEquals(2, $result['id']);
        $this->assertEquals('Article Two', $result['name']);
        $this->assertEquals(4.75, $result['price']);
    }
}