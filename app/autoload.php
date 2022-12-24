<?php
namespace App;

define("GLOBALS_DIR", __DIR__ . '/vendor/globals/');
define("CONTROLLERS_DIR", __DIR__ . '/Controllers/');
define("APP_CONFIG", require_once(__DIR__ . '/config.php'));
class Autoloader {
    // Define a function that loads the global function implementation from a file
    static public function loadGlobals()
    {
        $files = array_diff(scandir(GLOBALS_DIR), ['.', '..']);
        array_walk($files, function ($file) {
            require_once GLOBALS_DIR . $file;
        });
    }
    
    static public function loadControllers()
    {
        $files = array_diff(scandir(CONTROLLERS_DIR), ['.', '..']);
        array_walk($files, function ($file) {
            require_once CONTROLLERS_DIR . $file;
        });
    }
    
}

Autoloader::loadGlobals();
Autoloader::loadControllers();