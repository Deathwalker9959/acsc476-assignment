<?php

namespace App;

class Request
{
    protected $userAgent;
    protected $contentType;
    protected $body;
    protected $params = [];

    function __construct($params = [], $body = null)
    {
        $this->params = $params;
        $this->body = $body;
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->contentType = $_SERVER['HTTP_CONTENT_TYPE'];
    }
}