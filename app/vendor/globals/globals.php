<?php

use App\Database\Transaction;
use App\Router\Response;
use App\TransactionSingleton;

/**
 * Converts all string values in an array to UTF-8 encoding
 *
 * @param mixed $d The input array or string
 * @return mixed The input array with all string values converted to UTF-8 encoding
 */
function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}

/**
 * Prints the variable(s) and ends the script
 *
 * @param mixed ...$vars The variable(s) to print and end the script with
 */
function dd(): void
{
    dt(func_get_args());
    die;
}

/**
 * Prints the variable(s)
 *
 * @param mixed ...$vars The variable(s) to print
 */
function dt()
{
    array_map(function ($x) {
        if (is_array($x)) {
            foreach ($x as $y) {
                dt($y);
            }
        } elseif (is_object($x)) {
            if (method_exists($x, 'get_object_vars')) {
                $x = $x->get_object_vars();
            } else {
                // If the argument is an object, get its protected and private
                // properties and make them accessible.
                $reflectionObject = new ReflectionObject($x);
                $properties = $reflectionObject->getProperties(ReflectionProperty::IS_PROTECTED);
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $x->{$property->getName()} = $property->getValue($x);
                }
            }
            echo '<pre>' . htmlentities(json_encode(utf8ize($x), JSON_PRETTY_PRINT)) . '</pre>';
        }
    }, func_get_args());
}


/**
 * Converts a camelCase string to snake_case
 *
 * @param string $input The input string in camelCase
 * @return string The input string in snake_case
 */
function camelToSnake($input): string
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
}

/**
 * Converts a snake_case string to camelCase
 *
 * @param string $input The input string in snake_case
 * @return string The input string in camelCase
 */
function snakeToCamel($input): string
{
    return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
}

/**
 * Pluralizes a word.
 * @param string $word The word to pluralize.
 * @return string The pluralized word.
 */
function pluralize($word)
{
    // Check if the word ends in 'y'
    if (substr($word, -1) === 'y') {
        // If it does, replace the 'y' with 'ies'
        $plural = substr_replace($word, 'ies', -1);
    } else {
        // Otherwise, just add an 's' to the end of the word
        $plural = $word . 's';
    }
    return $plural;
}
/**
 * Creates and returns a new Response object
 *
 * @return Response The new Response object
 */
function response(): Response
{
    return new Response();
}

/**
 * Creates and returns a new Transaction object
 *
 * @return Transaction The new Transaction object
 */
function transaction(): Transaction
{
    return TransactionSingleton::getInstance()->getTransaction();
}

/**
 * Redirects to the previous route.
 *
 * @param string $fallback The fallback URL to use if the previous route cannot be determined.
 */
function back($fallback = '/')
{
    // Check if the previous URL is stored in the session
    if (isset($_SESSION['prev_url'])) {
        // Redirect to the previous URL
        header('Location: ' . $_SESSION['prev_url']);
        exit;
    } else {
        // Redirect to the fallback URL
        header('Location: ' . $fallback);
        exit;
    }
}
