<?php

declare(strict_types=1);

/**
 * Validates form fields according to the specified validation rules
 * Fills the errors array with validation messages
 *
 * @param array $rules Validation rules for form fields
 * @param array $form_data The form's data array
 * @param array $errors Array of errors
 * @param array|null $allowed_list List of valid categories for category validation
 *
 * @return void
 */
function validateFormData(array $rules, array $form_data, array &$errors, ?array $allowed_list = []): void
{

    foreach ($rules as $field => $validators) {

        $value = $form_data[$field] ?? '';

        foreach ($validators as $rule) {

            $error = validateByRule($rule, $value, $allowed_list, $form_data);

            if ($error !== null) {
                $errors[$field] = $error;
                break;
            }
        }
    }
}

/**
 * Converts a validator parameter string into an associative array
 *
 * @param string $params_string
 *
 * @return array
 */
function parseValidatorParams(string $params_string): array
{
    $params = [];

    $pairs = explode(VALIDATOR_PARAMS_SEPARATOR, $params_string);

    foreach ($pairs as $pair) {
        $parts = explode(VALIDATOR_PARAM_VALUE_SEPARATOR, $pair);

        $key = $parts[0] ?? null;
        $value = $parts[1] ?? null;

        if ($key !== null && $value !== null) {
            $params[$key] = $value;
        }
    }

    return $params;
}

/**
 * Parses a validation rule, extracts validator parameters
 * Executes the corresponding validation function
 *
 * @param string $rule Validation rules for form fields
 * @param string $value Data to validate
 *
 * @return string|null
 */
function validateByRule(string $rule, string $value, ?array $allowed_list, array $form_data): ?string
{

    $parts = explode(VALIDATOR_SEPARATOR, $rule);

    $validator = $parts[0];

    $params_string = $parts[1] ?? '';
    $params = [];

    if ($params_string !== '') {
        $params = parseValidatorParams($params_string);
    }

    $result = null;

    switch ($validator) {
        case 'required':
            $result = checkingEmptyField($value);
            break;

        case 'int':
            $result = validatePositiveInt($value, $params);
            break;

        case 'string':
            $result = validateText($value, $params);
            break;

        case 'date':
            $result = validateDate($value, $params);
            break;

        case 'category':
            $result = validateCategory($value, $allowed_list);
            break;

        case 'unique_email':
            $result = validateUniqueEmail($allowed_list);
            break;

        case 'email':
            $result = validateEmailFormat($value);
            break;

        case 'password':
            $result = validatePassword($value);
            break;

        case 'name':
            $result = validateName($value);
            break;

        case 'password_match':
            $result = validateUserPassword($allowed_list, $form_data);
            break;

        case 'user_exists':
            $result = validateUserExists($allowed_list);
            break;
    }
    return $result;
}
