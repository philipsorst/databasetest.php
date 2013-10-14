<?php


namespace net\dontdrinkandroot\database;

use \Iterator;
use \PDOStatement;
use \PDO;

class ResultIterator implements Iterator
{

    private $_oStatement;
    private $_aParameters;
    private $_iBatchSize;
    private $_aCurrentResult = null;
    private $_iCurrentPage = 0;
    private $_iRowNum = 0;

    /**
     * Constructs a new ResultIterator.
     *
     * @param PDOStatement $oStatement  The statement to iterate through.
     * @param array        $aParameters The parameters of the statement (excluding the pagination parameters.)
     * @param int          $iBatchSize  The number of entries per window.
     */
    public function __construct( PDOStatement $oStatement, $aParameters, $iBatchSize )
    {
        $this->_oStatement  = $oStatement;
        $this->_aParameters = $aParameters;
        $this->_iBatchSize  = $iBatchSize;
    }

    /**
     * Get the current database row.
     *
     * @return array The database row.
     */
    public function current()
    {
        $this->fetchIfNeeded();

        return $this->_aCurrentResult[ $this->getRelativeRowNum() ];
    }

    /**
     * Get the current absolute row number.
     *
     * @return int The current row number.
     */
    public function key()
    {
        return $this->_iRowNum;
    }

    public function next()
    {
        $this->_iRowNum++;
    }

    public function rewind()
    {
        $this->_iRowNum        = 0;
        $this->_iCurrentPage   = 0;
        $this->_aCurrentResult = null;
    }

    public function valid()
    {
        $this->fetchIfNeeded();

        return
            !empty( $this->_aCurrentResult ) && array_key_exists( $this->getRelativeRowNum(), $this->_aCurrentResult );
    }

    /**
     * Checks if the next window needs to be fetched and fetches it if required.
     */
    private function fetchIfNeeded()
    {
        if ( is_null( $this->_aCurrentResult ) ) {
            $this->_aCurrentResult = $this->fetchPage( $this->_iCurrentPage );
        }

        if ( $this->_iRowNum >= $this->_iCurrentPage * $this->_iBatchSize + $this->_iBatchSize ) {
            $this->_iCurrentPage   = $this->getPageNum( $this->_iRowNum );
            $this->_aCurrentResult = $this->fetchPage( $this->_iCurrentPage );
        }
    }

    /**
     * Fetches the results for the given page.
     *
     * @param int $iPageNum The page to fetch the results for.
     *
     * @return array The rows in the page.
     */
    private function fetchPage( $iPageNum )
    {
        if ( !is_null( $this->_aParameters ) && is_array( $this->_aParameters ) ) {
            foreach ( $this->_aParameters as $name => $value ) {
                $this->_oStatement->bindParam( $name, $value );
            }
        }
        $iFirstResult = $iPageNum * $this->_iBatchSize;
        $this->_oStatement->bindValue( ":firstResult", $iFirstResult, PDO::PARAM_INT );
        $this->_oStatement->bindValue( ":numResults", $this->_iBatchSize, PDO::PARAM_INT );

        $this->_oStatement->execute();

        return $this->_oStatement->fetchAll();
    }

    /**
     * Get the page number that corresponds to the given row.
     *
     * @param int $iRowNum The row number to compute the page for.
     *
     * @return int The page for the row number.
     */
    private function getPageNum( $iRowNum )
    {
        return (int)( $iRowNum / $this->_iBatchSize );
    }

    /**
     * Get the row number relative to the current window (starting at 0).
     *
     * @return int The relative row number.
     */
    private function getRelativeRowNum()
    {
        return $this->_iRowNum % $this->_iBatchSize;
    }
}