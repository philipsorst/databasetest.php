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
     * @param string $tableName    Name of the database table.
     * @param string $whereClause  SQL that describes the WHERE Clause.
     * @param array  $parameters   Bound parameters that belong to the where clause.
     * @param array  $columnNames  The column names to select, omit to select all columns.
     *
     * @return array The result as an array.
     */
    public function find($tableName, $whereClause, $parameters = array(), $columnNames = null)
    {
        $sql = 'SELECT ' . $this->buildColumns($columnNames) . ' ' .
            'FROM `' . $tableName . '` ' .
            'WHERE ' . $whereClause;
        $statement = $this->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    /**
     * Prepares a statement to find rows on the database with a given where clause and pagination parameters.
     *
     * @param string $tableName   Name of the database table.
     * @param string $whereClause SQL that describes the WHERE clause.
     * @param array  $columnNames The column names to select, omit to select all columns.
     *
     * @return \PDOStatement The statement prepared to execute the SELECT query.
     */
    public function prepareFindWithLimit($tableName, $whereClause, $columnNames = null)
    {
        $sql = 'SELECT ' . $this->buildColumns($columnNames) . ' ' .
            'FROM `' . $tableName . '` ' .
            'WHERE ' . $whereClause . ' ' .
            'LIMIT :firstResult,:numResults';
        $statement = $this->prepare($sql);

        return $statement;
    }

    /**
     * Inserts a row into a table.
     *
     * @param string $tableName The name of the table to insert into.
     * @param array  $row       The row to insert as an associative array where the key is the column name and the value the value.
     *
     * @return int The number of affected rows (should always be 1 or something went wrong).
     */
    public function insert($tableName, $row)
    {
        $params = array();

        $sql = 'INSERT INTO `' . $tableName . '` (';

        $first = true;
        foreach ($row as $column => $value) {
            if (!$first) {
                $sql .= ',';
            }
            $sql .= '`' . $column . '`';
            $first = false;
        }

        $sql .= ') VALUES (';

        $first = true;
        foreach ($row as $column => $value) {
            if (!$first) {
                $sql .= ',';
            }
            $sql .= ':' . $column;
            $params[':' . $column] = $value;
            $first = false;
        }

        $sql .= ')';

        $statement = $this->prepare($sql);
        $statement->execute($params);

        return $statement->rowCount();
    }

    /**
     * Update values in a table based on where conditions.
     *
     * @param string $tableName The name of the table to update.
     * @param array  $values    The values to update as an associative array where the key is the column and the
     *                          value the value.
     * @param array  $where     The where clause to apply as an associative array where the key is the column and
     *                          the value the value, each parameter is evaluated for equality and all parameters
     *                          are used in conjunction.
     *
     * @return int The number of rows affected.
     */
    public function update($tableName, $values, $where)
    {
        $params = array();

        $sql = 'UPDATE ' . $tableName . ' SET ';

        $first = true;
        foreach ($values as $column => $value) {
            if (!$first) {
                $sql .= ",";
            }
            $params[':' . $column] = $value;
            $sql .= '`' . $column . '` = :' . $column;
            $first = false;
        }

        $sql .= " WHERE ";

        $first = true;
        foreach ($where as $column => $value) {
            if ($value === null) {
                continue;
            }
            if (!$first) {
                $sql .= " AND ";
            }
            $params[':where_' . $column] = $value;
            $sql .= '`' . $column . '` = :where_' . $column;
            $first = false;
        }

        $statement = $this->prepare($sql);
        $statement->execute($params);

        return $statement->rowCount();
    }

    /**
     * Find rows on the database with the given restrictions. The results are encapsulated in a ResultIterator that
     * only fetches the needed rows batchwise.
     *
     * @param string $tableName   Name of the database table.
     * @param string $whereClause SQL that describes the WHERE clause.
     * @param array  $parameters  The parameters that belong to the where clause (excluding pagination parameters).
     * @param string $columnNames The column names to select, omit to select all columns.
     * @param int    $batchSize   How many entries to fetch in one database query.
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