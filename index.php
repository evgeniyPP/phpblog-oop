<?php

use core\DI\Container;
use core\DI\PostModelBox;
use core\DI\SessionModelBox;
use core\DI\UserBox;
use core\DI\UserModelBox;
use core\Exception\Error404Exception;
use models\RouterModel;

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

$mRouter = new RouterModel($_SERVER["REQUEST_URI"]);
try {
    try {
        $container = new Container();
        $container->register(new PostModelBox($container));
        $container->register(new UserModelBox($container));
        $container->register(new SessionModelBox($container));

        $controller = $mRouter->getController(
            [
                'post' => 'Post',
                'login' => 'User',
                'logout' => 'User',
            ]
        );
        $action = $mRouter->getAction();
        $id = $mRouter->getId();

        if ($id) {
            $_GET['id'] = $id;
        }

        $request = new core\Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, $_SESSION);
        $controller = new $controller($container, $request);
        $controller->$action();
    } catch (Error404Exception $e) {
        header("HTTP/1.1 404 Not Found");
        $controller = sprintf('controllers\%sController', ERROR_404_CONTROLLER);
        $action = BASE_ACTION;
        $request = new core\Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, $_SESSION);

        $controller = new $controller($container, $request);
        $controller->$action();
    }
} catch (\Exception $e) {
    $controller = new $controller($container, $request);
    $controller->errorHandler($e->getMessage(), $e->getTraceAsString());
}
$controller->render();