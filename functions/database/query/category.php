<?php

declare(strict_types=1);

/**
 * Retrieves all available categories from the database.
 *
 * @param mysqli $connection Active database connection
 *
 * @return array List of categories
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
 * Retrieves a category name by its slug.
 *
 * Searches for a category using the provided slug
 * and returns the category name.
 *
 * @param mysqli $connection Active database connection
 * @param string|null $category_slug Category slug (can be null)
 *
 * @return array Category name data
 */
function getCategoryName(mysqli $connection, ?string $category_slug): array
{
    $category_slug = mysqli_real_escape_string($connection, (string) $category_slug);

    $sql = "SELECT
        name
    FROM `category`
    WHERE slug = ?";

    return fetchOne($connection, $sql, 's', [$category_slug]);
}
