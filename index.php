<?php

use core\Exception\Error404Exception;
use models\RouterModel;

function __autoload($classname)
{
    include_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
}

const ROOT = '/blog-oop/';
const DEV_MODE = true;
const INDEX_DIR = __DIR__;
const BASE_ACTION = 'index';
const ERROR_404_CONTROLLER = 'Error404';
session_start();

$mRouter = new RouterModel($_SERVER["REQUEST_URI"]);
try {
    try {
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
        $controller->$action();
    } catch (Error404Exception $e) {
        header("HTTP/1.1 404 Not Found");
        $controller = sprintf('controllers\%sController', ERROR_404_CONTROLLER);
        $action = BASE_ACTION;
        $controller = new $controller($request);
        $controller->$action();
    }
} catch (\Exception $e) {
    $controller = new $controller($request);
    $controller->errorHandler($e->getMessage(), $e->getTraceAsString());
}
$controller->render();