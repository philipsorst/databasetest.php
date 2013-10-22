<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\DatabaseTestCase;
use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\schema;

class DatabaseTest extends DatabaseTestCase
{

    public function testFind()
    {
        $database = $this->getDatabase();
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
        $database = $this->getDatabase();

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
        $database = $this->getDatabase();

        $resultIterator = $database->findBatch('Article', '1 = 0', array(), null, 2);

        $this->assertFalse($resultIterator->valid());
    }

    public function testFindBatchSubset()
    {
        $database = $this->getDatabase();

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

    public function testInsert()
    {
        $articleName = 'A new article';
        $articlePrice = 42.21;

        $database = $this->getDatabase();

        $row = array(schema\Article::NAME => $articleName, schema\Article::PRICE => $articlePrice);

        $this->assertEquals(1, $database->insert(schema\Tables::ARTICLE, $row));

        $result = $database->find(schema\Tables::ARTICLE, "1 = 1");

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

        $database = $this->getDatabase();

        $this->assertEquals(1, $database->update(schema\Tables::ARTICLE, $values, $where));

        $rows = $database->find(schema\Tables::ARTICLE, "id = :id", array(":id" => 1));
        $this->assertCount(1, $rows);
        $row = $rows[0];

        $this->assertEquals(1, $row[schema\Article::ID]);
        $this->assertEquals($changedName, $row[schema\Article::NAME]);
        $this->assertEquals($changedPrice, $row[schema\Article::PRICE]);
    }

}