<?php


namespace net\dontdrinkandroot\repository;


interface Repository
{

    public function find($id);

    public function delete($id);

}