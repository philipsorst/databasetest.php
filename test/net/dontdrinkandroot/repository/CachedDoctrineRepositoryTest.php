<?php

namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\DatabaseTestCase;
use net\dontdrinkandroot\DoctrineTestCase;
use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\schema;
use \Memcache;

class CachedDoctrineRepositoryTest extends DoctrineTestCase
{

    public function testFind()
    {
        $id = 1;

        $cache = new Memcache();
        $cache->connect('localhost', 11211) or $this->markTestSkipped('MemCache not available');
        $cache->delete('Article_1');

        // TODO: This Test is not stable if the cache is used during the test.
        $stats = $cache->getstats();
        $initialHits = $stats['get_hits'];
        $initialMisses = $stats['get_misses'];

        $repository = new CachedDoctrineRepository(
            $this->getDoctrineConnection(),
            schema\Tables::ARTICLE,
            schema\Article::ID,
            $cache
        );

        $row = $repository->find($id);
        $this->assertEquals($id, $row[schema\Article::ID]);

        $stats = $cache->getstats();
        $this->assertEquals($initialHits, $stats['get_hits']);
        $this->assertEquals($initialMisses + 1, $stats['get_misses']);

        $row = $repository->find($id);
        $this->assertEquals($id, $row[schema\Article::ID]);

        $stats = $cache->getstats();
        $this->assertEquals($initialHits + 1, $stats['get_hits']);
        $this->assertEquals($initialMisses + 1, $stats['get_misses']);
    }
}