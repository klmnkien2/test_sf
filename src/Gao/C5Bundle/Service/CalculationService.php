<?php

namespace Gao\C5Bundle\Service;

/**
 * CalculationService class.
 *
 * Function to calculate value define from logic.
 */
class CalculationService
{

    /**
     * Logic sum to value.
     *
     * @param int $x.
     * @param int $y.
     *
     * @return int sum.
     */
    public static function example($x, $y)
    {
        return $x + $y;
    }
}
