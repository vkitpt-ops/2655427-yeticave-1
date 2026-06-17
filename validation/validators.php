<?php

declare(strict_types=1);

/**
 * Checks whether a value is empty.
 *
 * @param string $value Value to check
 *
 * @return string|null Error message or null if valid
 */
function checkingEmptyField(string $value): ?string
{
    return $value === '' ? 'Заполните поле' : null;
}

/**
 * Validates that a value is a positive integer.
 *
 * Checks that the value contains only digits
 * and is greater than or equal to the specified minimum value.
 *
 * @param string $value Value to validate
 * @param array $params Validation parameters
 *
 * @return string|null Error message or null if valid
 */
function validatePositiveInt(string $value, array $params): ?string
{
    $error_message = null;

    if (!preg_match('/^[1-9]\d*$/', $value)) {
        $error_message = "Введите целое значение больше нуля";
    } else {
        $min = (int)($params['min'] ?? 0);

        if (intval($value) < $min) {
            $error_message = "Минимальное значение: $min";
        }
    }

    return $error_message;
}

/**
 * Validates text length limits.
 *
 * Checks whether the text length is within
 * the specified minimum and maximum values.
 *
 * @param string $value Text value to validate
 * @param array $params Validation parameters
 *
 * @return string|null Error message or null if valid
 */
function validateText(string $value, array $params): ?string
{
    $max_characters = (int)($params['max'] ?? 0);
    $min_characters = (int)($params['min'] ?? 0);
    $string_length = mb_strlen($value);
    $error_message = null;

    if ($min_characters !== 0 && $string_length < $min_characters) {
        $error_message = "Минимальная длина поля $min_characters символов";
    } elseif ($max_characters !== 0 && $string_length > $max_characters) {
        $error_message = "Максимальная длина поля $max_characters символов";
    }
    return $error_message;
}

/**
 * Validates a date value.
 *
 * Checks date format and optional date constraints.
 *
 * @param string $date Date value to validate
 * @param array $params Validation parameters
 *
 * @return string|null Error message or null if valid
 */
function validateDate(string $date, array $params): ?string
{
    $format = $params['format'] ?? 'Y-m-d';
    $day = $params['gt'] ?? null;
    $error_message = null;

    $dateTime = date_create_from_format($format, $date);

    if (
        $dateTime === false ||
        $dateTime->format($format) !== $date
    ) {
        $error_message = "Введите дату в формате «ГГГГ-ММ-ДД»";
    } elseif ($day === 'today') {
        $today = new DateTimeImmutable('tomorrow');

        if ($dateTime <= $today) {
            $error_message = "Дата должна быть больше текущей минимум на 1 день";
        }
    }
    return $error_message;
}

/**
 * Validates that a category exists.
 *
 * Checks whether the category ID is included
 * in the list of allowed categories.
 *
 * @param string $category Category ID
 * @param array $allowed_list Allowed category IDs
 *
 * @return string|null Error message or null if valid
 */
function validateCategory(string $category, array $allowed_list): ?string
{
    $id = (int) $category;

    return !in_array($id, $allowed_list) ? "Указана несуществующая категория" : null;
}

/**
 * Checks whether an email address is already used.
 *
 * @param array|null $users_by_email Existing user data
 *
 * @return string|null Error message or null if valid
 */
function validateUniqueEmail(?array $users_by_email): ?string
{
    return $users_by_email ? "Указанный email уже используется другим пользователем" : null;
}

/**
 * Validates email format.
 *
 * @param string $email Email address to validate
 *
 * @return string|null Error message or null if valid
 */
function validateEmailFormat(string $email): ?string
{
    return !filter_var($email, FILTER_VALIDATE_EMAIL) ? "Некорректный email" : null;
}

/**
 * Validates user password requirements.
 *
 * Checks password characters and required
 * lowercase letters, uppercase letters and digits.
 *
 * @param string $password Password value
 *
 * @return string|null Error message or null if valid
 */
function validatePassword(string $password): ?string
{
    $error_message = null;

    if (!preg_match("/^[a-zA-Z\d]+$/", $password)) {
        $error_message = "Разрешены только буквы и цифры";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $error_message = "Пароль должен содержать хотя бы одну строчную букву";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $error_message = "Пароль должен содержать хотя бы одну заглавную букву";
    } elseif (!preg_match("/\d/", $password)) {
        $error_message = "Пароль должен содержать хотя бы одну цифру";
    }

    return $error_message;
}

/**
 * Validates user name format.
 *
 * Allows only letters, spaces and hyphens.
 *
 * @param string $name User name
 *
 * @return string|null Error message or null if valid
 */
function validateName(string $name): ?string
{
    return !preg_match("/^[\p{L}\- ]+$/u", $name) ? "Некорректное имя пользователя" : null;
}

/**
 * Checks whether a user exists.
 *
 * @param array|null $user User data
 *
 * @return string|null Error message or null if valid
 */
function validateUserExists(?array $user): ?string
{
    return $user === null ? "Пользователя с таким email не существует" : null;
}

/**
 * Checks whether the entered password matches the stored password hash.
 *
 * @param array|null $user User data
 * @param array $form_data Submitted form data
 *
 * @return string|null Error message or null if valid
 */
function validateUserPassword(?array $user, array $form_data): ?string
{
    return isset($user)
        && !password_verify($form_data[PASSWORD_FIELD], $user['password_hash'])
        ? "Указан неверный пароль"
        : null;
}
