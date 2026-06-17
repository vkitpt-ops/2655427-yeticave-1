<?php

declare(strict_types=1);

/**
 * Creates a connection to the MySQL database.
 *
 * Loads database configuration and initializes a mysqli connection.
 *
 * @return mysqli Active database connection
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
 * Executes a SELECT query and returns all matching rows.
 *
 * Uses prepared statements to safely bind parameters.
 *
 * @param mysqli $connection Active database connection
 * @param string $sql SQL query
 * @param string $types Parameter types for binding
 * @param array $params Query parameters
 *
 * @return array List of rows
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
 * Executes a SELECT query and returns a single row.
 *
 * Uses prepared statements to safely bind parameters.
 *
 * @param mysqli $connection Active database connection
 * @param string $sql SQL query
 * @param string $types Parameter types for binding
 * @param array $params Query parameters
 *
 * @return array|null Row data or null if no result found
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
