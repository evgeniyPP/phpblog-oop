<?php

namespace controllers;

use core\DB;
use core\DBDriver;
use models\AuthModel;
use models\ErrorModel;
use models\PostModel;
use models\ValidateModel;

class PostController extends BaseController
{
    public function index()
    {
        $mPost = new PostModel(new DBDriver(DB::getDBInstance()));
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
        $mPost = new PostModel(new DBDriver(DB::getDBInstance()));
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

        if ($this->request->isPost()) {
            $title = htmlspecialchars(trim($this->request->get('POST', 'title')));
            $content = htmlspecialchars(trim($this->request->get('POST', 'content')));

            ValidateModel::validatePost($title, $content);

            $mPost = new PostModel(new DBDriver(DB::getDBInstance()));
            $id = $mPost->add(
                [
                    'title' => $title,
                    'content' => $content,
                ]
            );

            $this->redirect("post/$id");
        }

        $this->title = 'Добавить пост | Блог на PHP';
        $this->stylefile = 'add-edit';
        $this->content = $this->build(
            __DIR__ . '/../views/add.html.php',
            [
                'error' => $error ?? null,
                'title' => $title ?? '',
                'content' => $content ?? '',
            ]
        );
    }

    public function edit()
    {
        $id = $this->request->get('GET', 'id');
        $this->secureRoute("post/edit/$id");

        ValidateModel::validateId($id);
        $mPost = new PostModel(new DBDriver(DB::getDBInstance()));

        if ($this->request->isGet()) {
            $post = $mPost->getById($id);
            if (!$post) {
                ErrorModel::error404();
            }
        } else {
            $title = htmlspecialchars(trim($this->request->get('POST', 'title')));
            $content = htmlspecialchars(trim($this->request->get('POST', 'content')));

            ValidateModel::validatePost($title, $content);

            $mPost->editById(
                [
                    'title' => $title,
                    'content' => $content,
                ],
                $id
            );
            $this->redirect("post/$id");
        }

        $this->title = 'Редактировать пост | Блог на PHP';
        $this->stylefile = 'add-edit';
        $this->content = $this->build(
            __DIR__ . '/../views/edit.html.php',
            [
                'id' => $id,
                'error' => $error ?? '',
                'title' => $post['title'] ?? $title,
                'content' => $post['content'] ?? $content,
            ]
        );
    }
}