<?php

declare(strict_types=1);

/**
 * Retrieves all bids for a specific lot
 *
 * @param mysqli $connection Active database connection
 * @param int    $lot_id
 *
 * @return array
 */
function getBidsByLotId(mysqli $connection, int $lot_id): array
{
    if ($lot_id === 0) {
        return [];
    }

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

    return fetchAll($connection, $sql, 'i', [$lot_id]);
}

/**
 * Retrieves all bids placed by a specific user
 *
 * @param mysqli $connection Active database connection
 * @param int    $user_id    User ID whose bids should be retrieved
 *
 * @return array
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
 * Inserts a new bid into the database.
 *
 * @param mysqli $connection Active database connection
 * @param array  $data       Bid data for insertion
 *
 * @return int|string|null Returns inserted bid ID on success, null on failure
 */
function addBid(mysqli $connection, array $data): string|int|null
{
    $sql = "INSERT INTO bid (
        amount,
        user_id,
        lot_id
    ) VALUES (?, ?, ?)";

    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_insert_id($connection);
    }
    return null;
}

/**
 * Assigns winning bids to all expired lots
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
 * Returns IDs of winning bids belonging to the specified user
 *
 * @param mysqli $connection Active database connection
 * @param int    $user_id    User ID to fetch winner bids for
 *
 * @return array
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
 * Receives a list of winning bets with user and lot data
 *
 * @param mysqli $connection Active database connection
 *
 * @return array
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
    WHERE lot.winner_notified !=1";

    return fetchAll($connection, $sql);
}

/**
 * Updates the winner_notified field in the lot table, setting the value to 1 for the specified lot
 * (the winner's email has been sent)
 *
 * @param mysqli $connection Active database connection
 * @param int    $lot_id     ID of the lot to mark
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
