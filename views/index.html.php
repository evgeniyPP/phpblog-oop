<header class="header">
    <h1 class="header__title">Блог на PHP</h1>
    <div class="header__btns">
        <?if ($is_auth): ?>
        <a href="<?=ROOT?>post/add">Добавить пост</a>
        <?endif;?>
        <a href="<?=ROOT?>login"><?=$log_btn?></a>
    </div>
</header>
<ul class="posts">
    <?foreach ($posts as $post): ?>
    <li class="posts__item">
        <a href="<?=ROOT?>post/<?=$post['id_post']?>"><?=$post['title']?></a>
    </li>
    <?endforeach;?>
</ul>