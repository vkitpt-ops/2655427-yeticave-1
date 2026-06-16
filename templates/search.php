<?php

/** @var array $categories */
/** @var array $found_lots */
/** @var array $query */
/** @var array $pagination */
/** @var int $page */
/** @var string $search_value */

?>

<nav class="nav">
    <ul class="nav__list container">

        <?= include_template('_partials/nav.php', [
            'mode'       => 'footer',
            'categories' => $categories
        ]); ?>

    </ul>
</nav>

<div class="container">
    <section class="lots">

        <?php if (!empty($found_lots)): ?>
            <h2>Результаты поиска по запросу «<span><?= $search_value ?></span>»</h2>
        <?php else: ?>
            <h2>Ничего не найдено по вашему запросу</h2>
        <?php endif; ?>

        <ul class="lots__list">

            <?php foreach ($found_lots as $lot): ?>
                <?= include_template('_partials/lot-card.php', compact('lot')) ?>
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
