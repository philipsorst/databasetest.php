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
     * @param string $sTableName   Name of the database table.
     * @param string $sWhereClause SQL that describes the WHERE Clause.
     * @param array  $aParameters  Binded parameters that belong to the where clause.
     * @param array  $aColumnNames The column names to select, omit to select all columns.
     *
     * @return array The result as an array.
     */
    public function find( $sTableName, $sWhereClause, $aParameters, $aColumnNames = null )
    {
        $sSql       = 'SELECT ' . $this->buildColumns( $aColumnNames ) . ' ' .
                      'FROM `' . $sTableName . '` ' .
                      'WHERE ' . $sWhereClause;
        $oStatement = $this->prepare( $sSql );
        $oStatement->execute( $aParameters );

        return $oStatement->fetchAll();
    }

    public function findWithLimit(
        $sTableName,
        $sWhereClause,
        $aParameters,
        $aColumnNames,
        $iFirstResult,
        $iNumResults )
    {
        $oStatement = $this->prepareFindWithLimit( $sTableName, $sWhereClause, $aColumnNames );
        $oStatement->bindParam( ':firstResult', $iFirstResult );
        $oStatement->bindParam( ':numResults', $iNumResults );
        $oStatement->execute( $aParameters );

        return $oStatement->fetchAll();
    }

    public function prepareFindWithLimit( $sTableName, $sWhereClause, $aColumnNames = null )
    {
        $sSql       = 'SELECT ' . $this->buildColumns( $aColumnNames ) . ' ' .
                      'FROM `' . $sTableName . '` ' .
                      'WHERE ' . $sWhereClause . ' ' .
                      'LIMIT :firstResult,:numResults';
        $oStatement = $this->prepare( $sSql );

        return $oStatement;
    }

    public function findBatch(
        $sTableName,
        $sWhereClause,
        $aParameters,
        $aColumnNames = null,
        $iBatchSize = self::DEFAULT_BATCH_SIZE )
    {
        $oStatement = $this->prepareFindWithLimit($sTableName, $sWhereClause, $aColumnNames);

        return new ResultIterator( $oStatement, $aParameters, $iBatchSize );
    }

    private function buildColumns( $aColumnNames )
    {
        if ( $aColumnNames == null || 0 == count( $aColumnNames ) ) {
            return '*';
        }

        return '`' . implode( '`,`', $aColumnNames ) . '`';
    }
}