<?php

namespace controllers;

use core\DB;
use models\AuthModel;
use models\ErrorModel;
use models\PostModel;
use models\ValidateModel;

class PostController extends BaseController
{
    public function index()
    {
        $mPost = new PostModel(DB::getDBInstance());
        $is_auth = AuthModel::checkAuth();
        $log_btn = !$is_auth ? 'Войти' : 'Выйти';
        $posts = $mPost->getAll();

        $this->title = 'Блог на PHP';
        $this->stylefile = 'index';
        $this->content = $this->build(
            __DIR__ . '/../views/index.html.php',
            [
                'posts' => $posts,
                'is_auth' => $is_auth,
                'log_btn' => $log_btn,
            ]
        );
    }

    public function single()
    {
        $id = $this->request->get('GET', 'id');
        $mPost = new PostModel(DB::getDBInstance());
        $is_auth = AuthModel::checkAuth();
        $post = $mPost->getById($id);

        if (!$post) {
            ErrorModel::error404();
        }

        $this->title = $post['title'] . ' | Блог на PHP';
        $this->stylefile = 'post';
        $this->content = $this->build(
            __DIR__ . '/../views/post.html.php',
            [
                'is_auth' => $is_auth,
                'id' => $id,
                'post' => $post,
            ]
        );
    }

    public function add()
    {
        $this->secureRoute("post/add");

        if ($this->request->isGet()) {
            $title = '';
            $content = '';
            $error = '';
        } else {
            $title = htmlspecialchars(trim($this->request->get('POST', 'title')));
            $content = htmlspecialchars(trim($this->request->get('POST', 'content')));
            $error = ''; # TODO error handling

            ValidateModel::validatePost($title, $content);

            $mPost = new PostModel(DB::getDBInstance());
            $id = $mPost->add($title, $content);

            header('Location: ' . ROOT . "post/$id");
            exit();
        }

        $this->title = 'Добавить пост | Блог на PHP';
        $this->stylefile = 'add-edit';
        $this->content = $this->build(
            __DIR__ . '/../views/add.html.php',
            [
                'error' => $error,
                'title' => $title,
                'content' => $content,
            ]
        );
    }

    public function edit()
    {
        $id = $this->request->get('GET', 'id');
        $this->secureRoute("post/edit/$id");

        ValidateModel::validateId($id);
        $mPost = new PostModel(DB::getDBInstance());

        if ($this->request->isGet()) {
            $post = $mPost->getById($id);

            if (!$post) {
                ErrorModel::error404();
            }

            $error = '';
        } else {
            $title = htmlspecialchars(trim($this->request->get('POST', 'title')));
            $content = htmlspecialchars(trim($this->request->get('POST', 'content')));
            $error = ''; # TODO error handling

            ValidateModel::validatePost($title, $content);

            $mPost->edit($title, $content, $id);
            header('Location: ' . ROOT . "post/$id");
            exit();

        }

        $this->title = 'Редактировать пост | Блог на PHP';
        $this->stylefile = 'add-edit';
        $this->content = $this->build(
            __DIR__ . '/../views/edit.html.php',
            [
                'id' => $id,
                'error' => $error,
                'title' => $post['title'] ?? $title,
                'content' => $post['content'] ?? $content,
            ]
        );
    }
}
