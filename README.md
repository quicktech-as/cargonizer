# Quicktech\Cargonizer (Laravel 5 Package)

Quicktech\Cargonizer is a succinct and flexible way to use Cargonizer services in **Laravel 5** applications.

## Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
    - [Shipping Cost Estimation](#shipping-cost-estimation)
- [License](#license)
- [Contribution guidelines](#contribution-guidelines)
- [Additional information](#additional-information)

## Installation

1) In order to install Laravel 5, just add the following to your composer.json. Then run `composer update`:

```json
"quicktech/cargonizer": "^1.0"
```

2) Open your `config/app.php` and add the following to the `providers` array:

```php
Quicktech\Cargonizer\CargonizerServiceProvider::class,
```

3) In the same `config/app.php` and add the following to the `aliases ` array: 

```php
'Cargonizer'   => Quicktech\Cargonizer\Facades\Cargonizer::class,
```

4) Run the command below to publish the package config file `config/cargonizer.php`:

```shell
php artisan vendor:publish
```

5) Open your `.env` file setup your Cargonizer credentials:

```php
CARGONIZER_ENDPOINT = 'http://sandbox.cargonizer.no'
CARGONIZER_SENDER = 'your send id'
CARGONIZER_SECRET_KEY = 'your secret key'
```

## Configuration

Set the other properties necessaries in the `config/cargonizer.php`.
These values will be used by Cargonizer to get other resouces.

## Usage

### Shipping Cost Estimation
To estimate the cost of shipping, you can use this resource:

```php
$params = [
    'consignment' => [
        '@attributes' => [
            'transport_agreement' => '1'
        ],
        'product' => 'tg_dpd_innland',
        'parts' => [
            'consignee' => [
                'country' => 'NO',
                'postcode' => '1337'
            ]
        ],
        'itens' => [
            'item' => [
                '@attributes' => [
                    'type' => 'package',
                    'amount' => '1',
                    'weight' => '12'
                ]
            ]
        ]
    ]
];

$consignmentCosts = Cargonizer::consignmentCosts($params);
```

The method above will return the following response:

```php
[
    'estimated_cost' => 495,
    'gross_mount'    => 495,
    'net_amount'     => 495
]
```

You can see more option to consignment parameters [here](https://logistra.no/cargonizer-api-documentation/12-developers/19-api-shipping-calculation.html).

## License

Quicktech\Cargonizer is free software distributed under the terms of the MIT license.

## Contribution guidelines

Please report any issue you find in the issues page.  
Pull requests are welcome.
