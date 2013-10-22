<?php

namespace net\dontdrinkandroot\repository;


use Doctrine\DBAL\Connection;
use net\dontdrinkandroot\schema;

class DoctrineArticleRepository extends DoctrineRepository implements ArticleRepository
{

    public function __construct(Connection $connection)
    {
        parent::__construct($connection, schema\Tables::ARTICLE, schema\Article::NAME);
    }

    public function findArticlesWithPriceGreaterThan($price)
    {
        $sql = "SELECT * FROM `" . $this->tableName . "` WHERE `" . schema\Article::PRICE . "` > :price";
        return $this->connection->fetchAll($sql, array(":price" => $price));
    }
}