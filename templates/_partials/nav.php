<?php

/** @var array $categories */
/** @var string $mode */
/** @var string|null $category_slug */

?>

<?php foreach ($categories as $category): ?>

    <?php if ($mode === 'nav'): ?>
        <?php $is_current = !empty($category_slug) && ($category['slug']?? '') === $category_slug; ?>
        <li class="nav__item<?= $is_current ? ' nav__item--current' : '' ?>">
            <a href="all-lot.php?category=<?= esc($category['slug'] ?? '') ?>">
                <?= esc($category['name'] ?? '') ?>
            </a>
        </li>

    <?php elseif ($mode === 'promo'): ?>
        <li class="promo__item promo__item--<?= esc($category['slug'] ?? '') ?>">
            <a class="promo__link" href="all-lot.php?category=<?= esc($category['slug'] ?? '') ?>">
                <?= esc($category['name'] ?? '') ?>
            </a>
        </li>

    <?php elseif ($mode === 'footer'): ?>
        <li class="nav__item">
            <a href="all-lot.php?category=<?= esc($category['slug'] ?? '') ?>">
                <?= esc($category['name'] ?? '') ?>
            </a>
        </li>
    <?php endif; ?>

<?php endforeach; ?>
