<?php

/** @var array $categories */
/** @var array $errors */
/** @var array $form_data */

?>

<nav class="nav">
    <ul class="nav__list container">

        <?= include_template('_partials/nav.php', [
            'mode'       => 'footer',
            'categories' => $categories
        ]); ?>

    </ul>
</nav>

<form
    class="form container<?= !empty($errors) ? ' form--invalid' : '' ?>"
    action="sign-up.php"
    method="post"
    autocomplete="off"
>
    <h2>Регистрация нового аккаунта</h2>
    <?php $input_name = 'user_email'; ?>
    <div class="form__item<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= $input_name ?>">E-mail <sup>*</sup></label>
        <input
            id="<?= $input_name ?>"
            type="text"
            name="<?= $input_name ?>"
            placeholder="Введите e-mail"
            value="<?= esc($form_data[$input_name] ?? '') ?>"
        >
        <span class="form__error"><?= $errors[$input_name] ?? '' ?></span>
    </div>
    <?php $input_name = 'user_password'; ?>
    <div class="form__item<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= $input_name ?>">Пароль <sup>*</sup></label>
        <input
            id="<?= $input_name ?>"
            type="password"
            name="<?= $input_name ?>"
            placeholder="Введите пароль"
            value="<?= esc($form_data[$input_name] ?? '') ?>"
        >
        <span class="form__error"><?= $errors[$input_name] ?? '' ?></span>
    </div>
    <?php $input_name = 'user_name'; ?>
    <div class="form__item<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= $input_name ?>">Имя <sup>*</sup></label>
        <input
            id="<?= $input_name ?>"
            type="text"
            name="<?= $input_name ?>"
            placeholder="Введите имя"
            value="<?= esc($form_data[$input_name] ?? '') ?>"
        >
        <span class="form__error"><?= $errors[$input_name] ?? '' ?></span>
    </div>
    <?php $input_name = 'user_contact_info'; ?>
    <div class="form__item<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= $input_name ?>">Контактные данные <sup>*</sup></label>
        <textarea
            id="<?= $input_name ?>"
            name="<?= $input_name ?>"
            placeholder="Напишите как с вами связаться"
        ><?= esc($form_data[$input_name] ?? '') ?></textarea>
        <span class="form__error"><?= $errors[$input_name] ?? '' ?></span>
    </div>

    <?php if (!empty($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>

    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
