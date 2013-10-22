<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\DatabaseTestCase;
use net\dontdrinkandroot\DoctrineTestCase;
use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\schema;

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

    public function testFindAll()
    {
        $repository = new DoctrineRepository($this->getDoctrineConnection(), schema\Tables::ARTICLE, schema\Article::ID);

        $results = $repository->findAll();

        $this->assertCount(3, $results);

        /* This assumes that the ordering is by natural insertion */
        $result = $results[0];
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Article One', $result['name']);
        $this->assertEquals(3.99, $result['price']);

        $result = $results[1];
        $this->assertEquals(2, $result['id']);
        $this->assertEquals('Article Two', $result['name']);
        $this->assertEquals(4.75, $result['price']);

        $result = $results[2];
        $this->assertEquals(3, $result['id']);
        $this->assertEquals('Article Three', $result['name']);
        $this->assertEquals(1.23, $result['price']);
    }

}