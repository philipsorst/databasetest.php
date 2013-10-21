<?php


namespace net\dontdrinkandroot\database;

use \Iterator;
use \PDOStatement;
use \PDO;

class ResultIterator implements Iterator
{

    private $statement;
    private $parameters;
    private $batchSize;
    private $currentResult = null;
    private $currentPage = 0;
    private $rowNum = 0;

    /**
     * Constructs a new ResultIterator.
     *
     * @param PDOStatement $statement  The statement to iterate through.
     * @param array $parameters The parameters of the statement (excluding the pagination parameters.)
     * @param int $batchSize  The number of entries per window.
     */
    public function __construct(PDOStatement $statement, $parameters, $batchSize)
    {
        $this->statement = $statement;
        $this->parameters = $parameters;
        $this->batchSize = $batchSize;
    }

    /**
     * Get the current database row.
     *
     * @return array The database row.
     */
    public function current()
    {
        $this->fetchIfNeeded();

        return $this->currentResult[$this->getRelativeRowNum()];
    }

    /**
     * Get the current absolute row number.
     *
     * @return int The current row number.
     */
    public function key()
    {
        return $this->rowNum;
    }

    public function next()
    {
        $this->rowNum++;
    }

    public function rewind()
    {
        $this->rowNum = 0;
        $this->currentPage = 0;
        $this->currentResult = null;
    }

    public function valid()
    {
        $this->fetchIfNeeded();

        return
            !empty($this->currentResult) && array_key_exists($this->getRelativeRowNum(), $this->currentResult);
    }

    /**
     * Checks if the next window needs to be fetched and fetches it if required.
     */
    private function fetchIfNeeded()
    {
        if (is_null($this->currentResult)) {
            $this->currentResult = $this->fetchPage($this->currentPage);
        }

        if ($this->rowNum >= $this->currentPage * $this->batchSize + $this->batchSize) {
            $this->currentPage = $this->getPageNum($this->rowNum);
            $this->currentResult = $this->fetchPage($this->currentPage);
        }
    }

    /**
     * Fetches the results for the given page.
     *
     * @param int $pageNum The page to fetch the results for.
     *
     * @return array The rows in the page.
     */
    private function fetchPage($pageNum)
    {
        if (!is_null($this->parameters) && is_array($this->parameters)) {
            foreach ($this->parameters as $name => $value) {
                $this->statement->bindParam($name, $value);
            }
        }
        $firstResult = $pageNum * $this->batchSize;
        $this->statement->bindValue(":firstResult", $firstResult, PDO::PARAM_INT);
        $this->statement->bindValue(":numResults", $this->batchSize, PDO::PARAM_INT);

        $this->statement->execute();

        return $this->statement->fetchAll();
    }

    /**
     * Get the page number that corresponds to the given row.
     *
     * @param int $rowNum The row number to compute the page for.
     *
     * @return int The page for the row number.
     */
    private function getPageNum($rowNum)
    {
        return (int)($rowNum / $this->batchSize);
    }

    /**
     * Get the row number relative to the current window (starting at 0).
     *
     * @return int The relative row number.
     */
    private function getRelativeRowNum()
    {
        return $this->rowNum % $this->batchSize;
    }
}