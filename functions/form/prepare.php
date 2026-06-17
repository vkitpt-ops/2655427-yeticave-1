<?php

declare(strict_types=1);

/**
 * Prepares data for creating a new lot.
 *
 * Collects and formats lot information from the creation form:
 * title, description, start price, expiration date, bid step,
 * category, image and owner ID.
 *
 * @param array $form_data Lot creation form data
 * @param int $user_id ID of the user who creates the lot
 *
 * @return array Prepared lot data
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
 * Prepares user data for registration.
 *
 * Collects and formats user information from the registration form:
 * email, username, hashed password and contact information.
 *
 * @param array $form_data Registration form data
 *
 * @return array Prepared user data
 */
function prepareUserData(array $form_data): array
{
    return [
        $form_data['user_email'],
        $form_data['user_name'],
        password_hash($form_data['user_password'], PASSWORD_DEFAULT),
        $form_data['user_contact_info']
    ];
}

/**
 * Prepares bid data for inserting into the database.
 *
 * Creates an array of values required to add a new bid:
 * bid amount, user ID and lot ID.
 *
 * @param int $user_id The ID of the user who places the bid
 * @param int $lot_id The ID of the lot being bid on
 * @param array $form_data Form data containing bid information
 *
 * @return array Prepared bid data
 */
function prepareBidData(int $user_id, int $lot_id, array $form_data): array
{
    return [
        $form_data['bid_cost'],
        $user_id,
        $lot_id
    ];
}
