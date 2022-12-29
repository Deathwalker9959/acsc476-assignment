<?php

namespace App\Router;

class Request
{
    /**
     * The HTTP method of the request (e.g. GET, POST, PUT, DELETE).
     *
     * @var string
     */
    public $method;

    /**
     * The URI of the request.
     *
     * @var string
     */
    public $uri;

    /**
     * An array of request headers.
     *
     * @var array
     */
    public $headers;

    /**
     * The request body.
     *
     * @var string
     */
    public $body;

    /**
     * An array of query string parameters.
     *
     * @var array
     */
    public $query;

    /**
     * An array of POST parameters.
     *
     * @var array
     */
    public $post;

    /**
     * An array of uploaded files.
     *
     * @var array
     */
    public $files;

    /**
     * Construct a new Request object by parsing the current PHP request.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->headers = getallheaders();
        $this->body = file_get_contents('php://input');
        $this->query = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
    }
}
