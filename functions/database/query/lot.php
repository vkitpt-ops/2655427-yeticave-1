<?php

declare(strict_types=1);

/**
 * Retrieves the newest active lots.
 *
 * Returns the latest lots that have not expired.
 *
 * @param mysqli $connection Active database connection
 *
 * @return array List of newest lots
 */
function getNewLots(mysqli $connection): array
{
    $sql = "SELECT
        lot.id AS lot_id,
        lot.title,
        lot.start_price,
        lot.img_url,
        category.name AS category_name,
        lot.expire_date
    FROM `lot`
    JOIN `category` ON category.id = lot.category_id
    WHERE lot.expire_date > NOW()
    ORDER BY lot.created_at DESC
    LIMIT 6";

    return fetchAll($connection, $sql);
}

/**
 * Retrieves a lot by its ID.
 *
 * @param mysqli $connection Active database connection
 * @param int $id Lot ID
 *
 * @return array|null Lot data or null if not found
 */
function getLotById(mysqli $connection, int $id): ?array
{
    $lot = null;

    if ($id > 0) {
        $sql = "SELECT
            lot.id,
            lot.title,
            lot.description,
            lot.start_price,
            COALESCE(MAX(bid.amount), lot.start_price) AS current_price,
            lot.bid_step,
            lot.img_url,
            category.name AS category_name,
            lot.expire_date,
            lot.author_id
        FROM `lot`
        LEFT JOIN `bid` ON bid.lot_id = lot.id
        JOIN `category` ON category.id = lot.category_id
        WHERE lot.id = ?
        GROUP BY lot.id";

        $lot = fetchOne($connection, $sql, 'i', [$id]);
    }

    return $lot;
}

/**
 * Retrieves the number of active lots in a category.
 *
 * @param mysqli $connection Active database connection
 * @param string|null $category_slug Category slug
 *
 * @return int Number of matching lots
 */
function getLotsCountByCategorySlug(mysqli $connection, ?string $category_slug): int
{
    $category_slug = mysqli_real_escape_string($connection, (string)$category_slug);

    $sql = "SELECT
        COUNT(*) as cnt
    FROM lot
    JOIN category ON category.id = lot.category_id
    WHERE lot.expire_date > NOW()
        AND category.slug = ?";

    $result = fetchOne($connection, $sql, 's', [$category_slug]);
    return (int)($result['cnt'] ?? 0);
}

/**
 * Retrieves active lots by category.
 *
 * @param mysqli $connection Active database connection
 * @param string|null $category_slug Category slug
 * @param int $limit Maximum number of results
 * @param int $offset Number of records to skip
 *
 * @return array List of lots
 */
function getLotsByCategorySlug(mysqli $connection, ?string $category_slug, int $limit, int $offset): array
{
    $category_slug = mysqli_real_escape_string($connection, (string)$category_slug);

    $sql = "SELECT
        lot.id AS lot_id,
        lot.title,
        lot.start_price,
        lot.img_url,
        category.name AS category_name,
        lot.expire_date
    FROM `lot`
    JOIN `category` ON category.id = lot.category_id
    WHERE lot.expire_date > NOW()
    AND category.slug = ?
    ORDER BY lot.created_at DESC
    LIMIT $limit OFFSET $offset";

    return fetchAll($connection, $sql, 's', [$category_slug]);
}

/**
 * Retrieves the total number of lots matching a search query.
 *
 * @param mysqli $connection Active database connection
 * @param string $value Search query
 *
 * @return int Number of matching lots
 */
function getLotsCountBySearch(mysqli $connection, string $value): int
{
    $sql = "SELECT
        COUNT(*) as cnt
    FROM `lot`
    JOIN `category` ON category.id = lot.category_id
    WHERE MATCH(lot.title,lot.description) AGAINST(?)";

    $row = fetchOne($connection, $sql, 's', [$value]);

    return (int)($row['cnt'] ?? 0);
}

/**
 * Retrieves lots matching a search query.
 *
 * Uses full-text search by lot title and description.
 *
 * @param mysqli $connection Active database connection
 * @param string $value Search query
 * @param int $limit Maximum number of results
 * @param int $offset Number of records to skip
 *
 * @return array List of found lots
 */
function getAllLotsBySearch(mysqli $connection, string $value, int $limit, int $offset): array
{
    $sql = "SELECT
        lot.id AS lot_id,
        lot.title,
        lot.start_price,
        lot.img_url,
        category.name AS category_name,
        lot.expire_date
    FROM `lot`
    JOIN `category` ON category.id = lot.category_id
    WHERE MATCH(lot.title,lot.description) AGAINST(?)
    ORDER BY lot.created_at DESC
    LIMIT $limit OFFSET $offset";

    return fetchAll($connection, $sql, 's', [$value]);
}

/**
 * Creates a new lot and inserts it into the database.
 *
 * @param mysqli $connection Active database connection
 * @param array $data Lot data for insertion
 *
 * @return int|null Created lot ID or null on failure
 */
function addLot(mysqli $connection, array $data): int|null
{
    $lot_id = null;
    $sql = "INSERT INTO lot (
        title,
        description,
        start_price,
        expire_date,
        bid_step,
        category_id,
        img_url,
        author_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = db_get_prepare_stmt($connection, $sql, $data);

    if (mysqli_stmt_execute($stmt)) {
        $lot_id = mysqli_insert_id($connection);
    }

    return $lot_id;
}
