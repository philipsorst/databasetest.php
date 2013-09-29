<?php


namespace net\dontdrinkandroot\database;


class DatabaseFactory {

    public static function createDatabase( DatabaseConfig $oConfig ) {

        if ( $oConfig instanceof MySqlDatabaseConfig) {
            return new MySqlDatabase( $oConfig );
        }

        throw new RuntimeException("Unsupported database config " . get_class( $oConfig ));
    }

}