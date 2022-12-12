<?php

function dd()
{
    array_map(function ($x) {
        echo '<pre>' . htmlentities(json_encode($x, JSON_PRETTY_PRINT)) . '</pre>';
    }, func_get_args());
    die;
}