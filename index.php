<?php

use models\ErrorModel;
use models\RouterModel;

function __autoload($classname)
{
    include_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
}

const ROOT = '/blog-oop/';
session_start();

$mRouter = new RouterModel($_SERVER["REQUEST_URI"]);
$controller = $mRouter->getController(
    [
        'post' => 'Post',
        'user' => 'User',
        'login' => 'Login',
    ]
);
$action = $mRouter->getAction();
$id = $mRouter->getId();

if ($id) {
    $_GET['id'] = $id;
}

$request = new core\Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, $_SESSION);

$controller = new $controller($request);

if (!method_exists($controller, $action)) {
    ErrorModel::error404();
}

$controller->$action();
$controller->render();