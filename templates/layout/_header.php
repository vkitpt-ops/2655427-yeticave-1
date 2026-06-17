<?php

/** @var array $auth_user */
/** @var string $search_value */

?>

<header class="main-header">
    <div class="main-header__container container">
        <h1 class="visually-hidden">YetiCave</h1>
        <a class="main-header__logo" href="index.php">
            <img
                src="assets/img/logo.svg"
                width="160"
                height="39"
                alt="Логотип компании YetiCave"
            >
        </a>
        <form
            class="main-header__search"
            method="get"
            action="search.php"
            autocomplete="off"
        >
            <input
                type="search"
                name="search"
                placeholder="Поиск лота"
                value="<?= esc($search_value) ?>"
            >
            <input
                class="main-header__search-btn"
                type="submit"
                value="Найти"
            >
        </form>
        <a class="main-header__add-lot button" href="add.php">Добавить лот</a>
        <nav class="user-menu">

            <?php if (!empty($auth_user['id'])): ?>
                <div class="user-menu__logged">
                    <p><?= esc($auth_user['name'] ?? '') ?></p>
                    <a class="user-menu__bets" href="my-bets.php">Мои ставки</a>
                    <a class="user-menu__logout" href="logout.php">Выход</a>
                </div>
            <?php else: ?>
                <ul class="user-menu__list">
                    <li class="user-menu__item">
                        <a href="sign-up.php">Регистрация</a>
                    </li>
                    <li class="user-menu__item">
                        <a href="login.php">Вход</a>
                    </li>
                </ul>
            <?php endif; ?>

        </nav>
    </div>
</header>
