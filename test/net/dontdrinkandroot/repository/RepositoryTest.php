<?php

namespace net\dontdrinkandroot\database\repository;

use net\dontdrinkandroot\database\MySqlDatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;

class RepositoryTest extends \PHPUnit_Extensions_Database_TestCase {

    public function testFind() {

        $databaseManager = new DatabaseManager();
        $databaseConfig = new MySqlDatabaseConfig( "localhost", 3306, "test", "test", "test" );
        $databaseManager->registerDatabase("test", $databaseConfig);

        $db = $databaseManager->getDatabase("test");
        $oRepository = new Repository( $db, "Article", "id");
        $oRepository->find(1);
    }

}