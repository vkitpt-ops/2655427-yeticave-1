<?php

declare(strict_types=1);
date_default_timezone_set('UTC');

require_once __DIR__ . '/config/constant.php';

require_once __DIR__ . '/enum/HttpMethodEnum.php';
require_once __DIR__ . '/enum/HttpStatusCodeEnum.php';

require_once __DIR__ . '/functions/helpers.php';
require_once __DIR__ . '/functions/functions.php';

require_once __DIR__ . '/functions/form/prepare.php';
require_once __DIR__ . '/functions/form/upload.php';

require_once __DIR__ . '/functions/database/core.php';
require_once __DIR__ . '/functions/database/query/lot.php';
require_once __DIR__ . '/functions/database/query/bid.php';
require_once __DIR__ . '/functions/database/query/user.php';
require_once __DIR__ . '/functions/database/query/category.php';

require_once __DIR__ . '/validation/const.php';
require_once __DIR__ . '/validation/rules.php';
require_once __DIR__ . '/validation/index.php';
require_once __DIR__ . '/validation/validators.php';

$connection = connectToMySQL();

$categories = getAllCategories($connection);

session_start();

$auth_user = [
    'name' => $_SESSION[USER_SESSION_KEY]['name'] ?? '',
    'id'   => $_SESSION[USER_SESSION_KEY]['id'] ?? null,
];
