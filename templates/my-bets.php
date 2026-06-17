<?php

/** @var array $categories */
/** @var array $bids */
/** @var array $winner_bid */
/** @var array $auth_user */

?>

<nav class="nav">
    <ul class="nav__list container">

        <?= include_template('_partials/nav.php', [
            'mode'       => 'footer',
            'categories' => $categories
        ]); ?>

    </ul>
</nav>

<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">

        <?php foreach ($bids as $bid): ?>
            <tr class="rates__item">
                <td class="rates__info">
                    <div class="rates__img">
                        <img
                            src="<?= esc($bid['img_url'] ?? '') ?>"
                            width="54" height="40"
                            alt="<?= esc($bid['category_name'] ?? '') ?>"
                        >
                    </div>
                    <h3 class="rates__title">
                        <a href="lot.php?id=<?= esc($bid['lot_id'] ?? '') ?>"><?= esc($bid['title'] ?? '') ?></a>

                        <?php if (in_array($bid['id'], $winner_bid)): ?>
                            <p><?= esc($bid['contact_info'] ?? '') ?></p>
                        <?php endif; ?>

                    </h3>
                </td>
                <td class="rates__category">
                    <?= esc($bid['category_name'] ?? '') ?>
                </td>
                <td class="rates__timer">

                    <?php [$hours, $minutes] = getRemainingTime(esc($bid['expire_date'] ?? '')); ?>

                    <?php if ($bid['expire_date'] < date('Y-m-d H:i:s')): ?>

                        <?php if (in_array($bid['id'], $winner_bid)): ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <?php else: ?>
                            <div class="timer timer--end">Торги окончены</div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="timer<?= $hours < 1 ? ' timer--finishing' : '' ?>">
                            <?= sprintf('%02d:%02d', $hours, $minutes) ?>
                        </div>
                    <?php endif; ?>

                </td>
                <td class="rates__price">
                    <?= formatPrice($bid['amount'] ?? 0) ?>
                </td>
                <td class="rates__time">
                    <?= getTimeAgo($bid['created_at'] ?? '') ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>
</section>
