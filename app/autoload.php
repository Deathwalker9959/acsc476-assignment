<?php


namespace App;

use App\Database\QueryBuilder;
use PDO;
use PDOException;

define("APP_DIR", __DIR__);
define("GLOBALS_DIR", __DIR__ . '/vendor/globals/');
define("BASE_DIR", __DIR__ . '/vendor/base/');
define("ROUTES_DIR", __DIR__ . '/Routes/');
define("MODELS_DIR", __DIR__ . '/Models/');
define("MIDDLEWARE_DIR", __DIR__ . '/Middleware/');
define("CONTROLLERS_DIR", __DIR__ . '/Controllers/');
define("VIEWS_DIR", __DIR__ . '/Views/');
define("ASSETS_DIR", __DIR__ . '/public/assets/');
define("APP_CONFIG", require_once(__DIR__ . '/config.php'));
class Autoloader
{

    static public $db;
    static public $queryBuilder;

    /**
     * Loads the global function implementation from the `vendor/globals` directory.
     *
     * @return void
     */
    static public function loadGlobals()
    {
        $files = array_diff(scandir(GLOBALS_DIR), ['.', '..']);
        array_walk($files, function ($file) {
            require_once GLOBALS_DIR . $file;
        });
    }

    /**
     * Loads the base classes from the `vendor/base` directory.
     *
     * @return void
     */
    static public function loadBase()
    {
        $files = array_diff(scandir(BASE_DIR), ['.', '..']);
        array_walk($files, function ($file) {
            require_once BASE_DIR . $file;
        });
    }

    /**
     * Loads the model classes from the `Models` directory.
     *
     * @return void
     */
    static public function loadModels()
    {
        $files = array_diff(scandir(MODELS_DIR), ['.', '..']);
        array_walk($files, function ($file) {
            require_once MODELS_DIR . $file;
        });
    }

    static public function loadMiddleware()
    {
        static::loadMiddlewareRecursive(MIDDLEWARE_DIR);
    }

    /**
     * Loads middleware classes recursively from the given directory.
     *
     * @param string $dir The directory to search for middleware classes.
     *
     * @return void
     */
    static private function loadMiddlewareRecursive(string $dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                static::loadMiddlewareRecursive($path);
            } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                require_once $path;
            }
        }
    }

    /**
     * Loads the controller classes from the `Controllers` directory.
     *
     * @return void
     */
    static public function loadControllers()
    {
        $files = array_diff(scandir(CONTROLLERS_DIR), ['.', '..']);
        array_walk($files, function ($file) {
            require_once CONTROLLERS_DIR . $file;
        });
    }


    /**
     * Initializes the `static::db` property of each model class to a PDO connection.
     *
     * @return void
     */
    static public function initializeModels()
    {
        $dbConfig = APP_CONFIG['db'];
        $driver = $dbConfig['driver'];
        $host = $dbConfig['host'];
        $port = $dbConfig['port'];
        $database = $dbConfig['database'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];
        $charset = $dbConfig['charset'];

        try {
            // Create a new PDO connection for the current model
            $pdo = new PDO("{$driver}:host={$host};port={$port};dbname={$database};charset={$charset}", $username, $password);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $queryBuilder = new QueryBuilder($pdo);

            // Set the `static::db` property of the current model to the PDO connection
            static::$db = $pdo;
            static::$queryBuilder = $queryBuilder;
        } catch (PDOException $e) {
            dd("Connection failed: " . $e->getMessage());
        }

        $modelClasses = array_filter(get_declared_classes(), function ($class) {
            return strpos($class, 'App\\Models\\') === 0;
        });

        array_map(function ($class) {
            $class::$db = static::$db;
            $class::$queryBuilder = static::$queryBuilder;
        }, $modelClasses);
    }
}

Autoloader::loadGlobals();
Autoloader::loadBase();
Autoloader::loadModels();
Autoloader::loadMiddleware();
Autoloader::initializeModels();
Autoloader::loadControllers();
