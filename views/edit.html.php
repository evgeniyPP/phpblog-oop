<header class="header">
    <h1 class="header__title">Редактировать пост</h1>
    <div class="header__btns">
        <a href="<?=ROOT?>post/<?=$id?>">Вернуться к посту</a>
    </div>
</header>
<form method="post">
    <label for="title">Название поста:</label>
    <?if ($is_error): ?>
    <?foreach ($title_errors as $title_error): ?>
    <p class="error"><?=$title_error?></p>
    <?endforeach;?>
    <input class="post__title" type="text" name="title" value="<?=$title?>">
    <label for="content">Текст поста:</label>
    <?foreach ($content_errors as $content_error): ?>
    <p class="error"><?=$content_error?></p>
    <?endforeach;?>
    <?else: ?>
    <input class="post__title" type="text" name="title" value="<?=$title?>">
    <label for="content">Текст поста:</label>
    <?endif;?>
    <textarea class="post__content" name="content"><?=$content?></textarea>
    <button type="submit">Отредактировать</button>
</form>