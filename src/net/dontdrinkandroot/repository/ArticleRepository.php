<?php


namespace net\dontdrinkandroot\repository;


interface ArticleRepository extends Repository {

    function findArticlesWithPriceGreaterThan( $fPrice );

}