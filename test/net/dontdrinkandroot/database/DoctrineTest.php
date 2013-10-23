<?php

namespace net\dontdrinkandroot\repository;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use net\dontdrinkandroot\DatabaseTestCase;
use net\dontdrinkandroot\schema;

class DoctrineTest extends DatabaseTestCase
{

    public function testFind()
    {
        $conn = $this->getDoctrineConnection();
        $results = $conn->fetchAll('SELECT * FROM Article WHERE price > :price', array('price' => 3.5));

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

    public function testQueryBuilder()
    {
        $conn = $this->getDoctrineConnection();
        $queryBuilder = $conn->createQueryBuilder();
        $priceExpression = $queryBuilder->expr()->gt('a.price', 3.5);
        $query = $queryBuilder->select('*')->from(schema\Tables::ARTICLE, 'a')->where($priceExpression);
        $results = $query->execute()->fetchAll();

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

    protected function getDoctrineConnection()
    {
        $config = new Configuration();
        $connectionParams = array('pdo' => $this->getDatabase());
        $conn = DriverManager::getConnection($connectionParams, $config);

        return $conn;
    }
}