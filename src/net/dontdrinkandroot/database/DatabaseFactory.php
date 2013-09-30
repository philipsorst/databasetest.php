<?php


namespace net\dontdrinkandroot\database;


/**
 * Instantiates databases on the DatabaseConfig given.
 * @package net\dontdrinkandroot\database
 */
class DatabaseFactory {

    public static function createDatabase( DatabaseConfig $oConfig ) {

        if ( $oConfig instanceof MySqlDatabaseConfig ) {

            return new MySqlDatabase( $oConfig );

        } else if ( $oConfig instanceof SqLiteDatabaseConfig ) {

            return new SqLiteDatabase( $oConfig );
        }

        throw new RuntimeException("Unsupported database config " . get_class( $oConfig ));
    }

}