<header class="header">
    <h1 class="header__title">Редактировать пост</h1>
    <div class="header__btns">
        <a href="<?=ROOT?>post/<?=$id?>">Вернуться к посту</a>
    </div>
</header>
<form method=<?=$form->method();?>>
    <?foreach ($form->fields() as $field): ?>
    <?=$field?>
    <?endforeach;?>
    <button type="submit">Отредактировать</button>
</form>