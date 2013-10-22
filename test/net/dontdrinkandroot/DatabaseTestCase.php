<?php

namespace net\dontdrinkandroot;


use net\dontdrinkandroot\database\DatabaseManager;
use net\dontdrinkandroot\database\SqLiteDatabaseConfig;

class DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{

    /**
     * @var DatabaseManager
     */
    protected static $databaseManager;

    public static function setUpBeforeClass()
    {
        self::$databaseManager = new DatabaseManager();
        self::$databaseManager->registerDatabase(
            "test",
            new SqLiteDatabaseConfig("sqlite::memory:")
        );
    }

    public function getConnection()
    {
        $database = self::$databaseManager->getDatabase("test");

        $createSql =
            "CREATE TABLE IF NOT EXISTS `Article` ( " .
            "`id` INTEGER NOT NULL, " .
            "`name` TEXT NOT NULL, " .
            "`price` FLOAT NOT NULL, " .
            "PRIMARY KEY (`id`) " .
            ");";
        $database->query($createSql);

        return $this->createDefaultDBConnection($database);
    }

    public function getDataSet()
    {
        $databasePath = __DIR__ . "/../../data.xml";

        return $this->createXMLDataSet(realpath($databasePath));
    }

    protected function getDatabase()
    {
        return self::$databaseManager->getDatabase("test");
    }

}