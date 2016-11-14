<?php

namespace Gao\C5Bundle\Service\Paging;

/**
 * Pagination class.
 * 
 * Class for store pagination information.
 */
class Pagination
{

    /**
     * List of all items.
     */
    private $items;

    /**
     * List of all items displayed in the current page.
     */
    private $itemsInCurrentPage;

    /**
     * Total number of all pages.
     */
    private $totalNumberOfPages;

    /**
     * List of all pages displayed in the range.
     */
    private $pagesInRange;

    /**
     * Current page number.
     */
    private $currentPageNumber;

    /**
     * First page number.
     */
    private $firstPageNumber;

    /**
     * Last page number.
     */
    private $lastPageNumber;

    /**
     * Previous page number.
     */
    private $previousPageNumber;

    /**
     * Next page number.
     */
    private $nextPageNumber;

    /**
     * Items limit per page.
     */
    private $itemsLimitPerPage;

    /**
     * Total number of items.
     */
    private $totalNumberOfItems;

    /**
     * First page number in range.
     */
    private $firstPageNumberInRange;

    /**
     * Last page number in range.
     */
    private $lastPageNumberInRange;

    /**
     * First item number in current page.
     */
    private $firstItemNumberInCurrentPage;

    /**
     * Last item number in current page.
     */
    private $lastItemNumberInCurrentPage;

    /**
     * First item number in next page.
     */
    private $firstItemNumberInNextPage;

    /**
     * Last item number in next page.
     */
    private $lastItemNumberInNextPage;

    /**
     * Total text.
     */
    private $textTotal = '件中';

    /**
     * FromToDisplay text.
     */
    private $textFromToDisplay = '件を表示';

    /**
     * Previous text.
     */
    private $textPrevious = '前へ';

    /**
     * Next text.
     */
    private $textNext = '次ヘ';

    /**
     * The page parameter in url query.
     */
    private $pageParameterName = 'page';

    /**
     * Get the items.
     * @return mixed The items.
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get the items in current page.
     * 
     * @return mixed The items in current page.
     */
    public function getItemsInCurrentPage()
    {
        return $this->itemsInCurrentPage;
    }

    /**
     * Get the total number of pages.
     * 
     * @return integer The total number of pages.
     */
    public function getTotalNumberOfPages()
    {
        return $this->totalNumberOfPages;
    }

    /**
     * Get the pages in range.
     * 
     * @return mixed The pages in range.
     */
    public function getPagesInRange()
    {
        return $this->pagesInRange;
    }

    /**
     * Get the current page number.
     * 
     * @return integer The current page number.
     */
    public function getCurrentPageNumber()
    {
        return $this->currentPageNumber;
    }

    /**
     * Get the first page number.
     * 
     * @return integer The first page number.
     */
    public function getFirstPageNumber()
    {
        return $this->firstPageNumber;
    }

    /**
     * Get the last page number.
     * 
     * @return integer The last page number.
     */
    public function getLastPageNumber()
    {
        return $this->lastPageNumber;
    }

    /**
     * Get the previous page number.
     * 
     * @return integer The previous page number.
     */
    public function getPreviousPageNumber()
    {
        return $this->previousPageNumber;
    }

    /**
     * Get the next page number.
     * 
     * @return integer The next page number.
     */
    public function getNextPageNumber()
    {
        return $this->nextPageNumber;
    }

    /**
     * Get the items limit per page.
     * 
     * @return integer The items limit per page.
     */
    public function getItemsLimitPerPage()
    {
        return $this->itemsLimitPerPage;
    }

    /**
     * Get the total number of items.
     * 
     * @return integer The total number of items.
     */
    public function getTotalNumberOfItems()
    {
        return $this->totalNumberOfItems;
    }

    /**
     * Get the first page number in range.
     * 
     * @return integer The first page number in range.
     */
    public function getFirstPageNumberInRange()
    {
        return $this->firstPageNumberInRange;
    }

    /**
     * Get the last page number in range.
     * 
     * @return integer The last page number in range.
     */
    public function getLastPageNumberInRange()
    {
        return $this->lastPageNumberInRange;
    }

