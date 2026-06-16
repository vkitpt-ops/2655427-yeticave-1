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
    class="form form--add-lot container<?= !empty($errors) ? ' form--invalid' : '' ?>"
    action="add.php"
    method="post"
    enctype="multipart/form-data"
>
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $input_name = 'lot_title'; ?>
        <div class="form__item<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $input_name ?>">Наименование <sup>*</sup></label>
            <input
                id="<?= $input_name ?>"
                type="text"
                name="<?= $input_name ?>"
                placeholder="Введите наименование лота"
                value="<?= esc($form_data[$input_name] ?? '') ?>"
            >
            <span class="form__error"><?= $errors[$input_name] ?? '' ?></span>
        </div>
        <?php $input_name = 'lot_category_id'; ?>
        <div class="form__item<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $input_name ?>">Категория <sup>*</sup></label>
            <select id="<?= $input_name ?>" name="<?= $input_name ?>">
                <option value="">Выберите категорию</option>

                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
                <?php endforeach; ?>

            </select>
            <span class="form__error"><?= $errors[$input_name] ?? ''?></span>
        </div>
    </div>
    <?php $input_name = 'lot_description'; ?>
    <div class="form__item form__item--wide<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= $input_name ?>">Описание <sup>*</sup></label>
        <textarea
            id="<?= $input_name ?>"
            name="<?= $input_name ?>"
            placeholder="Напишите описание лота"><?=htmlspecialchars($form_data[$input_name] ?? '') ?></textarea>
        <span class="form__error"><?= $errors[$input_name] ?? ''?></span>
    </div>
    <?php $input_name = 'lot_img'; ?>
    <div class="form__item form__item--file<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input
                class="visually-hidden"
                type="file"
                id="<?= $input_name ?>"
                name="<?= $input_name ?>"
                value=""
            >
            <label for="<?= $input_name ?>">Добавить</label>
            <span class="form__error"><?= $errors[$input_name] ?? '' ?></span>
        </div>
    </div>
    <?php $input_name = 'lot_start_price'; ?>
    <div class="form__container-three">
        <div class="form__item form__item--small<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $input_name ?>">Начальная цена <sup>*</sup></label>
            <input
                id="<?= $input_name ?>"
                type="number"
                name="<?= $input_name ?>"
                placeholder="0"
                value="<?= esc($form_data[$input_name] ?? ''); ?>"
            >
            <span class="form__error"><?= $errors[$input_name] ?? '' ?></span>
        </div>
        <?php $input_name = 'lot_bid_step'; ?>
        <div class="form__item form__item--small<?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $input_name ?>">Шаг ставки <sup>*</sup></label>
            <input
                id="<?= $input_name ?>"
                type="number"
                name="<?= $input_name ?>"
                placeholder="0"
                value="<?= esc($form_data[$input_name] ?? '') ?>"
            >
            <span class="form__error"><?= $errors[$input_name] ?? '' ?></span>
        </div>
        <?php $input_name = 'lot_expire_date'; ?>
        <div class="form__item <?= isset($errors[$input_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $input_name ?>">Дата окончания торгов <sup>*</sup></label>
            <input
                class="form__input-date"
                type="date"
                id="<?= $input_name ?>"
                name="<?= $input_name ?>"
                value="<?= esc($form_data[$input_name] ?? '') ?>"
            >
            <span class="form__error"><?= $errors[$input_name] ?? '' ?></span>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="form__error form__error--bottom">
            <p>Пожалуйста, исправьте ошибки в форме.</p>
        </div>
    <?php endif; ?>

    <button type="submit" class="button">Добавить лот</button>
</form>
