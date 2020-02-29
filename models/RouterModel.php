<?php

namespace models;

class RouterModel
{
    const ERROR_404 = '404';
    const ERROR_404_CONTROLLER = 'Error404';
    const BASE_ACTION = 'index';
    const SINGLE_ACTION = 'single';
    private $uriParts;

    public function __construct(string $uri)
    {
        $uriParts = explode("/", $uri);
        unset($uriParts[0]);
        if ($this->checkRoot($uriParts[1])) {
            unset($uriParts[1]);
        }
        $uriParts = array_values($uriParts);
        $this->uriParts = $uriParts;
    }

    public function getController(array $controllers, string $defValue = 'post')
    {
        $uriPart = isset($this->uriParts[0]) && $this->uriParts[0] !== '' ? $this->uriParts[0] : $defValue;
        if ($controllers[$uriPart]) {
            $controller = $controllers[$uriPart];
        } elseif ($uriPart === self::ERROR_404) {
            header("HTTP/1.1 404 Not Found");
            $controller = self::ERROR_404_CONTROLLER;
        } else {
            header('Location: ' . ROOT . "404");
            exit();
        }
        $controller = sprintf('controllers\%sController', $controller);
        return $controller;
    }

    public function getAction()
    {
        if (isset($this->uriParts[1]) && $this->uriParts[1] !== '') {
            $action = is_numeric($this->uriParts[1]) ? self::SINGLE_ACTION : $this->uriParts[1];
        } else {
            $action = self::BASE_ACTION;
        }
        return $action;
    }

    public function getId()
    {
        if (isset($this->uriParts[1]) && is_numeric($this->uriParts[1])) {
            $id = $this->uriParts[1];
        } else {
            $id = isset($this->uriParts[2]) && is_numeric($this->uriParts[2]) ? $this->uriParts[2] : null;
        }
        return $id;
    }

    // checks if the site is on a subdomain
    private function checkRoot($value)
    {
        $root_undashed = explode("/", ROOT);
        $root_undashed = array_filter($root_undashed, function ($item) {
            return $item !== '';
        });
        $root_undashed = array_values($root_undashed);
        $root_undashed = $root_undashed[0];

        return $value === $root_undashed;
    }
}