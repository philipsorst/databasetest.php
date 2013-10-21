<?php


namespace net\dontdrinkandroot\repository;


interface ArticleRepository extends Repository
{

    public function findArticlesWithPriceGreaterThan($price);

}