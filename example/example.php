<?php

require_once __DIR__ . '/../vendor/autoload.php';

use TransFi\TransFiPaymentAPI;
use TransFi\TransFiError;
use TransFi\PaymentInvoiceRequest;
use TransFi\Individual;
use TransFi\ProductDetails;

$PUBLIC_KEY = 'YOUR_PUBLIC_KEY';
$SECRET_KEY = 'YOUR_SECRET_KEY';

$api = new TransFiPaymentAPI([
    'publicKey' => $PUBLIC_KEY,
    'secretKey' => $SECRET_KEY,
]);

$product              = new ProductDetails('Test Premium Product');
$product->description = 'Sample product from SDK test';

$individual            = new Individual('John', 'Doe', 'test@example.com');
$individual->phone     = '1234567890';
$individual->phoneCode = '+1';
$individual->country   = 'US';

$paymentData                  = new PaymentInvoiceRequest(
    paymentLinkId:      'TEST_LINK_ID',
    amount:             '100',
    currency:           'USD',
    successRedirectUrl: 'https://example.com/success',
    failureRedirectUrl: 'https://example.com/failure'
);
$paymentData->productDetails  = $product;
$paymentData->individual      = $individual;
$paymentData->customerOrderId = 'order-' . time();

echo "Attempting to create invoice...\n";

try {
    $paymentUrl = $api->createPaymentInvoice($paymentData);
    echo "Success: " . $paymentUrl . "\n";
} catch (TransFiError $e) {
    echo "TransFiError caught as expected for mock credentials.\n";
    echo "Message:     " . $e->getMessage()    . "\n";
    echo "Status Code: " . $e->getStatusCode() . "\n";
    echo "Data:        " . json_encode($e->getResponseData(), JSON_PRETTY_PRINT) . "\n";
} catch (\Exception $e) {
    echo "Unexpected Error: " . $e->getMessage() . "\n";
}