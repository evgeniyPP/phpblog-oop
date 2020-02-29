<?php

use controllers\PostController;
use core\DB;
use models\PostModel;
use models\UserModel;

function __autoload($classname)
{
    include_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
}

define('ROOT', '/blog-oop/');
session_start();

$uri = $_SERVER["REQUEST_URI"];
$uriParts = explode("/", $uri);
unset($uriParts[0]);
unset($uriParts[1]);
$uriParts = array_values($uriParts);

// Controller
$controller = isset($uriParts[0]) && $uriParts[0] !== '' ? $uriParts[0] : 'post';

switch ($controller) {
    case 'post':
        $controller = "Post";
        break;
    case 'user':
        $controller = "User";
        break;
    case 'login':
        $controller = "Login";
        break;
    case '404':
        $controller = "Error404";
        break;
    default:
        header('Location: ' . ROOT . "404");
        break;
}

// Action
if (isset($uriParts[1]) && $uriParts[1] !== '') {
    $action = is_numeric($uriParts[1]) ? 'single' : $uriParts[1];
} else {
    $action = 'index';
}

// Id
if (isset($uriParts[1]) && is_numeric($uriParts[1])) {
    $id = $uriParts[1];
} else {
    $id = isset($uriParts[2]) && is_numeric($uriParts[2]) ? $uriParts[2] : null;
}

$controller = sprintf('controllers\%sController', $controller);
$controller = new $controller();

if (!method_exists($controller, $action)) {
    header('Location: ' . ROOT . "404");
}

$controller->$action($id);
$controller->render();