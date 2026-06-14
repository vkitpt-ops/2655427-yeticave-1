<?php

declare(strict_types=1);

/**
 * Escapes HTML characters for safe string output
 *
 * @param string $value
 *
 * @return string
 */
function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Formats the price for display, round up the value
 *
 * @param float $price
 *
 * @return string
 */
function formatPrice(float $price): string
{
    $price = ceil($price);

    return ($price > FORMAT_THRESHOLD
        ? number_format($price, 0, '', ' ')
        : $price) . ' ₽';
}

/**
 * Calculates the remaining time until the specified date
 *
 * @param string $value
 *
 * @return array
 */
function getRemainingTime(string $value): array
{
    if (!empty($value)) {
        $now = new DateTime();
        $future = new DateTime($value);

        $diff = date_diff($now, $future);

        $total_minutes = ($diff->days * HOURS_IN_DAY + $diff->h) * MINUTES_IN_HOUR + $diff->i;

        $hours = intdiv($total_minutes, MINUTES_IN_HOUR);
        $minutes = $total_minutes % MINUTES_IN_HOUR;

        return [$hours, $minutes];
    }
    return [0, 0];
}

/**
 * Calculates pagination data for SQL queries and UI.
 *
 * @param int $per_page        Number of items per page
 * @param int $page            Current page number (1-based)
 * @param int $total_elements  Total number of items in dataset
 *
 * @return array
 */
function getPaginationData(int $per_page, int $page, int $total_elements): array
{
    $total_pages = (int)ceil($total_elements / $per_page);
    $page = max(1, min($page, max(1, $total_pages)));

    return [
        'total_pages' => $total_pages,
        'offset'      => ($page - 1) * $per_page,
        'page'        => $page
    ];
}

/**
 * Builds a URL query string for pagination with preserved filters.
 *
 * @param array $query       Existing query parameters
 * @param int   $pageNumber  Target page number to set in URL
 *
 * @return string Generated query string
 */
function buildUrl(array $query, int $pageNumber): string
{
    $query['page'] = $pageNumber;

    return '?' . http_build_query($query);
}

/**
 * Returns a human-readable representation of the past time
 * relative to the passed date/time
 *
 * Examples:
 * - "только что" (если прошло меньше 60 секунд)
 * - "5 минут назад"
 * - "2 часа назад"
 * - "вчера"
 * - "3 дня назад"
 *
 *  @param string $datetime Date and time in a format understood by strtotime (Y-m-d H:i:s)
 *
 * @return string Relative time (for example: "5 minutes ago")
 */
function getTimeAgo(string $datetime): string
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    $result = '';

    if ($diff < 60) {
        $result = 'только что';
    } elseif ($diff < 3600) {
        $minutes = intdiv($diff, 60);
        $result = $minutes . ' '
            . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут')
            . ' назад';
    } elseif ($diff < 86400) {
        $hours = intdiv($diff, 3600);
        $result = $hours . ' '
            . get_noun_plural_form($hours, 'час', 'часа', 'часов')
            . ' назад';
    } else {
        $days = intdiv($diff, 86400);

        $result = $days === 1
            ? 'вчера'
            : $days . ' '
                . get_noun_plural_form($days, 'день', 'дня', 'дней')
                . ' назад';
    }

    return $result;
}
