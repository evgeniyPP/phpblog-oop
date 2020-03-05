<form method="post">
    <?if (isset($no_auth_error)): ?>
    <p class="error"><?=$no_auth_error?></p>
    <?endif;?>
    <?if ($is_validation_errors): ?>
    <?foreach ($login_errors as $login_error): ?>
    <p class="error"><?=$login_error?></p>
    <?endforeach;?>
    <?foreach ($password_errors as $password_error): ?>
    <p class="error"><?=$password_error?></p>
    <?endforeach;?>
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
        <button type="submit" class="btns__submit" name="login_form_submit" value="login">Войти</button>
        <button type="submit" class="btns__submit" name="login_form_submit" value="signup">Зарегистрироваться</button>
        <a href="<?=ROOT?>" class="btns__links">На главную</a>
    </div>
</form>