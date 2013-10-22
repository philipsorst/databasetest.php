<?php


namespace net\dontdrinkandroot\repository;


interface Repository
{

    /**
     * Find an entity by its primary key.
     *
     * @param mixed $id The id of the entity to find.
     *
     * @return array An array containing the corresponding row of the entity or null if none was found.
     * @throws \net\dontdrinkandroot\exception\TooManyResultsException Thrown if more than one result was found.
     */
    public function find($id);

    /**
     * Finds all entities.
     *
     * @return array An associative array that contains all entities in the database table.
     */
    public function findAll();

    /**
     * Deletes an entity by its primary key.
     *
     * @param mixed $id The id of the entity to delete.
     *
     * @return int The number of rows that were affected, this should be 0 or 1 depending on whether
     * the entity existed and the primary key was correctly specified.
     */
    public function delete($id);

    /**
     * Insert a row in to the table.
     *
     * @param array $row The row to insert as an associative array where the key is the column and the value the value.
     * @return int The number of rows that were affected (this should always be 1 as otherwise something went wrong).
     */
    public function insert(array $row);

}