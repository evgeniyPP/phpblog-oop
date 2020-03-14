<?php

namespace controllers;

use core\Exception\Error404Exception;
use core\Exception\ValidatorException;
use core\FormBuilder;
use core\User;
use forms\AddEditForm;

class PostController extends BaseController
{
    private $username;

    public function index()
    {
        $mPost = $this->container->execute('postModel');
        $is_auth = $this->checkAuth();
        $posts = $mPost->getAll();

        $this->title = 'Блог на PHP';
        $this->stylefile = 'index';
        $this->content = $this->build(
            __DIR__ . '/../views/index.html.php',
            [
                'posts' => $posts,
                'is_auth' => $is_auth,
                'username' => $this->username ?? null,
            ]
        );
    }

    public function single()
    {
        $id = $this->request->get('GET', 'id');
        $mPost = $this->container->execute('postModel');
        $is_auth = $this->checkAuth();
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

            $mPost = $this->container->execute('postModel');

            try {
                $id = $mPost->add(
                    [
                        'title' => $title,
                        'content' => $content,
                    ]
                );
                $this->redirect("post/$id");
            } catch (ValidatorException $e) {
                $errors = $e->getErrors();
            }
        }

        $form = new FormBuilder(
            new AddEditForm(
                ['title' => $title ?? '', 'content' => $content ?? ''],
                $errors ?? null
            )
        );

        $this->title = 'Добавить пост | Блог на PHP';
        $this->stylefile = 'add-edit';
        $this->content = $this->build(
            __DIR__ . '/../views/add.html.php',
            [
                'form' => $form,
            ]
        );
    }

    public function edit()
    {
        $id = $this->request->get('GET', 'id');
        $this->secureRoute("post/edit/$id");

        $mPost = $this->container->execute('postModel');

        if ($this->request->isGet()) {
            $post = $mPost->getById($id);
            if (!$post) {
                throw new Error404Exception();
            }
        } else {
            $title = $this->request->get('POST', 'title');
            $content = $this->request->get('POST', 'content');

            try {
                $mPost->editById(
                    [
                        'title' => $title,
                        'content' => $content,
                    ],
                    $id
                );
                $this->redirect("post/$id");
            } catch (ValidatorException $e) {
                $errors = $e->getErrors();
            }

        }

        $form = new FormBuilder(
            new AddEditForm(
                ['title' => $post['title'] ?? $title, 'content' => $post['content'] ?? $content],
                $errors ?? null
            )
        );

        $this->title = 'Редактировать пост | Блог на PHP';
        $this->stylefile = 'add-edit';
        $this->content = $this->build(
            __DIR__ . '/../views/edit.html.php',
            [
                'id' => $id,
                'form' => $form,
            ]
        );
    }

    private function checkAuth()
    {
        $mUser = $this->container->execute('userModel');
        $mSession = $this->container->execute('sessionModel');
        $user = new User($mUser, $mSession, $this->request);
        $auth = $user->checkAuth();
        $this->username = $auth['username'] ?? null;

        return $auth['isAuth'];
    }

    private function secureRoute(string $returnUrl, string $rerouteUrl = 'login')
    {
        if (!$this->checkAuth()) {
            $_SESSION['return_url'] = $returnUrl;
            $this->redirect($rerouteUrl);
        }
    }
}