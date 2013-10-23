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
        $cacheKey = $this->getCacheKey($id);
        $row = $this->cache->get($cacheKey);

        if (false !== $row) {
            return $row;
        }

        $row = parent::find($id);
        if (null !== $row) {
            $this->cache->set($cacheKey, $row);
        }

        return $row;
    }

    public function delete($id)
    {
        $numRowsAffected = parent::delete($id);
        $this->cache->delete($this->getCacheKey($id));

        return $numRowsAffected;
    }

    public function getCacheKey($id)
    {
        return $this->tableName . '_' . $id;
    }

}