<?php


namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\schema;
use net\dontdrinkandroot\database\Database;

class DatabaseArticleRepository extends DatabaseRepository implements ArticleRepository
{

    public function __construct(Database $database)
    {
        parent::__construct($database, schema\Tables::ARTICLE, schema\Article::ID);
    }

    public function findArticlesWithPriceGreaterThan($price)
    {
        $sWhere = '`' . schema\Article::PRICE . '` > :price';
        $aParameters = array(':price' => $price);

        return $this->database->find($this->tableName, $sWhere, $aParameters);
    }
}