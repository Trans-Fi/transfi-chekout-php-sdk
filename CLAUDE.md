# transfi-chekout-php-sdk

## Overview
Official PHP SDK for integrating the TransFi Payment API, allowing PHP applications to create payment invoices and handle the TransFi checkout flow. Note: repo name has a typo ("chekout" instead of "checkout").

## Category
SDK/Library

## Tech Stack
- PHP (Composer)
- PSR-compliant HTTP client

## Key Directories
- `src/`: SDK source — `TransFiPaymentAPI.php`, `PaymentInvoiceRequest.php`, `Individual.php`, `ProductDetails.php`, `TransFiError.php`
- `example/`: Usage examples
- `composer.json`: Package manifest (`transfi/payment-sdk`)

## Related Services
- `transfi-checkout-server`: The backend API this SDK calls
- `transfi-checkout-sdk`: Multi-language SDK monorepo (also contains a PHP SDK)

## Development
```bash
composer require transfi/payment-sdk   # install as dependency
composer install                       # install dev dependencies
```

## Notes
- Published to Packagist as `transfi/payment-sdk`
- Repo name contains a typo: "chekout" (missing 'c') — do not rename without updating all references
- See `example/` directory for integration usage patterns
