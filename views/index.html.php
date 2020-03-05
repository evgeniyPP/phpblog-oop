<header class="header">
    <h1 class="header__title">Блог на PHP</h1>
    <div class="header__btns">
        <?if ($is_auth): ?>
        <p class="username" style="margin:0; padding: 8px 16px; text-decoration: underline;"><?=$username?></p>
        <a href="<?=ROOT?>post/add">Добавить пост</a>
        <a href="<?=ROOT?>login/logout">Выйти</a>
        <?else: ?>
        <a href="<?=ROOT?>login">Войти / Зарегистрироваться</a>
        <?endif;?>
    </div>
</header>
<ul class="posts">
    <?foreach ($posts as $post): ?>
    <li class="posts__item">
        <a href="<?=ROOT?>post/<?=$post['id']?>"><?=$post['title']?></a>
    </li>
    <?endforeach;?>
</ul>