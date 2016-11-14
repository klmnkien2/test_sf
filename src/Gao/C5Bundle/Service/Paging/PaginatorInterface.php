<?php

namespace Gao\C5Bundle\Service\Paging;

/**
 * PaginatorInterface interface.
 */
interface PaginatorInterface
{

    /**
     * Execute paginating for current page number.
     *
     * @param int $currentPageNumber The current page number.
     * @return Pagination The Pagination object.
     */
    public function paginate($currentPageNumber = 1);
}
