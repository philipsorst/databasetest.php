<?php


namespace net\dontdrinkandroot\database;
use \PDO;

/**
 * PDO Abstraction that encapsulates a database. Can be subclassed to implement dialect specific implementations.
 * @package net\dontdrinkandroot\database
 */
class Database extends PDO
{

    /**
     * Find rows on the database with the given restrictions.
     *
     * @param $sTableName Name of the database table.
     * @param $sWhereClause SQL that describes the WHERE Clause.
     * @param $aParameters Binded parameters that belong to the where clause.
     * @param array $aColumnNames The column names to select, omit to select all columns.
     * @return array The result as an array.
     */
    public function find($sTableName, $sWhereClause, $aParameters, $aColumnNames = null)
    {
        $sSql = 'SELECT ' . $this->buildColumns($aColumnNames) . ' FROM `' . $sTableName . '` ' .
                'WHERE ' . $sWhereClause;
        $oStatement = $this->prepare($sSql);
        $oStatement->execute($aParameters);

        return $oStatement->fetchAll();
    }

    private function buildColumns($aColumnNames)
    {
        if ($aColumnNames == null || 0 == count($aColumnNames)) {
            return '*';
        }

        return '`' . implode('`,`', $aColumnNames) . '`';
    }

}