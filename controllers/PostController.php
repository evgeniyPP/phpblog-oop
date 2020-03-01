<?php

namespace controllers;

use core\DB;
use core\DBDriver;
use core\Exception\Error404Exception;
use core\Exception\ValidatedDataException;
use core\Validator;
use models\AuthModel;
use models\PostModel;

class PostController extends BaseController
{
    public function index()
    {
        $mPost = new PostModel(new DBDriver(DB::getDBInstance()), new Validator());
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
        $mPost = new PostModel(new DBDriver(DB::getDBInstance()), new Validator());
        $is_auth = AuthModel::checkAuth();
        $post = $mPost->getById($id);

        if (!$post) {
            throw new Error404Exception();
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
            $title = $this->request->get('POST', 'title');
            $content = $this->request->get('POST', 'content');

            $mPost = new PostModel(new DBDriver(DB::getDBInstance()), new Validator());

            try {
                $id = $mPost->add(
                    [
                        'title' => $title,
                        'content' => $content,
                    ]
                );
                $this->redirect("post/$id");
            } catch (ValidatedDataException $e) {
                $errors = $e->getErrors();
                $title_errors = $errors['title'] ?? null;
                $content_errors = $errors['content'] ?? null;
            }
        }

        $this->title = 'Добавить пост | Блог на PHP';
        $this->stylefile = 'add-edit';
        $this->content = $this->build(
            __DIR__ . '/../views/add.html.php',
            [
                'is_error' => boolval($errors) ?? false,
                'title_errors' => $title_errors ?? null,
                'content_errors' => $content_errors ?? null,
                'title' => $title ?? '',
                'content' => $content ?? '',
            ]
        );
    }

    public function edit()
    {
        $id = $this->request->get('GET', 'id');
        $this->secureRoute("post/edit/$id");

        $mPost = new PostModel(new DBDriver(DB::getDBInstance()), new Validator());

        if ($this->request->isGet()) {
            $post = $mPost->getById($id);
            if (!$post) {
                throw new Error404Exception();
            }
        } else {
            $title = $this->request->get('POST', 'title');
            $content = $this->request->get('POST', 'content');

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