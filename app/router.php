<?php

namespace App;

use App\Models\Model;
use App\Router\Request;
use App\Router\Response;
use Exception;
use ReflectionMethod;
use ReflectionParameter;

use function PHPSTORM_META\type;

// Define the base path of the application
define("ROUTES_DIR", __DIR__ . '/Routes/');

class Router
{

    private $requestURI;
    private $requestMethod;

    private $request;

    private function loadRoutes()
    {
        $files = array_diff(scandir(ROUTES_DIR), ['.', '..']);
        return array_map(function ($file) {
            $res = require_once ROUTES_DIR . $file;
            return $res;
        }, array_values($files));
    }

    private function getControllerModels($route)
    {
        $ref = new ReflectionMethod($route["controllerClass"], $route["method"]);
        $params = $ref->getParameters();
        $calleParamClasses = array_map(function (ReflectionParameter $param) {
            return [
                "name" => $param?->getName(),
                "class  " => $param?->getDeclaringFunction(),
                "type" => $param?->getType()?->getName(),
            ];
        }, $params);
        return $calleParamClasses;
    }

    private function classToPrototype($className)
    {
        preg_match('/([^\\\]+)$/', $className, $matches);
        return lcfirst($matches[1]);
    }
    private function mapParameters(array $controllerModels, array $routeModels): array
    {
        return array_values(array_filter(array_map(function (array $model) use ($routeModels) {
            if ($model['type'] == Request::class) {
                return new Request();
            }
            // Get the last part of the class name
            $modelType = $this->classToPrototype($model['type']);
            // If the model exists in the route models, return it
            return $routeModels[$modelType] ?? null;
        }, $controllerModels)));
    }

    private function handleRoute($route, $group)
    {
        $controllerModels = $this->getControllerModels($route);
        $routeModels = $this->fetchRouteModels($route);

        // Check if the route has any middleware defined
        if (isset($group['middleware'])) {
            // Loop through the middleware for the route
            foreach ($group['middleware'] as $middleware) {
                // Call the middleware class
                $qualifiedClass = "App\\Middleware\\" . $middleware;
                $middlewareClass = new $qualifiedClass;

                // Check if the middleware has a handle method
                if (method_exists($middlewareClass, 'handle')) {

                    // If the handle method returns false, stop processing the route and return a response
                    $resp = $middlewareClass->handle($this->request);
                    if (gettype($resp) == "object" && $resp::class == Response::class) {
                        return $resp->send();
                    }
                }
            }
        }

        // If no middleware returned false, call the route's controller method

        $resp = call_user_func_array([$route['controllerClass'], $route["method"]], $this->mapParameters($controllerModels, $routeModels));
        if (gettype($resp) == 'object' && $resp::class == Response::class) {
            return $resp->send();
        }

        return $resp;
    }

    private function handleNotFound()
    {
        return (new Response())->status(404)->view("Error.404")->send();
    }

    private function extractTokensFromRoute($route, $prefixes)
    {
        // Check if the request URI has a prefix (e.g. /api/users)
        $firstLevel = isset($group);
        $realPath = $firstLevel ? substr($this->requestURI, strlen($firstLevel) + 1) : $this->requestURI;

        $uriPaths = preg_split('/\//', $realPath, -1, PREG_SPLIT_NO_EMPTY) ?? [];
        $transformedURI = array_map(function ($value, $key) use ($uriPaths, $route) {
            return $uriPaths[$key] = isset($route['bindings'][$key]['token']) ? $uriPaths[$key] : null;
        }, $uriPaths, array_keys($uriPaths));

        return array_filter($transformedURI);
    }
    /**
     * @return Model[][]
     */
    private function fetchRouteModels($route): array
    {
        return array_map(function ($model) {
            if (!isset($model['predicted_model']) || !isset($model['val']))
                return;
            return $model['predicted_model']::find($model['val']);
        }, $route['bindings']);
    }

    private function hasBindings($route)
    {
        return isset($route['bindings']) && count($route['bindings']) > 0;
    }

    private function formatBindings(&$route, $prefixes)
    {
        $tokens = array_column($route['bindings'], 'token');
        $position = array_combine($tokens, array_keys($route['bindings']));
        $values = array_combine($tokens, $this->extractTokensFromRoute($route, $prefixes));
        foreach ($values as $key => $value) {
            $index = $position[$key];
            $route['bindings'][$index]['val'] = $value;
        }
        $route['bindings'] = array_combine($tokens, $route['bindings']);
    }



    private function transformGroupToRoutePrototype($route, $group)
    {
        // Check if the request URI has a prefix (e.g. /api/users)
        $firstLevel = isset($group);
        $realPath = $firstLevel ? substr($this->requestURI, strlen($firstLevel) + 1) : $this->requestURI;

        $uriPaths = preg_split('/\//', $realPath, -1, PREG_SPLIT_NO_EMPTY) ?? [];
        // dd($firstLevel);
        $transformedURI = array_combine($uriPaths, array_map(function ($key) use ($uriPaths, $route) {
            return isset($route['bindings'][$key]['token']) ? "{{$route['bindings'][$key]['token']}}" : $uriPaths[$key];
        }, array_keys($uriPaths)));

        return "/" . implode("/", $transformedURI);
    }
    function getFirstMatch(array $arr)
    {
        // Filter the array based on the condition that the key is equal to the value
        $filtered = array_filter($arr, function ($key, $value) {
            return $key === $value;
        }, ARRAY_FILTER_USE_BOTH);

        // Get the first element of the filtered array
        $ret = reset($filtered);
        return $ret ? $ret : null;
    }

    private function matchRoute($route)
    {

        $isGroup = isset($route["type"]) && $route["type"] === "group";

        $firstLevel = explode('/', $this->requestURI)[1];
        $routeObject = $route['routes'] ?? $route;
        $prefixes = array_column($routeObject, 'prefix');
        $uriPrototypes = array_combine(array_keys($routeObject), array_map([$this, 'transformGroupToRoutePrototype'], $routeObject, $prefixes));
        $requestURIPrototype = $this->getFirstMatch($uriPrototypes) ?? $this->requestURI;

        $matchedRoute = isset($route['routes'][$requestURIPrototype]) ?
            $route['routes'][$requestURIPrototype] : (isset($route[$requestURIPrototype])
                ? $route[$requestURIPrototype] :
                null);

        if ($this->hasBindings($matchedRoute)) {
            $this->formatBindings($matchedRoute, $prefixes);
        }

        if (
            $isGroup
            && isset($route['prefix']) && $firstLevel == $route['prefix']
            && isset($route['routes'][$requestURIPrototype])
            && $route['routes'][$requestURIPrototype]['httpMethod'] === $this->requestMethod
            || $isGroup
            && !isset($route['prefix'])
            && isset($route['routes'][$requestURIPrototype])
            && $route['routes'][$requestURIPrototype]['httpMethod'] === $this->requestMethod
        ) {
            return $matchedRoute;
        } elseif (
            isset($route[$requestURIPrototype])
            && $route[$requestURIPrototype]['httpMethod'] === $this->requestMethod
        ) {
            return $matchedRoute;
        }
    }

    function __construct()
    {
        $this->requestURI = parse_url($_SERVER["REQUEST_URI"])['path'];
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $this->request = new Request();
        $files = $this->loadRoutes();

        foreach ($files as $file) {
            foreach ($file as $route) {
                $res = call_user_func([$this, 'matchRoute'], $route);
                if ($res && $this->handleRoute($res, $route)) {
                    return;
                }
            }
        }

        $this->handleNotFound();
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
