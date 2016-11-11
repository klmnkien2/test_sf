<?php

/**
 * Common Business Exception.
 *
 * PHP version 5.5
 *
 * @category  Biz
 */

namespace Gao\C5Bundle\Biz;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * BizException class.
 *
 * Exception class is used in Business(Biz) functions.
 */
class BizException extends Exception
{
    public $redirectResponse;
}
