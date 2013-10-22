<?php


namespace net\dontdrinkandroot\database;

use \PDO;

/**
 * PDO Abstraction that encapsulates a database. Can be subclassed to implement dialect specific implementations.
 *
 * @package net\dontdrinkandroot\database
 */
class Database extends PDO
{

    const DEFAULT_BATCH_SIZE = 50;

    /**
     * Find rows on the database with the given restrictions.
     *
     * @param string $tableName   Name of the database table.
     * @param string $whereClause SQL that describes the WHERE Clause.
     * @param array $parameters  Binded parameters that belong to the where clause.
     * @param array $columnNames The column names to select, omit to select all columns.
     *
     * @return array The result as an array.
     */
    public function find($tableName, $whereClause, $parameters, $columnNames = null)
    {
        $sSql = 'SELECT ' . $this->buildColumns($columnNames) . ' ' .
            'FROM `' . $tableName . '` ' .
            'WHERE ' . $whereClause;
        $oStatement = $this->prepare($sSql);
        $oStatement->execute($parameters);

        return $oStatement->fetchAll();
    }

    /**
     * Prepares a statement to find rows on the database with a given where clause and pagination parameters.
     *
     * @param string $tableName   Name of the database table.
     * @param string $whereClause SQL that describes the WHERE clause.
     * @param array $columnNames The column names to select, omit to select all columns.
     *
     * @return \PDOStatement The statement prepared to execute the SELECT query.
     */
    public function prepareFindWithLimit($tableName, $whereClause, $columnNames = null)
    {
        $sSql = 'SELECT ' . $this->buildColumns($columnNames) . ' ' .
            'FROM `' . $tableName . '` ' .
            'WHERE ' . $whereClause . ' ' .
            'LIMIT :firstResult,:numResults';
        $oStatement = $this->prepare($sSql);

        return $oStatement;
    }

    /**
     * Find rows on the database with the given restrictions. The results are encapsulated in a ResultIterator that
     * only fetches the needed rows batchwise.
     *
     * @param string $tableName   Name of the database table.
     * @param string $whereClause SQL that describes the WHERE clause.
     * @param array $parameters  The parameters that belong to the where clause (excluding pagination parameters).
     * @param string $columnNames The column names to select, omit to select all columns.
     * @param int $batchSize   How many entries to fetch in one database query.
     *
     * @return PdoResultIterator An Iterator that transparently iterates over all results founds, refetching from database
     * if necessary.
     */
    public function findBatch(
        $tableName,
        $whereClause,
        $parameters,
        $columnNames = null,
        $batchSize = self::DEFAULT_BATCH_SIZE
    ) {
        $statement = $this->prepareFindWithLimit($tableName, $whereClause, $columnNames);

        return new PdoResultIterator($statement, $parameters, $batchSize);
    }

    private function buildColumns($columnNames)
    {
        if ($columnNames == null || 0 == count($columnNames)) {
            return '*';
        }

        return '`' . implode('`,`', $columnNames) . '`';
    }
}