<?php

namespace App\Models;

use ErrorException;
use mysqli;

class Model
{
    private $table;
    private mysqli $pdo;
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;

    private function connect()
    {
    }

    function __construct()
    {
        $config = APP_CONFIG;
        $this->host = $config['database']['host'];
        $this->username = $config['database']['username'];
        $this->password = $config['database']['password'];
        $this->database = $config['database']['database'];
        $this->port = $config['database']['port'];
        $this->table = camelToSnake((new \ReflectionClass($this))->getShortName());
        $this->pdo = mysqli_connect($this->host, $this->username, $this->password, $this->database, $this->port);
    }

    public static function Where()
    {
        $args = func_get_args();
        $numArgs = func_num_args();
        $model = new (__CLASS__);

        switch ($numArgs) {
            case 2: {
                    $column = $args[0];
                    $eq = $args[1];
                    break;
                }
            case 3: {
                    $column = $args[0];
                    $operator = $args[1];
                    $eq = $args[2];
                    break;
                }
            default: {
                    return throw new ErrorException("Number of arguements in model must be between 2 and 3");
                }
        }
    }
}
