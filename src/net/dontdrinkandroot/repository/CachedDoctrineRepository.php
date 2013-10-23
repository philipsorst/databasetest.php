<?php


namespace net\dontdrinkandroot\repository;

use Doctrine\DBAL\Connection;
use \Memcache;

class CachedDoctrineRepository extends DoctrineRepository
{

    /**
     * @var Memcache
     */
    private $cache;

    public function __construct(Connection $connection, $tableName, $primaryKey, Memcache $cache)
    {
        parent::__construct($connection, $tableName, $primaryKey);
        $this->cache = $cache;
    }

    public function find($id)
    {
        $cacheKey = $this->tableName . '_' . $id;
        $row = $this->cache->get($cacheKey);
        if (false !== $row) {
            return $row;
        }

        $row = parent::find($id);
        $this->cache->set($cacheKey, $row);

        return $row;
    }

}