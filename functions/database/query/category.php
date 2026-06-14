<?php

declare(strict_types=1);

/**
 * Get all categories
 *
 * @param mysqli $connection Active database connection
 *
 * @return array
 */
function getAllCategories(mysqli $connection): array
{
    $sql = "SELECT
        id,
        name,
        slug
    FROM `category`";

    return fetchAll($connection, $sql);
}

/**
 * Get the category name
 *
 * @param mysqli      $connection    Active database connection
 * @param string|null $category_slug Category slug (can be null)
 *
 * @return array
 */
function getCategoryName(mysqli $connection, ?string $category_slug): array
{
    $category_slug = mysqli_real_escape_string($connection, (string)$category_slug);

    $sql = "SELECT
        name
    FROM `category`
    WHERE slug = ?";

    return fetchOne($connection, $sql, 's', [$category_slug]);
}