    /**
     * Get the first item number in current page.
     * 
     * @return integer The first item number in current page.
     */
    public function getFirstItemNumberInCurrentPage()
    {
        return $this->firstItemNumberInCurrentPage;
    }

    /**
     * Get the last item number in current page.
     * 
     * @return integer The last item number in current page.
     */
    public function getLastItemNumberInCurrentPage()
    {
        return $this->lastItemNumberInCurrentPage;
    }

    /**
     * Get the first item number in next page.
     * 
     * @return integer The first item number in next page.
     */
    public function getFirstItemNumberInNextPage()
    {
        return $this->firstItemNumberInNextPage;
    }

    /**
     * Get the last item number in next page.
     * 
     * @return integer The last item number in next page.
     */
    public function getLastItemNumberInNextPage()
    {
        return $this->lastItemNumberInNextPage;
    }

    /**
     * Get the total text.
     * 
     * @return string The total text.
     */
    public function getTextTotal()
    {
        return $this->textTotal;
    }

    /**
     * Get the from to display text.
     * 
     * @return string The from to display text.
     */
    public function getTextFromToDisplay()
    {
        return $this->textFromToDisplay;
    }

    /**
     * Get the previous text.
     * 
     * @return string The previous text.
     */
    public function getTextPrevious()
    {
        return $this->textPrevious;
    }

    /**
     * Get the next text.
     * 
     * @return string The next text.
     */
    public function getTextNext()
    {
        return $this->textNext;
    }

    /**
     * Get the page parameter name.
     * 
     * @return string The page parameter name.
     */
    public function getPageParameterName()
    {
        return $this->pageParameterName;
    }

    /**
     * Set the items.
     * 
     * @param mixed $items The items.
     * @return Pagination The Pagination object.
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Set the items in current page.
     * 
     * @param mixed $itemsInCurrentPage The items in current page.
     * @return Pagination The Pagination object.
     */
    public function setItemsInCurrentPage($itemsInCurrentPage)
    {
        $this->itemsInCurrentPage = $itemsInCurrentPage;
        return $this;
    }

    /**
     * Set the total number of pages.
     * 
     * @param iinteger $totalNumberOfPages The total number of pages.
     * @return Pagination The Pagination object.
     */
    public function setTotalNumberOfPages($totalNumberOfPages)
    {
        $this->totalNumberOfPages = $totalNumberOfPages;
        return $this;
    }

    /**
     * Set the pages in range.
     * 
     * @param mixe $pagesInRange The pages in range.
     * @return Pagination The Pagination object.
     */
    public function setPagesInRange($pagesInRange)
    {
        $this->pagesInRange = $pagesInRange;
        return $this;
    }

    /**
     * Set the curernt page number.
     * 
     * @param integer $currentPageNumber The curernt page number.
     * @return Pagination The Pagination object.
     */
    public function setCurrentPageNumber($currentPageNumber)
    {
        $this->currentPageNumber = $currentPageNumber;
        return $this;
    }

    /**
     * Set the first page number.
     * 
     * @param integer $firstPageNumber The first page number.
     * @return Pagination The Pagination object.
     */
    public function setFirstPageNumber($firstPageNumber)
    {
        $this->firstPageNumber = $firstPageNumber;
        return $this;
    }

    /**
     * Set the last page number.
     * 
     * @param integer $lastPageNumber The last page number.
     * @return Pagination The Pagination object.
     */
    public function setLastPageNumber($lastPageNumber)
    {
        $this->lastPageNumber = $lastPageNumber;
        return $this;
    }

    /**
     * Set the previous page number.
     * 
     * @param integer $previousPageNumber The previous page number.
     * @return Pagination The Pagination object.
     */
    public function setPreviousPageNumber($previousPageNumber)
    {
        $this->previousPageNumber = $previousPageNumber;
        return $this;
    }

    /**
     * Set the next page number.
     * 
     * @param integer $nextPageNumber The next page number.
     * @return Pagination The Pagination object.
     */
    public function setNextPageNumber($nextPageNumber)
    {
        $this->nextPageNumber = $nextPageNumber;
        return $this;
    }

