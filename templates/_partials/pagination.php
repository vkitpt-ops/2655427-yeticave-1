<?php

/** @var int $page */
/** @var array $pagination */
/** @var array $query */

require_once 'functions/functions.php';

?>

<?php if ($page > 1): ?>
    <li class="pagination-item pagination-item-prev">
        <a href="<?= buildUrl($query, $page - 1) ?>">
            Назад
        </a>
    </li>
<?php endif; ?>

<?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
    <li class="pagination-item <?= $i === $page ? 'pagination-item-active' : '' ?>">
        <a href="<?= buildUrl($query, $i) ?>">
            <?= $i ?>
        </a>
    </li>
<?php endfor; ?>

<?php if ($page < $pagination['total_pages']): ?>
    <li class="pagination-item pagination-item-next">
        <a href="<?= buildUrl($query, $page + 1) ?>">
            Вперед
        </a>
    </li>
<?php endif; ?>
