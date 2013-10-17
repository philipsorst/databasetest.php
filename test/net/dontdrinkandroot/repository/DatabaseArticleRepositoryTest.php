<?php

namespace net\dontdrinkandroot\repository;

use \PHPUnit_Extensions_Database_TestCase;
use net\dontdrinkandroot\database\MySqlDatabaseConfig;
use net\dontdrinkandroot\database\DatabaseManager;
use net\dontdrinkandroot\schema;

class DatabaseArticleRepositoryTest extends PHPUnit_Extensions_Database_TestCase
{

    /**
     * @var DatabaseManager
     */
    private static $_oDatabaseManager;

    public static function setUpBeforeClass()
    {
        self::$_oDatabaseManager = new DatabaseManager();
        self::$_oDatabaseManager->registerDatabase( "test",
                                                    new MySqlDatabaseConfig( "localhost", 3306, "test", "test", "test" ) );
    }

    public function getConnection()
    {
        $oDb = self::$_oDatabaseManager->getDatabase( "test" );

        return $this->createDefaultDBConnection( $oDb );
    }

    public function getDataSet()
    {
        $sDbPath = __DIR__ . "/../../../data.xml";

        return $this->createXMLDataSet( realpath( $sDbPath ) );
    }

    public function testFindArticlesWithPriceGreaterThan()
    {
        $oDb         = self::$_oDatabaseManager->getDatabase( "test" );
        $oRepository = new DatabaseArticleRepository( $oDb );
        $aResults    = $oRepository->findArticlesWithPriceGreaterThan( 3.5 );

        $this->assertCount( 2, $aResults );

        /* This assumes that the ordering is by natural insertion */
        $aResult = $aResults[ 0 ];
        $this->assertEquals( 1, $aResult[ 'id' ] );
        $this->assertEquals( 'Article One', $aResult[ 'name' ] );
        $this->assertEquals( 3.99, $aResult[ 'price' ] );

        $aResult = $aResults[ 1 ];
        $this->assertEquals( 2, $aResult[ 'id' ] );
        $this->assertEquals( 'Article Two', $aResult[ 'name' ] );
        $this->assertEquals( 4.75, $aResult[ 'price' ] );
    }
}