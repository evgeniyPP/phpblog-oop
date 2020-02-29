<?php

namespace controllers;

use core\DB;
use models\AuthModel;
use models\PostModel;
use models\ValidateModel;

class PostController extends BaseController
{
    public function index()
    {
        $mPost = new PostModel(DB::connect());
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

    public function single(int $id)
    {
        $mPost = new PostModel(DB::connect());
        $is_auth = AuthModel::checkAuth();
        $post = $mPost->getById($id);

        if (!$post) {
            header('Location: ' . ROOT . "404");
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
        if (!AuthModel::checkAuth()) {
            $_SESSION['return_url'] = "post/add";
            header('Location: ' . ROOT . "login");
            exit();
        }

        if (!count($_POST) > 0) { // GET
            $title = '';
            $content = '';
            $error = '';
        } else { // POST
            $title = htmlspecialchars(trim($_POST['title']));
            $content = htmlspecialchars(trim($_POST['content']));
            $error = ''; # TODO error handling

            ValidateModel::validatePost($title, $content);

            $mPost = new PostModel(DB::connect());
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

    public function edit($id)
    {
        if (!AuthModel::checkAuth()) {
            $_SESSION['return_url'] = "post/edit/$id";
            header('Location: ' . ROOT . "login");
            exit();
        }

        ValidateModel::validateId($id);
        $mPost = new PostModel(DB::connect());

        if (!count($_POST) > 0) { // GET
            $post = $mPost->getById($id);

            if (!$post) {
                header('Location: ' . ROOT . "404");
            }

            $error = '';
        } else { // POST
            $title = htmlspecialchars(trim($_POST['title']));
            $content = htmlspecialchars(trim($_POST['content']));
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