    /**
     * Set the items limit per page.
     * 
     * @param integer $itemsLimitPerPage the items limit per page.
     * @return Pagination The Pagination object.
     */
    public function setItemsLimitPerPage($itemsLimitPerPage)
    {
        $this->itemsLimitPerPage = $itemsLimitPerPage;
        return $this;
    }

    /**
     * Set the total number of items.
     * 
     * @param integer $totalNumberOfItems The total number of items.
     * @return Pagination The Pagination object.
     */
    public function setTotalNumberOfItems($totalNumberOfItems)
    {
        $this->totalNumberOfItems = $totalNumberOfItems;
        return $this;
    }

    /**
     * Set the first page number in range.
     * 
     * @param integer $firstPageNumberInRange The first page number in range.
     * @return Pagination The Pagination object.
     */
    public function setFirstPageNumberInRange($firstPageNumberInRange)
    {
        $this->firstPageNumberInRange = $firstPageNumberInRange;
        return $this;
    }

    /**
     * Set the last page number in range.
     * 
     * @param integer $lastPageNumberInRange The last page number in range.
     * @return Pagination The Pagination object.
     */
    public function setLastPageNumberInRange($lastPageNumberInRange)
    {
        $this->lastPageNumberInRange = $lastPageNumberInRange;
        return $this;
    }

    /**
     * Set the first item number in current page.
     * 
     * @param integer $firstItemNumberInCurrentPage The first item number in current page.
     * @return Pagination The Pagination object.
     */
    public function setFirstItemNumberInCurrentPage($firstItemNumberInCurrentPage)
    {
        $this->firstItemNumberInCurrentPage = $firstItemNumberInCurrentPage;
        return $this;
    }

    /**
     * Set the last item number in current page.
     * 
     * @param integer $lastItemNumberInCurrentPage The last item number in current page.
     * @return Pagination The Pagination object.
     */
    public function setLastItemNumberInCurrentPage($lastItemNumberInCurrentPage)
    {
        $this->lastItemNumberInCurrentPage = $lastItemNumberInCurrentPage;
        return $this;
    }

    /**
     * Set the first item number in next page.
     * 
     * @param integer $firstItemNumberInNextPage The first item number in next page.
     * @return Pagination The Pagination object.
     */
    public function setFirstItemNumberInNextPage($firstItemNumberInNextPage)
    {
        $this->firstItemNumberInNextPage = $firstItemNumberInNextPage;
        return $this;
    }

    /**
     * Set the last item number in next page.
     * 
     * @param integer $lastItemNumberInNextPage The last item number in next page.
     * @return Pagination The Pagination object.
     */
    public function setLastItemNumberInNextPage($lastItemNumberInNextPage)
    {
        $this->lastItemNumberInNextPage = $lastItemNumberInNextPage;
        return $this;
    }

    /**
     * Set the total text.
     * 
     * @param string $textTotal The total text.
     * @return Pagination The Pagination object.
     */
    public function setTextTotal($textTotal)
    {
        $this->textTotal = $textTotal;
        return $this;
    }

    /**
     * Set the from to display text.
     * 
     * @param string $textFromToDisplay The from to display text.
     * @return Pagination The Pagination object.
     */
    public function setTextFromToDisplay($textFromToDisplay)
    {
        $this->textFromToDisplay = $textFromToDisplay;
        return $this;
    }

    /**
     * Set the previous text.
     * 
     * @param string $textPrevious The previous text.
     * @return Pagination The Pagination object.
     */
    public function setTextPrevious($textPrevious)
    {
        $this->textPrevious = $textPrevious;
        return $this;
    }

    /**
     * Set the next text.
     * 
     * @param string $textNext The next text.
     * @return Pagination The Pagination object.
     */
    public function setTextNext($textNext)
    {
        $this->textNext = $textNext;
        return $this;
    }

    /**
     * Set the page parameter name.
     * 
     * @param string $pageParameterName The page parameter name.
     * @return Pagination The Pagination object.
     */
    public function setPageParameterName($pageParameterName)
    {
        $this->pageParameterName = $pageParameterName;
        return $this;
    }
}
