<?php

declare(strict_types=1);

/**
 * Prepared data for filling in the form fields when adding a new lot
 *
 * @param array $form_data
 * @param int   $user_id ID of the registered user
 *
 * @return array
 */
function prepareLotData(array $form_data, int $user_id): array
{
    return [
        $form_data['lot_title'],
        $form_data['lot_description'],
        $form_data['lot_start_price'],
        $form_data['lot_expire_date'],
        $form_data['lot_bid_step'],
        (int)$form_data['lot_category_id'],
        $form_data['lot_img'],
        (int) $user_id
    ];
}

/**
 * Prepared data for the fields of the user registration form
 *
 * @param array $form_data
 *
 * @return array
 */
function prepareUserData(array $form_data): array
{
    return [
        $form_data['email'],
        $form_data['name'],
        password_hash($form_data['password'], PASSWORD_DEFAULT),
        $form_data['message']
    ];
}

/**
 * Prepared data for filling in the add rate form field
 *
 * @param int   $user_id
 * @param int   $lot_id
 * @param array $form_data
 *
 * @return array
 */
function prepareBidData(int $user_id, int $lot_id, array $form_data): array
{
    return [
        $form_data['cost'],
        $user_id,
        $lot_id
    ];
}
