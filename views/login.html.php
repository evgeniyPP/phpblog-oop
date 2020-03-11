<form method=<?=$form->method();?>>
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
    <?foreach ($form->fields() as $field): ?>
    <?=$field?>
    <?endforeach;?>
    <div class="form__btns">
        <button type="submit" class="btns__submit" name="login_form_submit" value="login">Войти</button>
        <button type="submit" class="btns__submit" name="login_form_submit" value="signup">Зарегистрироваться</button>
        <a href="<?=ROOT?>" class="btns__links">На главную</a>
    </div>
</form>