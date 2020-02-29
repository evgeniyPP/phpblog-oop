<?php

namespace controllers;

use core\Request;
use models\AuthModel;

abstract class BaseController
{
    protected $request;
    protected $title;
    protected $stylefile;
    protected $content;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function build(string $template, array $props = [])
    {
        extract($props);
        ob_start();
        include $template;
        return ob_get_clean();
    }

    public function render()
    {
        echo $this->build(
            __DIR__ . '/../views/base.html.php',
            [
                'title' => $this->title,
                'stylefile' => $this->stylefile,
                'content' => $this->content,
            ]
        );
    }

    protected function secureRoute(string $returnUrl, string $rerouteUrl = 'login')
    {
        if (!AuthModel::checkAuth()) {
            $_SESSION['return_url'] = $returnUrl;
            $this->redirect($rerouteUrl);
        }
    }

    protected function redirect(string $uri = '')
    {
        header('Location: ' . ROOT . $uri);
        exit();
    }
}