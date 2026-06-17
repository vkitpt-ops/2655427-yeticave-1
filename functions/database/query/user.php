<?php

declare(strict_types=1);

/**
 * Retrieves a user by email address.
 *
 * @param mysqli $connection Active database connection
 * @param string $email User email address
 *
 * @return array|null User data or null if not found
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
 * Creates a new user and inserts the data into the database.
 *
 * @param mysqli $connection Active database connection
 * @param array $data User data for insertion
 *
 * @return int|null Created user ID or null on failure
 */
function addUser(mysqli $connection, array $data): string|int|null
{
    $user_id = null;

    $sql = "INSERT INTO user (
        email,
        name,
        password_hash,
        contact_info
    ) VALUES (?, ?, ?, ?)";

    $stmt = db_get_prepare_stmt($connection, $sql, $data);

    if (mysqli_stmt_execute($stmt)) {
        $user_id = mysqli_insert_id($connection);
    }

    return $user_id;
}
