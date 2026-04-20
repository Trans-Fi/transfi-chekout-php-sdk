# php-sdk

PHP SDK for the [TransFi](https://checkout-dashboard.transfi.com) Payment API.

## Installation

```bash
composer require transfi/payment-sdk
```

## Quick start

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use TransFi\TransFiPaymentAPI;
use TransFi\TransFiError;
use TransFi\PaymentInvoiceRequest;
use TransFi\Individual;
use TransFi\ProductDetails;

$api = new TransFiPaymentAPI([
    'publicKey' => 'YOUR_PUBLIC_KEY',
    'secretKey' => 'YOUR_SECRET_KEY',
]);

$product = new ProductDetails('Premium Plan');
$product->description = 'Monthly subscription';

$individual = new Individual('John', 'Doe', 'john@example.com');
$individual->phone     = '1234567890';
$individual->phoneCode = '+1';
$individual->country   = 'US';

$paymentData = new PaymentInvoiceRequest(
    'LINK_ID',
    '100',
    'USD',
    'https://example.com/success',
    'https://example.com/failure'
);
$paymentData->productDetails  = $product;
$paymentData->individual      = $individual;
$paymentData->customerOrderId = 'order-001';

try {
    $paymentUrl = $api->createPaymentInvoice($paymentData);
    echo 'Checkout URL: ' . $paymentUrl . PHP_EOL;
} catch (TransFiError $e) {
    echo 'API Error   : ' . $e->getMessage() . PHP_EOL;
    echo 'Status Code : ' . $e->getStatusCode() . PHP_EOL;
    echo 'Response    : ' . json_encode($e->getResponseData(), JSON_PRETTY_PRINT) . PHP_EOL;
}
```

You can also pass a plain array instead of `PaymentInvoiceRequest` (keys match the JSON API: camelCase):

```php
$paymentUrl = $api->createPaymentInvoice([
    'paymentLinkId'      => 'LINK_ID',
    'amount'             => '100',
    'currency'           => 'USD',
    'productDetails'     => [
        'name'        => 'Premium Plan',
        'description' => 'Monthly subscription',
    ],
    'individual'         => [
        'firstName' => 'John',
        'lastName'  => 'Doe',
        'email'     => 'john@example.com',
        'phone'     => '1234567890',
        'phoneCode' => '+1',
        'country'   => 'US',
    ],
    'successRedirectUrl' => 'https://example.com/success',
    'failureRedirectUrl' => 'https://example.com/failure',
    'customerOrderId'    => 'order-001',
]);
```

## Types reference

| PHP class | Properties |
|---|---|
| `PaymentInvoiceRequest` | `paymentLinkId`, `amount`, `currency`, `successRedirectUrl`, `failureRedirectUrl`, optional `productDetails`, `individual`, `customerOrderId` |
| `ProductDetails` | `name`, optional `description`, `imageUrl` |
| `Individual` | `firstName`, `lastName`, `email`, optional `phone`, `phoneCode`, `country`, `address` |
| `Address` | optional `city`, `state`, `street`, `postalCode` |
