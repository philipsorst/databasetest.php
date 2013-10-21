<?php


namespace net\dontdrinkandroot\repository;


interface Repository
{

    public function find($mId);

    public function delete($id);

}