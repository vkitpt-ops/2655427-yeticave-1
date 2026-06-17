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

        <h2>Результаты поиска
            <?php if (!empty($search_value)): ?>
                по запросу «<span><?= $search_value ?></span>»
            <?php endif; ?>
        </h2>

        <ul class="lots__list">

            <?php if (!empty($found_lots)): ?>

                <?php foreach ($found_lots as $lot): ?>
                    <?= include_template('_partials/lot-card.php', compact('lot')) ?>
                <?php endforeach; ?>

            <?php else: ?>
                <p>Ничего не найдено по вашему запросу</p>
            <?php endif; ?>

        </ul>
    </section>

    <?php if (isset($pagination['total_pages']) && $pagination['total_pages'] > 1): ?>
    <ul class="pagination-list">

        <?= include_template('_partials/pagination.php',
            compact(
                'pagination',
                'page',
                'query'
            )) ?>

    </ul>
    <?php endif; ?>

</div>
