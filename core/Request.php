<?php

namespace core;

class Request
{
    const METHOD_GET = 'GET';

    private $get;
    private $post;
    private $server;
    private $cookie;
    private $files;
    private $session;

    public function __construct($get, $post, $server, $cookie, $files, $session)
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->cookie = $cookie;
        $this->files = $files;
        $this->session = $session;
    }

    public function get(string $method, string $key = null)
    {
        $method = strtolower($method);
        if (!$key) {
            return $this->$method;
        }

        if (isset($this->$method[$key])) {
            return $this->$method[$key];
        }

        return false;
    }

    public function isGet()
    {
        return $this->server['REQUEST_METHOD'] === self::METHOD_GET;
    }

    public function isPost()
    {
        return !$this->isGet();
    }
}