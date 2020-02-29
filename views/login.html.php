<form method="post">
    <?if ($error): ?>
    <p class="error"><?=$error?></p>
    <?endif;?>
    <label for="login">Логин</label>
    <input type="text" name="login">
    <label for="password">Пароль</label>
    <input type="password" name="password">
    <label class="remember">
        <input type="checkbox" name="remember">
        <span></span>
        Запомнить
    </label>
    <div class="form__btns">
        <button type="submit" class="btns__submit">Войти</button>
        <a href="<?=ROOT?>" class="btns__toindex">На главную</a></button>
    </div>
</form>