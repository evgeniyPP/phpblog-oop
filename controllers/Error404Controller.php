<?php

namespace controllers;

class Error404Controller extends BaseController
{
    public function index()
    {
        if ($this->request->get('SESSION', 'error')) {
            $error = htmlspecialchars($this->request->get('SESSION', 'error'));
            $this->request->remove('SESSION', 'error');
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