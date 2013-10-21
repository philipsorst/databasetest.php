<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\DatabaseTestCase;
use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\database\MySqlDatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;
use net\dontdrinkandroot\schema;

class DatabaseArticleRepositoryTest extends DatabaseTestCase
{

    public function testFindArticlesWithPriceGreaterThan()
    {
        $database = $this->getDatabase();
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