<?php

/** @var array $categories */
/** @var array $errors */
/** @var array $form_data */

?>

<nav class="nav">
    <ul class="nav__list container">

        <?= include_template('_partials/nav.php', array_merge(
            [
                'mode' => 'footer'
            ],
            compact(
                'categories'
            )
        )); ?>

    </ul>
</nav>

<form
    class="form form--add-lot container <?= !empty($errors) ? 'form--invalid' : '' ?>"
    action="add.php"
    method="post"
    enctype="multipart/form-data"
>
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= isset($errors['lot-name']) ? "form__item--invalid" : '' ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input
                id="lot-name"
                type="text"
                name="lot-name"
                placeholder="Введите наименование лота"
                value="<?= esc($form_data['lot-name'] ?? '') ?>"
            >
            <span class="form__error"><?= $errors['lot-name'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['category']) ? "form__item--invalid" : '' ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option value="">Выберите категорию</option>

                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
                <?php endforeach; ?>

            </select>
            <span class="form__error"><?= $errors['category']?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?= isset($errors['message']) ? "form__item--invalid" : '' ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea
            id="message"
            name="message"
            placeholder="Напишите описание лота"><?=htmlspecialchars($form_data['message'] ?? '') ?></textarea>
        <span class="form__error"><?= $errors['message'] ?></span>
    </div>
    <div class="form__item form__item--file <?= isset($errors['lot-img']) ? "form__item--invalid" : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input
                class="visually-hidden"
                type="file"
                id="lot-img"
                name="lot-img"
                value=""
            >
            <label for="lot-img">Добавить</label>
            <span class="form__error"><?= $errors['lot-img'] ?></span>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?= isset($errors['lot-rate']) ? "form__item--invalid" : '' ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input
                id="lot-rate"
                type="number"
                name="lot-rate"
                placeholder="0"
                value="<?= esc($form_data['lot-rate'] ?? ''); ?>"
            >
            <span class="form__error"><?= $errors['lot-rate'] ?></span>
        </div>
        <div class="form__item form__item--small <?= isset($errors['lot-step']) ? "form__item--invalid" : '' ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input
                id="lot-step"
                type="number"
                name="lot-step"
                placeholder="0"
                value="<?= esc($form_data['lot-step'] ?? '') ?>"
            >
            <span class="form__error"><?= $errors['lot-step'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['lot-date']) ? "form__item--invalid" : '' ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input
                class="form__input-date"
                type="date"
                id="lot-date"
                name="lot-date"
                value="<?= esc($form_data['lot-date'] ?? '') ?>"
            >
            <span class="form__error"><?= $errors['lot-date'] ?></span>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="form__error form__error--bottom">
            <p>Пожалуйста, исправьте ошибки в форме.</p>
        </div>
    <?php endif; ?>

    <button type="submit" class="button">Добавить лот</button>
</form>
