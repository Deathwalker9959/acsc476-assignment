<?php

namespace App;
// Define the base path of the application
define("ROUTES_DIR", __DIR__ . '/Routes/');

class Router {

    private function loadRoutes()
    {
        $files = array_diff(scandir(ROUTES_DIR), ['.', '..']);
        return array_map(function ($file) {
            $res = require_once ROUTES_DIR . $file;
            return $res;
        }, $files);
    }

    function __construct()
    {
       $routes = $this->loadRoutes();
       dd($routes);
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
