<?php

/** @var string $title */
/** @var string $page_content */
/** @var array $auth_user */
/** @var array $categories */
/** @var string $search_value */

$search_value = $search_value ?? '';

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="assets/css/normalize.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/flatpickr.min.css" rel="stylesheet">
</head>

<body>
    <div class="page-wrapper">

        <?= include_template('layout/_header.php', compact('auth_user', 'search_value')) ?>

        <main><?= $page_content ?></main>
    </div>

    <?= include_template('layout/_footer.php', compact('categories')) ?>

    <script src="assets/js/flatpickr.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
