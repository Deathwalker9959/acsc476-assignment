<?php
namespace App\Router;

class Route
{

    // private $routeList = [];

    private static function parseController(string $httpMethod, string $location, string $function) {
        $path = explode("@", $function);
        $class = $path[0];
        $method = $path[1];

        $controllerClass = "App\\Controllers\\{$class}";

        return [ 
            "{$location}" => [
                "httpMethod" => strtoupper($httpMethod),
                "controllerClass" => $controllerClass,
                "method" => $method,
            ],
        ];
    }

    public static function Get(string $location, string $function)
    {
        return  Route::parseController(__FUNCTION__, $location, $function);
    }
    public static function Post(string $location, string $function)
    {
        return Route::parseController(__FUNCTION__, $location, $function);
    }
    public static function Put(string $location, string $function)
    {
        return Route::parseController(__FUNCTION__, $location, $function);
    }
    public static function Patch(string $location, string $function)
    {
        return Route::parseController(__FUNCTION__, $location, $function);
    }
    public static function Delete(string $location, string $function)
    {
        return Route::parseController(__FUNCTION__, $location, $function);
    }
    
    public static function Group($options, $routes)
    {
        $prefix = $options['prefix'] ?? null;
        $middleware = $options['middleware'] ?? null;

        $flattenedRoutes = call_user_func_array('array_merge', $routes);

        return [
            "type" => "group",
            "prefix" => $prefix,
            "middleware" => $middleware,
            "routes" => $flattenedRoutes
        ];
    }

    protected function route($location, $httpMethod) {
        if (isset($routeList[$location.$httpMethod])) {
            $route = $routeList[$location.$httpMethod];
            $controllerClass = $route['controllerClass'];
            $method = $route['method'];
            
            $controller = new $controllerClass;
            $controller->$method($_REQUEST);
        }
    }
}
