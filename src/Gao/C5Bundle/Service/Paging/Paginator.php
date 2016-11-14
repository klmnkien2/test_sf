<?php

namespace Gao\C5Bundle\Service\Paging;

use Gao\C5Bundle\Service\Paging\PaginatorInterface;

/**
 * Paginator class.
 * 
 * Class for executing pagination.
 */
class Paginator implements PaginatorInterface
{

    /**
     * Total bumber of all items.
     */
    private $totalNumberOfItems;

    /**
     * List of all items displayed in the current page.
     */
    private $itemsInCurrentPage;

    /**
     * Limit number of items per page.
     */
    private $itemsLimitPerPage = 30;

    /**
     * Limit number of pages ing the range.
     */
    private $pagesLimitInRange = 5;

    /**
     * Executing pagination.
     * 
     * @param integer $currentPageNumber The current page number.
     * @return Pagination The Pagination object.
     * @throws \Exception If not success.
     */
    public function paginate($currentPageNumber = 1)
    {
        $totalNumberOfItems = $this->totalNumberOfItems;

        $totalNumberOfPages = (int) ceil($totalNumberOfItems / $this->itemsLimitPerPage);
        if ($currentPageNumber > $totalNumberOfPages || $currentPageNumber <= 0) {
            throw new \Exception('$currentPageNumber must be the range(from 1 to the last page number).');
        }

        // Identify actual PagesLimitInRange.
        $pagesLimitInRange = $this->pagesLimitInRange;

        if ($pagesLimitInRange > $totalNumberOfPages) {
            $pagesLimitInRange = $totalNumberOfPages;
        }

        $variance = (int) ceil($pagesLimitInRange / 2);

        if (($currentPageNumber - $variance) > ($totalNumberOfPages - $pagesLimitInRange)) {
            $pages = range(($totalNumberOfPages - $pagesLimitInRange) + 1, $totalNumberOfPages);
        } else {
            if (($currentPageNumber - $variance) < 0) {
                $variance = $currentPageNumber;
            }

            $offset = $currentPageNumber - $variance;
            $pages = range(($offset + 1), $offset + $pagesLimitInRange);
        }

        $offset = ($currentPageNumber - 1) * $this->itemsLimitPerPage;

        // Identify actual ItemsInCurrentPage.
        $itemsInCurrentPage = $this->getItemsInCurrentPage();
        if (count($itemsInCurrentPage) > $this->itemsLimitPerPage) {
            $itemsInCurrentPage = array_slice($itemsInCurrentPage, 0, $this->itemsLimitPerPage);
        }

        $previousPageNumber = null;
        if ($currentPageNumber > 1) {
            $previousPageNumber = $currentPageNumber - 1;
        }

        $nextPageNumber = null;
        if ($currentPageNumber < $totalNumberOfPages) {
            $nextPageNumber = $currentPageNumber + 1;
        }

        // Get the first, last item number in current page.
        $firstItemNumberInCurrentPage = ($currentPageNumber - 1) * $this->itemsLimitPerPage + 1;
        if ($currentPageNumber == $totalNumberOfPages) {
            $lastItemNumberInCurrentPage = $totalNumberOfItems;
        } else {
            $lastItemNumberInCurrentPage = $firstItemNumberInCurrentPage + $this->itemsLimitPerPage - 1;
        }

        // Get the first, last item number in next page.
        $firstItemNumberInNextPage = 0;
        $lastItemNumberInNextPage = 0;
        if ($currentPageNumber < $totalNumberOfPages) {
            $firstItemNumberInNextPage = $currentPageNumber * $this->itemsLimitPerPage + 1;

            if ($currentPageNumber == $totalNumberOfPages - 1) {
                $lastItemNumberInNextPage = $totalNumberOfItems;
            } else {
                $lastItemNumberInNextPage = $firstItemNumberInNextPage + $this->itemsLimitPerPage - 1;
            }
        }

        // Build Paginating object.
        $pagination = new Pagination();
        $pagination
            ->setItemsInCurrentPage($itemsInCurrentPage)
            ->setPagesInRange($pages)
            ->setTotalNumberOfPages($totalNumberOfPages)
            ->setCurrentPageNumber($currentPageNumber)
            ->setFirstPageNumber(1)
            ->setLastPageNumber($totalNumberOfPages)
            ->setPreviousPageNumber($previousPageNumber)
            ->setNextPageNumber($nextPageNumber)
            ->setItemsLimitPerPage($this->itemsLimitPerPage)
            ->setTotalNumberOfItems($totalNumberOfItems)
            ->setFirstPageNumberInRange(min($pages))
            ->setLastPageNumberInRange(max($pages))
            ->setFirstItemNumberInCurrentPage($firstItemNumberInCurrentPage)
            ->setLastItemNumberInCurrentPage($lastItemNumberInCurrentPage)
            ->setFirstItemNumberInNextPage($firstItemNumberInNextPage)
            ->setLastItemNumberInNextPage($lastItemNumberInNextPage)
        ;

        return $pagination;
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
     * Get the items in current page.
     * 
     * @return mixed The items in current page.
     */
    public function getItemsInCurrentPage()
    {
        return $this->itemsInCurrentPage;
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
     * Get the pages limit in range.
     * 
     * @return integer The pages limit in range.
     */
    public function getPagesLimitInRange()
    {
        return $this->pagesLimitInRange;
    }

    /**
     * Set the total number of items.
     * 
     * @param integer $totalNumberOfItems The total number of items.
     * @return Paginator The Paginator object.
     */
    public function setTotalNumberOfItems($totalNumberOfItems)
    {
        $this->totalNumberOfItems = $totalNumberOfItems;
        return $this;
    }

    /**
     * Set the items in current page.
     * 
     * @param integer $itemsInCurrentPage The items in current page.
     * @return Paginator The Paginator object.
     */
    public function setItemsInCurrentPage($itemsInCurrentPage)
    {
        $this->itemsInCurrentPage = $itemsInCurrentPage;
        return $this;
    }

    /**
     * Set the items limit per page.
     * 
     * @param integer $itemsLimitPerPage The items limit per page.
     * @return Paginator The Paginator object.
     */
    public function setItemsLimitPerPage($itemsLimitPerPage)
    {
        $this->itemsLimitPerPage = $itemsLimitPerPage;
        return $this;
    }

    /**
     * Set the pages limit in range.
     * 
     * @param integer $pagesLimitInRange The pages limit in range.
     * @return Paginator The Paginator object.
     */
    public function setPagesLimitInRange($pagesLimitInRange)
    {
        $this->pagesLimitInRange = $pagesLimitInRange;
        return $this;
    }
}
