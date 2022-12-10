<?php

class Debug
{
    public static function dd()
    {
        array_map(function ($x) {
            echo print_r($x, true);
        }, func_get_args());
        die;
    }
}
