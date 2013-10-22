<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\DatabaseTestCase;
use net\dontdrinkandroot\DoctrineTestCase;
use \PDO;
use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\database\MySqlDatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;

class DoctrineRepositoryTest extends DoctrineTestCase
{

    public function testFind()
    {
        $oRepository = new DoctrineRepository($this->getDoctrineConnection(), "Article", "id");
        $result = $oRepository->find(1);

        $this->assertEquals(1, $result["id"]);
        $this->assertEquals("Article One", $result["name"]);
        $this->assertEquals(3.99, $result["price"]);
    }


    public function testDelete()
    {
        $repository = new DoctrineRepository($this->getDoctrineConnection(), "Article", "id");

        $result = $repository->find(3);
        $this->assertNotNull($result);

        $this->assertEquals(1, $repository->delete(3));

        $result = $repository->find(3);
        $this->assertNull($result);
    }

}