<?php


namespace net\dontdrinkandroot\database;


class SqLiteDatabase extends Database
{

    public function __construct( SqLiteDatabaseConfig $oConfig )
    {
        parent::__construct( 'sqlite:' . $oConfig->getPath() );
    }

}