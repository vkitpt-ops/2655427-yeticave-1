<?php

/** @var array $lot */

?>

<li class="lots__item lot">
    <div class="lot__image">
        <img
            src="<?= esc($lot['img_url'] ?? '') ?>"
            width="350"
            height="260"
            alt="<?= esc($lot['category_name'] ?? '') ?>"
        >
    </div>
    <div class="lot__info">
        <span class="lot__category"><?= esc($lot['category_name'] ?? '') ?></span>
        <h3 class="lot__title">
            <a class="text-link" href="lot.php?id=<?= esc($lot['lot_id'] ?? '') ?>"><?= esc($lot['title'] ?? '') ?></a>
        </h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?= formatPrice($lot['start_price'] ?? 0) ?></span>
            </div>

            <?php [$hours, $minutes] = getRemainingTime($lot['expire_date'] ?? ''); ?>

            <div class="lot__timer timer<?= $hours < 1 ? ' timer--finishing' : '' ?>">
                <?= sprintf('%02d:%02d', $hours, $minutes) ?>
            </div>
        </div>
    </div>
</li>
