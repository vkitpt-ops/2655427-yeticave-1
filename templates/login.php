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
    action="login.php"
    method="post"
    autocomplete="off"
>
    <h2>Вход</h2>
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
    <div class="form__item form__item--last<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
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
    <button type="submit" class="button">Войти</button>
</form>
