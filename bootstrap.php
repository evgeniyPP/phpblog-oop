<?php

use core\DI\Box\DBDriverBox;
use core\DI\Box\FormBuilderBox;
use core\DI\Box\ModelBox;
use core\DI\Box\UserBox;
use core\DI\Container;

function __autoload($classname)
{
    include_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
}

const ROOT = '/';
const DEV_MODE = true;

const INDEX_DIR = __DIR__;
const BASE_ACTION = 'index';
const ERROR_404_CONTROLLER = 'Error404';
const METHOD_GET = 'get';
const METHOD_POST = 'post';

session_start();

$container = new Container();

$container->register(new DBDriverBox());
$container->register(new ModelBox());
$container->register(new UserBox());
$container->register(new FormBuilderBox());