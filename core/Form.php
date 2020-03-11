<?php

namespace core;

abstract class Form
{
    protected $formName;
    protected $action;
    protected $method;
    protected $fields;

    public function getName()
    {
        return $this->formName;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getFields()
    {
        return new \ArrayIterator($this->fields);
    }
}