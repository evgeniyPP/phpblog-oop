<header class="header">
    <h1 class="header__title">Добавить пост</h1>
    <div class="header__btns">
        <a href="<?=ROOT?>">Вернуться на главную</a>
    </div>
</header>
<?if ($is_error): ?>
<form method="post">
    <label for="title">Название поста:</label>
    <?foreach ($title_errors as $title_error): ?>
    <p class="error"><?=$title_error?></p>
    <?endforeach;?>
    <input class="post__title" type="text" name="title" value="<?=$title?>">
    <label for="content">Текст поста:</label>
    <?foreach ($content_errors as $content_error): ?>
    <p class="error"><?=$content_error?></p>
    <?endforeach;?>
    <textarea class="post__content" name="content"><?=$content?></textarea>
    <button type="submit">Добавить</button>
</form>
<?else: ?>
<form method="post">
    <label for="title">Название поста:</label>
    <input class="post__title" type="text" name="title" value="<?=$title?>">
    <label for="content">Текст поста:</label>
    <textarea class="post__content" name="content"><?=$content?></textarea>
    <button type="submit">Добавить</button>
</form>
<?endif;?>