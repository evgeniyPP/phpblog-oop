<form method=<?=$form->method();?>>
    <?if (isset($no_auth_error)): ?>
    <p class="error"><?=$no_auth_error?></p>
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