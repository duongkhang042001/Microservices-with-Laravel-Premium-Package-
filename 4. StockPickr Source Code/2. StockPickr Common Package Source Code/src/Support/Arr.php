<?php

namespace StockPickr\Common\Support;

use Illuminate\Support\Arr as SupportArr;
use Illuminate\Support\Collection;

final class Arr extends SupportArr
{
    public static function objectToArray(mixed $obj): mixed
    {        
        if (is_object($obj) && !($obj instanceof Collection)) {
            $obj = (array) $obj;
        }

        if (is_array($obj)) {
            $array = [];
            foreach($obj as $key => $val) {
                if (is_scalar($val)) {
                    $array[$key] = $val;
                } else {
                    $array[$key] = static::objectToArray($val);
                }
            }
        } elseif ($obj instanceof Collection) {
            $array = $obj->all();
        } else {
            $array = $obj;
        }

        return $array;
    }
}