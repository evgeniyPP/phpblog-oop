<header class="header">
    <h1 class="header__title">Добавить пост</h1>
    <div class="header__btns">
        <a href="<?=ROOT?>">Вернуться на главную</a>
    </div>
</header>
<form method=<?=$form->method();?>>
    <?foreach ($form->fields() as $field): ?>
    <?=$field?>
    <?endforeach;?>
    <button type="submit">Добавить</button>
</form>