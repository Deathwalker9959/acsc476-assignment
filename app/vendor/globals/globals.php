<?php

function dd()
{
    array_map(function ($x) {
        echo '<pre>' . htmlentities(json_encode($x, JSON_PRETTY_PRINT)) . '</pre>';
    }, func_get_args());
    die;
}
function dt()
{
    array_map(function ($x) {
        echo '<pre>' . htmlentities(json_encode($x, JSON_PRETTY_PRINT)) . '</pre>';
    }, func_get_args());
}
function camelToSnake($input)
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
}
function snakeToCamel($input)
{
    return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
}