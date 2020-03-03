<?php

namespace controllers;

use core\Exception\Error404Exception;
use core\Request;

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

    public function __call($method, $args)
    {
        throw new Error404Exception();
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

    protected function redirect(string $uri = '')
    {
        header('Location: ' . ROOT . $uri);
        exit();
    }

    public function errorHandler($message, $trace)
    {
        if (DEV_MODE === true) {
            $trace = explode("#", $trace);
            $trace_new = [];
            foreach ($trace as $line) {
                $line = str_replace(INDEX_DIR . '\\', '', $line);
                $trace_new[] = $line;
            }
            $trace = implode("<br>", $trace_new);
            $this->content = "<br><br><b style=\"font-size: 20px;\">$message</b><br>$trace";
        } else {
            $this->content = "<br><br><b style=\"font-size: 20px;\">$message</b>";
        }
    }
}