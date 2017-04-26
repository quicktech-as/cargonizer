<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Defining application environment
    |--------------------------------------------------------------------------
    |
    | Define all options necessary to communicate with Cargonizer
    |
    */
    'endpoint'                           => env('CARGONIZER_ENDPOINT', 'http://sandbox.cargonizer.no'),
    'sender'                             => env('CARGONIZER_SENDER', 0),
    'transport_agreement'                => env('CARGONIZER_TRANSPORT_AGREEMENT', 0),
    'transport_agreement_product'        => env('CARGONIZER_TRANSPORT_AGREEMENT_PRODUCT', null),
    'transport_agreement_product_return' => env('CARGONIZER_TRANSPORT_AGREEMENT_PRODUCT_RETURN', null),
    'printer_id'                         => env('CARGONIZER_PRINTER_ID', 0),
    'transport_transfer'                 => env('CARGONIZER_TRANSPORT_TRANSFER', null),
    'transport_type'                     => env('CARGONIZER_TRANSPORT_TYPE', null),
    'pickup_point'                       => env('CARGONIZER_PICKUP_POINT', false),
    'max_weight'                         => env('CARGONIZER_MAX_WEIGHT', 0),

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | Any request to the API must be passed the secret key
    |
    */
    'credentials' => [
        'key' => env('CARGONIZER_SECRET_KEY', null)
    ],
];