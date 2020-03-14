<?php

use core\Exception\Error404Exception;
use core\Request;
use core\Router;

require_once __DIR__ . '/bootstrap.php';

$router = new Router($_SERVER["REQUEST_URI"]);
try {
    try {
        $controller = $router->getController(
            [
                'post' => 'Post',
                'login' => 'User',
                'logout' => 'User',
            ]
        );
        $action = $router->getAction();
        $id = $router->getId();

        if ($id) {
            $_GET['id'] = $id;
        }

        $request = new Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, $_SESSION);
        $controller = new $controller($container, $request);
        $controller->$action();
    } catch (Error404Exception $e) {
        header("HTTP/1.1 404 Not Found");
        $controller = sprintf('controllers\%sController', ERROR_404_CONTROLLER);
        $action = BASE_ACTION;
        $request = new Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, $_SESSION);

        $controller = new $controller($container, $request);
        $controller->$action();
    }
} catch (\Exception $e) {
    $controller = new $controller($container, $request);
    $controller->errorHandler($e->getMessage(), $e->getTraceAsString());
}
$controller->render();