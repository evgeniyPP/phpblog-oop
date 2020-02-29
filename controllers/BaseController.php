<?php

namespace controllers;

abstract class BaseController
{
    protected $title;
    protected $stylefile;
    protected $content;

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
}