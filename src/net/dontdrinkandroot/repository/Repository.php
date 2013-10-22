<?php


namespace net\dontdrinkandroot\repository;


interface Repository
{

    /**
     * Find an entity by its primary key.
     *
     * @param mixed $mId The id of the entity to find.
     *
     * @return array An array containing the corresponding row of the entity or null if none was found.
     * @throws \net\dontdrinkandroot\exception\TooManyResultsException Thrown if more than one result was found.
     */
    public function find($id);

    /**
     * Deletes an entity by its primary key.
     *
     * @param mixed $id The id of the entity to delete.
     *
     * @return int The number of rows that were affected, this should be 0 or 1 depending on whether
     * the entity existed and the primary key was correctly specified.
     */
    public function delete($id);

}