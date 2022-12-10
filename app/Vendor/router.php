<?php

namespace App;
// Define the base path of the application
define('BASE_PATH', __DIR__);

class Router
{
    function __construct()
    {
        // Define the default controller and action
        $defaultController = 'HomeController';
        $defaultAction = 'index';

        // Parse the request URL to get the controller and action
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = trim($url, '/');
        $urlParts = explode('/', $url);

        // Get the controller and action from the URL, or use the default values
        $controller = $urlParts[0] ?: $defaultController;
        $action = $urlParts[1] ?? $defaultAction;

        // Get the query string parameters
        $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $queryParams = null;
        if (isset($queryString))
            parse_str($queryString, $queryParams);
        // Construct the fully qualified class name for the controller
        $controllerClass = "App\\Controllers\\{$controller}";

        // Check if the controller class exists
        if (!class_exists($controllerClass, false)) {
            // Handle 404 error here...
            http_response_code(404);
            exit("404 Not Found");
        }
        // Create an instance of the controller and call the action method
        $controller = new $controllerClass();
        $controller->$action($queryParams);
    }
}
