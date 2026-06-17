<?php

declare(strict_types=1);

/**
 * Escapes HTML special characters for safe output.
 *
 * Prevents HTML injection by converting special characters
 * into HTML entities.
 *
 * @param string $value String to escape
 *
 * @return string Escaped string
 */
function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Formats a price for display.
 *
 * Rounds the value up and adds the currency symbol.
 *
 * @param float $price Price value
 *
 * @return string Formatted price
 */
function formatPrice(float $price): string
{
    $price = ceil($price);

    return ($price > FORMAT_THRESHOLD
        ? number_format($price, 0, '', ' ')
        : $price) . ' ₽';
}

/**
 * Calculates the remaining time until a specified date.
 *
 * Returns the remaining time as an array containing hours and minutes.
 *
 * @param string $value Target date and time
 *
 * @return array Remaining time in hours and minutes
 */
function getRemainingTime(string $value): array
{
    $hours = 0;
    $minutes = 0;

    if (!empty($value)) {
        $now = new DateTime();
        $future = new DateTime($value);

        $diff = date_diff($now, $future);

        $total_minutes = ($diff->days * HOURS_IN_DAY + $diff->h) * MINUTES_IN_HOUR + $diff->i;

        $hours = intdiv($total_minutes, MINUTES_IN_HOUR);
        $minutes = $total_minutes % MINUTES_IN_HOUR;
    }

    return [$hours, $minutes];
}

/**
 * Calculates pagination parameters.
 *
 * Returns total pages, current page and SQL offset values.
 *
 * @param int $per_page Number of items per page
 * @param int $page Current page number
 * @param int $total_elements Total number of items
 *
 * @return array Pagination data
 */
function getPaginationData(int $per_page, int $page, int $total_elements): array
{
    $total_pages = (int) ceil($total_elements / $per_page);
    $page = max(1, min($page, max(1, $total_pages)));

    return [
        'total_pages' => $total_pages,
        'offset'      => ($page - 1) * $per_page,
        'page'        => $page
    ];
}

/**
 * Builds a URL query string for pagination.
 *
 * Preserves existing query parameters and updates the page number.
 *
 * @param array $query Existing query parameters
 * @param int $pageNumber Page number to add to the URL
 *
 * @return string Generated query string
 */
function buildUrl(array $query, int $pageNumber): string
{
    $query['page'] = $pageNumber;

    return '?' . http_build_query($query);
}

/**
 * Returns a human-readable relative time.
 *
 * Converts a date and time into a relative format:
 * "just now", "5 minutes ago", "yesterday", etc.
 *
 * @param string $datetime Date and time string
 *
 * @return string Relative time representation
 */
function getTimeAgo(string $datetime): string
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    $result = '';

    if ($diff < SECONDS_IN_MINUTE) {
        $result = 'только что';
    } elseif ($diff < SECONDS_IN_HOUR) {
        $minutes = intdiv($diff, SECONDS_IN_MINUTE);
        $result = $minutes . ' '
            . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут')
            . ' назад';
    } elseif ($diff < SECONDS_IN_DAY) {
        $hours = intdiv($diff, SECONDS_IN_HOUR);
        $result = $hours . ' '
            . get_noun_plural_form($hours, 'час', 'часа', 'часов')
            . ' назад';
    } else {
        $days = intdiv($diff, SECONDS_IN_DAY);

        $result = $days === 1
            ? 'вчера'
            : $days . ' '
                . get_noun_plural_form($days, 'день', 'дня', 'дней')
                . ' назад';
    }

    return $result;
}
