<?php

declare(strict_types=1);

const ADD_LOT_FORM_KEY = 'add-lot';
const SIGN_UP_FORM_KEY = 'sign-up';
const ADD_BID_FORM_KEY = 'add-bid';
const LOGIN_FORM_KEY = 'login';

const VALIDATION_RULES = [
    ADD_LOT_FORM_KEY => [
        'lot_category_id' => [
            'required',
            'category'
        ],
        'lot_title' => [
            'required',
            'string:min=5&max=255'
        ],
        'lot_description' => [
            'required',
            'string:min=5'
        ],
        'lot_start_price' => [
            'required',
            'int:min=1'
        ],
        'lot_bid_step' => [
            'required',
            'int:min=1'
        ],
        'lot_expire_date' => [
            'required',
            'date:format=Y-m-d&gt=today'
        ]
    ],
    SIGN_UP_FORM_KEY => [
        'email' => [
            'required',
            'string:min=4&max=128',
            'email',
            'unique_email'
        ],
        'name' => [
            'required',
            'string:min=4&max=128',
            'name:filter'
        ],
        'password' => [
            'required',
            'string:min=8&max=255',
            'password'
        ],
        'message' => [
            'required',
            'string:min=5'
        ]
    ],
    LOGIN_FORM_KEY => [
        'email' => [
            'required',
            'email',
            'user_exists'
        ],
        'password' => [
            'required',
            'password_match'
        ]
    ],
    ADD_BID_FORM_KEY => [
        'cost' => [
            'required',
            'int'
        ]
    ]
];
