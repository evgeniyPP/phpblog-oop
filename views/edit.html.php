<header class="header">
    <h1 class="header__title">Редактировать пост</h1>
    <div class="header__btns">
        <a href="<?=ROOT?>post/<?=$id?>">Вернуться к посту</a>
    </div>
</header>
<?if ($error): ?>
<p class="error"><?=$error?></p>
<?endif;?>
<form method="post">
    <label for="title">Название поста:</label>
    <input class="post__title" type="text" name="title" value="<?=$title?>">
    <label for="content">Текст поста:</label>
    <textarea class="post__content" name="content"><?=$content?></textarea>
    <button type="submit">Отредактировать</button>
</form>