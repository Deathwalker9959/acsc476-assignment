<?php

namespace App;

use ReflectionMethod;
use ReflectionParameter;

// Define the base path of the application
define("ROUTES_DIR", __DIR__ . '/Routes/');

class Router
{

    private $requestURI;
    private $requestMethod;

    private function loadRoutes()
    {
        $files = array_diff(scandir(ROUTES_DIR), ['.', '..']);
        return array_map(function ($file) {
            $res = require_once ROUTES_DIR . $file;
            return $res;
        }, array_values($files));
    }

    private function parseModels($route)
    {
        dt($route);
        $ref = new ReflectionMethod($route["controllerClass"], $route["method"]);
        $params = $ref->getParameters();
        $calleParamClasses = array_map(function (ReflectionParameter $param) {
            return [
                "name" => $param?->getName(),
                "class  " => $param?->getDeclaringFunction(),
                "type" => $param?->getType()?->getName(),
            ];
        }, $params);
        dt($calleParamClasses);
    }

    private function handleRoute($route)
    {
        $this->parseModels($route);
        $isRaw = is_countable($_REQUEST) && count($_REQUEST) == 0;
        if ($isRaw) {
            // dt("raw");
        } else {
        }
        // call_user_func_array();
    }

    private function handleNotFound()
    {
        http_response_code(404);
        include_once(__DIR__ . '/Views/Error/404.php');
        exit;
    }

    private function parseRoute($values)
    {
        foreach ($values as $value) {
            $isGroup = isset($value["type"]) && $value["type"] === "group";
            $firstLevel = explode('/', $this->requestURI)[1];
            $realPath = explode($firstLevel, $this->requestURI)[1];

            if (
                $isGroup
                && isset($value['prefix']) && $firstLevel == $value['prefix']
                && isset($value['routes'][$realPath])
                && $value['routes'][$realPath]['httpMethod'] === $this->requestMethod
                || $isGroup
                && !isset($value['prefix'])
                && isset($value['routes'][$this->requestURI])
                && $value['routes'][$this->requestURI]['httpMethod'] === $this->requestMethod
            ) {
                return isset($value['prefix']) ? $value['routes'][$realPath] : $value['routes'][$this->requestURI];
            } elseif (
                isset($value[$this->requestURI])
                && $value[$this->requestURI]['httpMethod'] === $this->requestMethod
            ) {
                return $value[$this->requestURI];
            }
        };

        return false;
    }

    function __construct()
    {
        $this->requestURI = parse_url($_SERVER["REQUEST_URI"])['path'];
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $routes = $this->loadRoutes();
        // dt(file_get_contents('php://input'));
        // dt($_REQUEST);

        // dt(file_get_contents('php://input') == $_REQUEST);
        foreach ($routes as $route) {
            $res = call_user_func([$this, 'parseRoute'], $route);
            if ($res) {
                $this->handleRoute($res);
                break;
            }
        }

        $this->handleNotFound();

        // echo dd($routes);
    }
}

// class Router
// {

//     private 

//     function __construct()
//     {
//         // Define the default controller and action
//         $defaultController = 'HomeController';
//         $defaultAction = 'index';

//         // Parse the request URL to get the controller and action
//         $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//         $url = trim($url, '/');
//         $urlParts = explode('/', $url);

//         // Get the controller and action from the URL, or use the default values
//         $controller = $urlParts[0] ?: $defaultController;
//         $action = $urlParts[1] ?? $defaultAction;

//         // Get the query string parameters
//         $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
//         $queryParams = null;
//         if (isset($queryString))
//             parse_str($queryString, $queryParams);
//         // Construct the fully qualified class name for the controller
//         $controllerClass = "App\\Controllers\\{$controller}";

//         // Check if the controller class exists
//         if (!class_exists($controllerClass, false)) {
//             // Handle 404 error here...
//             http_response_code(404);
//             exit("404 Not Found");
//         }
//         // Create an instance of the controller and call the action method
//         $controller = new $controllerClass();
//         $controller->$action($queryParams);
//     }
// }
