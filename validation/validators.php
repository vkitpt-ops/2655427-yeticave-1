<?php

declare(strict_types=1);

/**
 * Checks whether the field value is empty
 *
 * @param string $value
 *
 * @return string|null Error message or null
 */
function checkingEmptyField(string $value): ?string
{
    if ($value === '') {
        return 'Заполните поле';
    }
    return null;
}

/**
 * Checks whether the value is positive and greater than zero
 *
 * @param string $value
 *
 * @return string|null Error message or null
 */
function validatePositiveInt(string $value, array $params): ?string
{
    if (!preg_match('/^[1-9]\d*$/', $value)) {
        return "Введите целое значение больше нуля";
    }

    $min = (int)($params['min'] ?? 0);

    if ((int)$value < $min) {
        return "Минимальное значение: $min";
    }
    return null;
}

/**
 * Validates text length according to character limits
 *
 * @param string $value Text value to validate
 * @param array $params Validation parameters
 *
 * @return string|null Validation error message or null if validation passes
 */
function validateText(string $value, array $params): ?string
{
    $max_characters = (int)($params['max'] ?? 0);
    $min_characters = (int)($params['min'] ?? 0);
    $string_length = mb_strlen($value);

    if ($min_characters !== 0 && $string_length < $min_characters) {
        return "Минимальная длина поля $min_characters символов";
    }

    if ($max_characters !== 0 && $string_length > $max_characters) {
        return "Максимальная длина поля $max_characters символов";
    }
    return null;
}

/**
 * Checks the correctness of the date and its compliance with the form requirements
 *
 * @param string $date Date in the format "YYYY-MM-DD"
 * @param array $params Validation parameters
 *
 * @return string|null Error message or null
 */
function validateDate(string $date, array $params): ?string
{
    $format = $params['format'] ?? 'Y-m-d';
    $day = $params['gt'] ?? null;

    $dateTime = date_create_from_format($format, $date);

    if (
        $dateTime === false ||
        $dateTime->format($format) !== $date
    ) {
        return "Введите дату в формате «ГГГГ-ММ-ДД»";
    }

    if ($day === 'today') {
        $today = new DateTimeImmutable('tomorrow');

        if ($dateTime <= $today) {
            return "Дата должна быть больше текущей минимум на 1 день";
        }
    }
    return null;
}

/**
 * Checks whether the category exists in the list of allowed categories
 *
 * @param array $allowed_list List of valid categories for category validation
 * @param string $category
 *
 * @return string|null Error message or null
 */
function validateCategory(string $category, array $allowed_list): ?string
{
    $id = (int)$category;

    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
    return null;
}

/**
 * Verifies the uniqueness of the email among existing users
 *
 * @param array|null $users_by_email User information by email
 *
 * @return string|null Error message or null
 */
function validateUniqueEmail(?array $users_by_email): ?string
{
    if ($users_by_email) {
        return "Указанный email уже используется другим пользователем";
    }
    return null;
}

/**
 * Checks the correctness of the email format
 *
 * @param string $email
 *
 * @return string|null Error message or null
 */
function validateEmailFormat(string $email): ?string
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Некорректный email";
    }
    return null;
}

/**
 * Only letters and numbers are allowed in the password
 *
 * @param string $password
 *
 * @return string|null Error message or null
 */
function validatePassword(string $password): ?string
{
    if (!preg_match("/^[a-zA-Z\d]+$/", $password)) {
        return "Разрешены только буквы и цифры";
    }

    if (!preg_match("/[a-z]/", $password)) {
        return "Пароль должен содержать хотя бы одну строчную букву";
    }

    if (!preg_match("/[A-Z]/", $password)) {
        return "Пароль должен содержать хотя бы одну заглавную букву";
    }

    if (!preg_match("/\d/", $password)) {
        return "Пароль должен содержать хотя бы одну цифру";
    }
    return null;
}

/**
 * User name validation
 *
 * @param string $name
 *
 * @return string|null Error message or null
 */
function validateName(string $name): ?string
{
    if (!preg_match("/^[\p{L}\- ]+$/u", $name)) {
        return "Некорректное имя пользователя";
    }
    return null;
}

/**
 * Checks whether a user with the specified email exists
 *
 * @param array|null $user User information by email
 *
 * @return string|null Error message or null
 */
function validateUserExists(?array $user): ?string
{
    if ($user === null) {
        return "Пользователя с таким email не существует";
    }
    return null;
}

/**
 * Verifies whether the entered password matches the user's password hash
 *
 * @param array|null $user User information by email
 * @param array|null $form_data The form's data array
 *
 * @return string|null Error message or null
 */
function validateUserPassword(?array $user, array $form_data): ?string
{
    if (isset($user)) {
        if (!password_verify($form_data[PASSWORD_FIELD], $user['password_hash'])) {
            return "Указан неверный пароль";
        }
    }
    return null;
}
