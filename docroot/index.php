<?php

use net\dontdrinkandroot\database\DatabaseManager;
use net\dontdrinkandroot\database\DatabaseConfig;

error_reporting(E_ALL ^ E_NOTICE);

$sBasePath = dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR . 'src';
define( BASE_PATH, $sBasePath );

function __autoload( $sClass ) {

    echo $sClass . "\n";

    $sClass = str_replace( '\\', DIRECTORY_SEPARATOR, $sClass );
    $sClassName = BASE_PATH . DIRECTORY_SEPARATOR . $sClass . '.php';

    require_once $sClassName;
}

$databaseManager = new DatabaseManager();
$databaseConfig = new DatabaseConfig("localhost", 3306, "test", "test", "test");
$databaseManager->registerDatabase("test", $databaseConfig);

$db = $databaseManager->getDatabase("test");

$createArticleSql = "INSERT INTO Article(name, price) VALUES (:name, :price)";
$createArticleStatement = $db->prepare($createArticleSql);
$createArticleStatement->execute(array(':name' => "Product One", ':price' => 3.99));