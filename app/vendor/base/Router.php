<?php

namespace App;

use App\Router\Request;
use App\Router\Response;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class Router
{

    /**
     * The request URI
     *
     * @var string
     */
    private $requestURI;

    /**
     * The request method
     *
     * @var string
     */
    private $requestMethod;

    /**
     * The request object
     *
     * @var Request
     */
    private $request;

    private $beforeHooks = [];

    private $afterHooks = [];

    /**
     * Loads and requires all the route files in the ROUTES_DIR directory
     *
     * @return array An array of route arrays
     */
    private function loadRoutes()
    {
        $files = array_diff(scandir(ROUTES_DIR), ['.', '..']);
        return array_map(function ($file) {
            $res = require_once ROUTES_DIR . $file;
            return $res;
        }, array_values($files));
    }

    /**
     * Gets the parameter names, classes and types of a controller method
     *
     * @param array $route The route array
     * @return array An array of parameter arrays
     */
    private function getControllerModels($route)
    {
        $refMethod = new ReflectionMethod($route["controllerClass"], $route["method"]);
        $params = $refMethod->getParameters();
        $calleParamClasses = array_map(function (ReflectionParameter $param) {
            return [
                "name" => $param?->getName(),
                "class" => $param?->getDeclaringFunction(),
                "type" => $param?->getType()?->getName(),
            ];
        }, $params);

        return $calleParamClasses;
    }

    /**
     * Converts a class name to its corresponding model name
     *
     * @param string $className The class name
     * @return string The model name
     */
    private function classToPrototype($className)
    {
        preg_match('/([^\\\]+)$/', $className, $matches);
        return lcfirst($matches[1]);
    }

    /**
     * Maps the route models to the controller method parameters
     *
     * @param array $controllerModels An array of parameter arrays for the controller method
     * @param array $routeModels An array of route models
     * @return array An array of the mapped parameters
     */
    private function mapParameters(array $controllerModels, array $routeModels): array
    {
        return array_values(array_filter(array_map(function (array $model) use ($routeModels) {
            if ($model['type'] == Request::class) {
                return RequestSingleton::getInstance()->getRequest();
            }
            // Get the last part of the class name
            $modelType = $this->classToPrototype($model['type']);
            // If the model exists in the route models, return it
            return $routeModels[$modelType] ?? null;
        }, $controllerModels)));
    }

    /**
     * Handles the middleware for the route
     *
     * @param array|null $controllerMiddleware An array of middleware for the controller method
     * @param array $group The route array
     * @return Response|void A response object if a middleware returns one, void otherwise
     */
    private function handleMiddleware($controllerMiddleware, $group)
    {
        // Check if the route has any middleware defined
        if (isset($group['middleware'])) {
            // Loop through the middleware for the route
            foreach ($group['middleware'] as $middleware) {
                // Call the middleware class
                $qualifiedClass = "App\\Middleware\\" . ucFirst($middleware);
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

        if (isset($controllerMiddleware)) {

            // Loop through the middleware for the route
            foreach ($controllerMiddleware as $middleware) {
                // Call the middleware class
                $qualifiedClass = "App\\Middleware\\" . ucFirst($middleware);
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
    }
    /**
     * Gets the middleware for a controller class
     *
     * @param array $route The route array
     * @return array An array of middleware
     */
    private function getControllerMiddleware($route)
    {
        $refClass = new ReflectionClass($route['controllerClass']);
        $refMiddlewares = $refClass->getStaticPropertyValue("middleware");

        return $refMiddlewares;
    }
    /**
     * Handles a route by calling its controller method and applying its middleware
     *
     * @param array $route The route array
     * @param array $group The group array
     * @return Response|void A response object if a middleware returns one, void otherwise
     */
    private function handleRoute($route, $group)
    {
        $controllerModels = $this->getControllerModels($route);
        $routeModels = $this->fetchRouteModels($route);
        $controllerMiddleware = $this->getControllerMiddleware($route);

        $middlewareRet = $this->handleMiddleware($controllerMiddleware, $group);
        if ($middlewareRet) {
            return $middlewareRet;
        }
        // If no middleware returned false, call the route's controller method

        ob_start();
        $resp = call_user_func_array([$route['controllerClass'], $route["method"]], $this->mapParameters($controllerModels, $routeModels));
        if (gettype($resp) == 'object' && $resp::class == Response::class) {
            return $resp->send();
        }

        $output = ob_get_clean();

        return response()->body($output)->send();
    }

    /**
     * Handles a 404 error by returning a response with a 404 status code and a view file
     *
     * @return Response A response object
     */
    private function handleNotFound()
    {
        return response()->status(404)->view("error.404")->send();
    }

    /**
     * Extracts the tokens from a route URI
     *
     * @param array $route The route array
     * @param array $prefixes An array of prefixes
     * @return array An array of extracted tokens
     */
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
     * Fetches the models for a route
     *
     * @param array $route The route array
     * @return array An array of models
     */
    private function fetchRouteModels($route): array
    {
        return array_map(function ($model) {
            if (!isset($model['predicted_model']) || !isset($model['val']))
                return;
            return $model['predicted_model']::find($model['val']);
        }, $route['bindings']);
    }

    /**
     * Checks if a route has bindings
     *
     * @param array $route The route array
     * @return bool True if the route has bindings, false otherwise
     */
    private function hasBindings($route)
    {
        return isset($route['bindings']) && count($route['bindings']) > 0;
    }

    /**
     * Formats the bindings for a route
     *
     * @param array $route The route array
     * @param array $prefixes An array of prefixes
     * @return void
     */
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


    /**
     * Transforms a group route into a route prototype by replacing dynamic segments with tokens.
     *
     * @param array $route The route data.
     * @param string $group The group prefix.
     * @return string The transformed route prototype.
     */
    private function transformGroupToRoutePrototype($route, $group)
    {
        // Check if the request URI has a prefix (e.g. /api/users)
        $firstLevel = isset($group);
        $realPath = $firstLevel ? substr($this->requestURI, strlen($firstLevel)) : $this->requestURI;

        $uriPaths = preg_split('/\//', $realPath, -1, PREG_SPLIT_NO_EMPTY) ?? [];
        $transformedURI = array_combine($uriPaths, array_map(function ($key) use ($uriPaths, $route) {
            return isset($route['bindings'][$key]['token']) ? "{{$route['bindings'][$key]['token']}}" : $uriPaths[$key];
        }, array_keys($uriPaths)));

        return "/" . implode("/", $transformedURI);
    }

    private function transformRouteKeys($key, $prefix)
    {
        return "/{$prefix}{$key}";
    }

    /**
     * Returns the first element in an array where the key is equal to the value.
     *
     * @param array $arr The array to search.
     * @return mixed|null The first matching element, or null if not found.
     */
    private function getFirstMatch(array $arr, string $prefix = null)
    {
        // Filter the array based on the condition that the key is equal to the value
        $filtered = array_filter($arr, function ($key, $value) use ($prefix) {
            return $prefix ? "/{$prefix}{$key}" : $key === $value;
        }, ARRAY_FILTER_USE_BOTH);

        // Get the first element of the filtered array
        $ret = $prefix ? substr(reset($filtered), strlen("/{$prefix}")) : reset($filtered);
        return $ret ? $ret : null;
    }

    /**
     * Matches the current request to a route.
     *
     * @param array $route The route data.
     * @return mixed The matching route data, or null if no match is found.
     */
    private function matchRoute($route)
    {

        $isGroup = isset($route["type"]) && $route["type"] === "group";

        $firstLevel = explode('/', $this->requestURI)[1];
        $routeObject = $route['routes'] ?? $route;
        $prefixes = array_fill(0, count($routeObject), $route['prefix']);
        // $routeKeys = !isset($route['prefix']) ? array_keys($routeObject) : array_map([$this,'transformRouteKeys'],array_keys($routeObject),$prefixes);
        $uriPrototypes = array_combine(array_keys($routeObject), array_map([$this, 'transformGroupToRoutePrototype'], $routeObject, $prefixes));
        $requestURIPrototype = $this->getFirstMatch($uriPrototypes, $route['prefix']) ?? $this->requestURI;

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

    /**
     * Hook function that stores the current URL in the session before every request.
     *
     * @param array $route The route data.
     * @param array $group The group data.
     * @return bool Whether to continue processing the request.
     */
    private function setPrevUrl($route, $group)
    {
        // Store the current URL in the session
        $_SESSION['prev_url'] = $_SERVER['REQUEST_URI'];

        // Continue processing the request
        return true;
    }

    /**
     * Adds a hook function to the router.
     *
     * @param string $hookType The type of hook to add. Can be "before" or "after".
     * @param callable $hookFunction The hook function to add.
     * @param array $hookParams Optional parameters to pass to the hook function.
     */
    public function addHook($hookType, $hookFunction, $hookParams = [])
    {
        // Check that the hook type is valid
        if (!in_array($hookType, ['before', 'after'])) {
            throw new Exception('Invalid hook type: ' . $hookType);
        }

        // Check that the hook function is callable
        if (!is_callable($hookFunction)) {
            throw new Exception('Invalid hook function: ' . var_export($hookFunction, true));
        }

        // Add the hook to the appropriate array
        $this->{$hookType . 'Hooks'}[] = [
            'function' => $hookFunction,
            'params' => $hookParams,
        ];
    }

    /**
     * Runs all of the hooks of the specified type.
     *
     * @param string $hookType The type of hooks to run. Can be "before" or "after".
     * @param array $hookParams Optional parameters to pass to the hook functions.
     * @return bool Whether to continue processing the request.
     */
    private function runHooks($hookType, $hookParams = [])
    {
        // Check that the hook type is valid
        if (!in_array($hookType, ['before', 'after'])) {
            throw new Exception('Invalid hook type: ' . $hookType);
        }

        // Run all of the hooks
        foreach ($this->{$hookType . 'Hooks'} as $hook) {
            $result = call_user_func_array($hook['function'], array_merge($hook['params'], $hookParams));
            if ($hookType === 'before' && $result === false) {
                // Stop processing the request if a "before" hook returns false
                return false;
            }
        }

        // Continue processing the request
        return true;
    }



    /**
     * Constructor for the Router class.
     *
     * Loads the routes, matches the current request to a route, and handles the route if a match is found.
     * If no match is found, the "not found" route is handled.
     */
    function __construct()
    {
        $this->requestURI = parse_url($_SERVER["REQUEST_URI"])['path'];
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $this->request = RequestSingleton::getInstance()->getRequest();
        $files = $this->loadRoutes();

        if (!$this->runHooks('before')) {
            return;
        }


        foreach ($files as $file) {
            foreach ($file as $route) {
                $matchedRoute = call_user_func([$this, 'matchRoute'], $route);
                if ($matchedRoute && $this->handleRoute($matchedRoute, $route)) {
                    if (!$this->runHooks('after', [$matchedRoute, $route])) {
                        return;
                    }

                    return;
                }
            }
        }

        $this->handleNotFound();

        return;
    }
}