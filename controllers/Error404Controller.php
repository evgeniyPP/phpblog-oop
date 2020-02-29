<?php

namespace controllers;

class Error404Controller extends BaseController
{
    public function index()
    {
        if (isset($_SESSION['error'])) {
            $error = htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
        } else {
            $error = null;
        }

        $this->title = 'Страница не найдена | Блог на PHP';
        $this->stylefile = '404';
        $this->content = $this->build(
            __DIR__ . '/../views/404.html.php',
            [
                'error' => $error,
            ]
        );
    }
}