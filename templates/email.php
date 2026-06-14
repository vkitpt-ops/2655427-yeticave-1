<?php

/** @var string $lot_url */
/** @var string $bets_url */
/** @var string $winner_name */
/** @var string $lot_name */

?>

<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= esc($winner_name) ?></p>
<p>
    Ваша ставка для лота
        <a href="<?= esc($lot_url) ?>"><?= esc($lot_name) ?></a>
    победила.
</p>
<p>
    Перейдите по ссылке
        <a href="<?= esc($bets_url) ?>">мои ставки</a>,
    чтобы связаться с автором объявления
</p>
<small>Интернет-Аукцион "YetiCave"</small>
