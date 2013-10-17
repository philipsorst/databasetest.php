<?php


namespace net\dontdrinkandroot\repository;

use net\dontdrinkandroot\schema;
use net\dontdrinkandroot\database\Database;

class DatabaseArticleRepository extends DatabaseRepository implements ArticleRepository
{

    public function __construct( Database $oDb )
    {
        parent::__construct( $oDb, schema\Tables::ARTICLE, schema\Article::ID );
    }

    function findArticlesWithPriceGreaterThan( $fPrice )
    {
        $sWhere      = '`' . schema\Article::PRICE . '` > :price';
        $aParameters = array( ':price' => $fPrice );

        return $this->_oDatabase->find( $this->_sTableName, $sWhere, $aParameters );
    }
}