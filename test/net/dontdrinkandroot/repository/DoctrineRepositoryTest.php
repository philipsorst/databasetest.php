<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\DatabaseTestCase;
use net\dontdrinkandroot\DoctrineTestCase;
use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\schema;

class DoctrineRepositoryTest extends DoctrineTestCase
{

    /**
     * @var Repository
     */
    protected static $repository;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$repository = new DoctrineRepository(
            self::getDoctrineConnection(),
            schema\Tables::ARTICLE,
            schema\Article::ID
        );
    }

    public function testFind()
    {
        $result = self::$repository->find(1);

        $this->assertEquals(1, $result["id"]);
        $this->assertEquals("Article One", $result["name"]);
        $this->assertEquals(3.99, $result["price"]);
    }


    public function testDelete()
    {
        $result = self::$repository->find(3);
        $this->assertNotNull($result);

        $this->assertEquals(1, self::$repository->delete(3));

        $result = self::$repository->find(3);
        $this->assertNull($result);
    }

    public function testFindAll()
    {
        $results = self::$repository->findAll();

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

    public function testInsert()
    {
        $articleName = 'A new article';
        $articlePrice = 42.21;

        $row = array(schema\Article::NAME => $articleName, schema\Article::PRICE => $articlePrice);

        $this->assertEquals(1, self::$repository->insert($row));

        $result = self::$repository->findAll();

        $this->assertCount(4, $result);

        $insertedRow = $result[3];

        $this->assertNotNull($insertedRow[schema\Article::ID]);
        $this->assertEquals($articleName, $insertedRow[schema\Article::NAME]);
        $this->assertEquals($articlePrice, $insertedRow[schema\Article::PRICE]);
    }

    public function testUpdate()
    {
        $changedName = 'Changed Name';
        $changedPrice = 666.66;

        $values = array(schema\Article::NAME => $changedName, schema\Article::PRICE => $changedPrice);
        $where = array(schema\Article::ID => 1);

        $this->assertEquals(1, self::$repository->update($values, $where));

        $row =  self::$repository->find(1);

        $this->assertEquals(1, $row[schema\Article::ID]);
        $this->assertEquals($changedName, $row[schema\Article::NAME]);
        $this->assertEquals($changedPrice, $row[schema\Article::PRICE]);
    }

}
