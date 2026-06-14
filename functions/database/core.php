<?php

declare(strict_types=1);

/**
 * connect to MySQL
 *
 * @return mysqli
 */
function connectToMySQL(): mysqli
{
    $mysql = require __DIR__ . '/../../config/db.php';

    $connection = mysqli_connect(
        $mysql['host'],
        $mysql['user'],
        $mysql['password'],
        $mysql['database']
    );

    if (!$connection) {
        exit('Ошибка подключения: ' . mysqli_connect_error());
    }

    mysqli_set_charset($connection, 'utf8mb4');

    return $connection;
}

/**
 * Executes a SELECT query safely and returns all rows
 *
 * @param mysqli $connection Active database connection
 * @param string $sql
 *
 * @return array
 */
function fetchAll(mysqli $connection, string $sql, string $types = '', array $params = []): array
{
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        error_log(mysqli_error($connection));
        return [];
    }

    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        error_log(mysqli_error($connection));
        return [];
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Executes a SELECT query safely and returns one row
 *
 * @param mysqli $connection Active database connection
 * @param string $sql
 *
 * @return array|null
 */
function fetchOne(mysqli $connection, string $sql, string $types = '', array $params = []): ?array
{
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        error_log(mysqli_error($connection));
        return null;
    }

    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        error_log(mysqli_error($connection));
        return null;
    }

    return mysqli_fetch_assoc($result) ?: null;
}
