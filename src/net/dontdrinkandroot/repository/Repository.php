<?php


namespace net\dontdrinkandroot\repository;


interface Repository {

    function find( $mId );

    function delete( $mId );

}