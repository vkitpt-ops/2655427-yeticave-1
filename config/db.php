<?php

declare(strict_types=1);

// host for docker-build must be "mysql"
return [
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'database' => getenv('DB_NAME')
];
