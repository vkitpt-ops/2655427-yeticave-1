<?php

declare(strict_types=1);

/**
 * Retrieves all bids for a specific lot
 *
 * @param mysqli $connection Active database connection
 * @param int $lot_id
 *
 * @return array List of bids
 */
function getBidsByLotId(mysqli $connection, int $lot_id): array
{
    $bids = [];

    if ($lot_id > 0) {
        $sql = "SELECT
            user.name AS user_name,
            bid.amount,
            bid.created_at,
            bid.user_id,
            lot.start_price,
            lot.bid_step
        FROM `bid`
        JOIN `user` ON  bid.user_id = user.id
        JOIN `lot` ON bid.lot_id = lot.id
        WHERE bid.lot_id = ?
        ORDER BY bid.created_at DESC";

        $bids = fetchAll($connection, $sql, 'i', [$lot_id]);
    }

    return $bids;
}

/**
 * Retrieves all bids placed by a specific user
 *
 * @param mysqli $connection Active database connection
 * @param int $user_id User ID
 *
 * @return array List of user bids
 */
function getBidsByUserId(mysqli $connection, int $user_id): array
{
    $sql = "SELECT
        bid.id,
        bid.user_id,
        bid.lot_id,
        bid.created_at,
        lot.expire_date,
        lot.img_url,
        lot.title,
        bid.amount,
        category.name AS category_name,
        user.contact_info
    FROM `bid`
    JOIN `lot`ON bid.lot_id = lot.id
    LEFT JOIN `category` ON category.id = lot.category_id
    LEFT JOIN `user` ON user.id = bid.user_id
    WHERE bid.user_id = ?
    ORDER BY bid.created_at DESC";

    return fetchAll($connection, $sql, 'i', [$user_id]);
}

/**
 * Creates a new bid and inserts it into the database.
 *
 * @param mysqli $connection Active database connection
 * @param array $data Bid data for insertion
 *
 * @return int|null Created bid ID or null on failure
 */
function addBid(mysqli $connection, array $data): int|null
{
    $bid_id = null;
    $sql = "INSERT INTO bid (
        amount,
        user_id,
        lot_id
    ) VALUES (?, ?, ?)";

    $stmt = db_get_prepare_stmt($connection, $sql, $data);

    if (mysqli_stmt_execute($stmt)) {
        $bid_id = mysqli_insert_id($connection);
    }

    return $bid_id;
}

/**
 * Assigns winning bids to expired lots.
 *
 * Finds the highest bid for each expired lot
 * and assigns it as the winning bid.
 *
 * @param mysqli $connection Active database connection
 *
 * @return void
 */
function assignWinnerBids(mysqli $connection): void
{
    $sql = "UPDATE `lot`
    SET winner_bid_id = (
        SELECT bid.id
        FROM `bid`
        WHERE bid.lot_id = lot.id
        ORDER BY bid.amount DESC, bid.created_at ASC
        LIMIT 1)
    WHERE lot.expire_date < NOW()
        AND lot.winner_bid_id IS NULL";

    if (!$connection->query($sql)) {
        error_log($connection->error);
    }
}

/**
 * Retrieves winning bid IDs for a specific user.
 *
 * @param mysqli $connection Active database connection
 * @param int $user_id User ID
 *
 * @return array List of winning bid IDs
 */
function getWinnerBidIds(mysqli $connection, int $user_id): array
{
    $sql = "SELECT
        lot.id AS lot_id,
        lot.winner_bid_id
    FROM `lot`
    JOIN `bid` ON bid.id = lot.winner_bid_id
    WHERE bid.user_id = ?";

    $rows = fetchAll($connection, $sql, 'i', [$user_id]);

    return array_column($rows, 'winner_bid_id');
}

/**
 * Retrieves winning bids that have not been notified.
 *
 * Returns bid, user and lot information
 * for sending winner notifications.
 *
 * @param mysqli $connection Active database connection
 *
 * @return array List of winning bids
 */
function getWinnerBids(mysqli $connection): array
{
    $sql = "SELECT
        bid.id AS bid_id,
        bid.user_id,
        bid.lot_id,
        lot.title,
        user.name,
        user.email
    FROM `bid`
    JOIN `user` ON user.id = bid.user_id
    JOIN `lot` ON lot.winner_bid_id = bid.id
    WHERE lot.winner_notified IS NULL";

    return fetchAll($connection, $sql);
}

/**
 * Marks a lot as notified after sending a winner notification.
 *
 * @param mysqli $connection Active database connection
 * @param int $lot_id Lot ID
 *
 * @return void
 */
function setWinnerNotified(mysqli $connection, int $lot_id): void
{
    $sql = "UPDATE `lot`
    SET winner_notified = 1
    WHERE id = ?";

    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
}
