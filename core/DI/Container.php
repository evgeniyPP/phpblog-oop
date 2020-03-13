<?php

namespace core\DI;

class Container
{
    private $container = [];

    public function set($name, \Closure $callback)
    {
        $this->container[$name] = $callback;
    }

    public function register(IRegisterBox $box)
    {
        $box->register($this);
    }

    public function execute(string $name, ...$params)
    {
        if (!$this->container[$name]) {
            throw new \Exception('No dependency injection with this name was found');
        }

        return call_user_func_array($this->container[$name], $params);
    }
}