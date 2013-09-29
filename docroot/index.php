<?php

error_reporting(E_ALL ^ E_NOTICE);

$sBasePath = dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR . 'src';
define( BASE_PATH, $sBasePath );

function __autoload( $sClass ) {

    $sClass = str_replace( '\\', DIRECTORY_SEPARATOR, $sClass );
    $sClassName = BASE_PATH . DIRECTORY_SEPARATOR . $sClass . '.php';

    echo $sClassName . "\n";+

    require_once $sClassName;
}

$databaseManager = new \net\dontdrinkandroot\database\DatabaseManager();
$databaseConfig = new \net\dontdrinkandroot\database\MySqlDatabaseConfig("localhost", 3306, "test", "test", "test");
$databaseManager->registerDatabase("test", $databaseConfig);

$db = $databaseManager->getDatabase("test");

$createArticleSql = "INSERT INTO Article(name, price) VALUES (:name, :price)";
$createArticleStatement = $db->prepare($createArticleSql);
$createArticleStatement->execute(array(':name' => "Product One", ':price' => 3.99));