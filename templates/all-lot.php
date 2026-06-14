<?php

/** @var array $categories */
/** @var array $category_lots */
/** @var array $category_name */
/** @var string|null $category_slug */
/** @var int $page */
/** @var array $pagination */
/** @var array $query */

?>

<nav class="nav">
    <ul class="nav__list container">

        <?= include_template('_partials/nav.php', array_merge(
            [
                'mode' => 'nav'
            ],
            compact(
                'categories',
                'category_slug'
            )
        )) ?>

    </ul>
</nav>

<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span>«<?= $category_name['name'] ?>»</span></h2>
        <ul class="lots__list">

            <?php foreach ($category_lots as $lot): ?>
                <?= include_template('_partials/lot-cards.php', compact('lot')) ?>
            <?php endforeach; ?>

        </ul>
    </section>

    <ul class="pagination-list">

        <?= include_template('_partials/pagination.php',
            compact(
                'pagination',
                'page',
                'query'
            )) ?>

    </ul>
</div>
