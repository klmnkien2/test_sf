<?php

namespace Gao\C5Bundle\Service;

/**
 * CommonUtility class.
 *
 * Utility class for common purpose.
 */
class CommonUtility
{

    /**
     * Padding zero at first of number.
     *
     * @param string $number The number or numberic string to pad.
     * @param int    $length The length of result string.
     *
     * @return string The String with zero padded.
     */
    public static function padZero($number, $length)
    {
        if (!is_numeric($number)) {
            return $number;
        }

        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }

    /**
     * roundNumber.
     *
     * @param mixed  $number
     * @param mixed  $length
     * @param string $mode
     */
    public static function roundNumber($number, $under_len, $mode = 'down')
    {
        $num = pow(10, $under_len);
        if ($mode === 'up') {
            return ceil($number * $num) / $num;
        } elseif ($mode === 'down') {
            return floor($number * $num) / $num;
        }
    }

    /**
     * get number which larger than zero.
     *
     * @param mixed $tmp
     *
     * @return positive number or null
     */
    public static function getPositiveNumber($tmp)
    {
        if (!is_null($tmp) && $tmp > 0) {
            return $tmp;
        } else {
            return null;
        }
    }

    /**
     * Remove trailing and leading zeros - just to return cleaner number
     *
     * @param float $num
     *
     * @return string
     */
    public static function numberClean($num)
    {
        //remove zeros from end of number ie. 140.00000 becomes 140.
        $clean = rtrim($num, '0');
        //remove decimal point if an integer ie. 140. becomes 140
        $clean = rtrim($clean, '.');

        return $clean;
    }

    /**
     * Get value indentify by KEY from array data
     *
     * @param  array $arr     array that keep data
     * @param  mixed $key     KEY to indentify data
     * @param  mixed $default Default of data
     * @return mixed value of data
     */
    public static function getVal($arr, $key, $default = null)
    {
        $ret = $default;
        if (is_array($arr)) {
            if (array_key_exists($key, $arr)) {
                $ret = $arr[$key];
            }
        } elseif ($arr instanceof \ArrayAccess) {
            //ArrayAccess not have array_key_exists function
            if (isset($arr[$key])) {
                $ret = $arr[$key];
            }
        }
        return $ret;
    }

}
