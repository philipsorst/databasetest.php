<?php

namespace net\dontdrinkandroot\database\repository;

require_once "../../../../src/net/dontdrinkandroot/database/Database.php";
require_once "../../../../src/net/dontdrinkandroot/database/DatabaseManager.php";
require_once "../../../../src/net/dontdrinkandroot/database/DatabaseConfig.php";
require_once "../../../../src/net/dontdrinkandroot/repository/Repository.php";

use net\dontdrinkandroot\database\DatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;

class RepositoryTest extends \PHPUnit_Framework_TestCase {

    public function testFind() {

        $databaseManager = new DatabaseManager();
        $databaseConfig = new DatabaseConfig("localhost", 3306, "test", "test", "test");
        $databaseManager->registerDatabase("test", $databaseConfig);

        $db = $databaseManager->getDatabase("test");
        $oRepository = new Repository( $db, "Article", "id");
        $oRepository->find(1);
    }

}