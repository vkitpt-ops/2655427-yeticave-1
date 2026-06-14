<?php

declare(strict_types=1);

/**
 * Get user information by email
 *
 * @param mysqli $connection Active database connection
 * @param string $email
 *
 * @return array|null
 */
function getUserByEmail(mysqli $connection, string $email): ?array
{
    $sql = "SELECT
        id,
        email,
        name,
        password_hash
    FROM `user`
    WHERE email = ?";

    return fetchOne($connection, $sql, 's', [$email]);
}

/**
 * Adding user data to the database
 *
 * @param mysqli $connection Active database connection
 * @param array  $data
 *
 * @return string|int|null
 */
function addUser(mysqli $connection, array $data): string|int|null
{
    $sql = "INSERT INTO user (
        email,
        name,
        password_hash,
        contact_info
    ) VALUES (?, ?, ?, ?)";

    $stmt = db_get_prepare_stmt($connection, $sql, $data);

    if (mysqli_stmt_execute($stmt)) {
        return mysqli_insert_id($connection);
    }
    return null;
}
