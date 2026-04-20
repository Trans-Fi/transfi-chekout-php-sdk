<?php

namespace TransFi;

class SDKConfig
{
    public string $publicKey;
    public string $secretKey;

    public function __construct(string $publicKey, string $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }
}

class Address
{
    public ?string $city       = null;
    public ?string $state      = null;
    public ?string $street     = null;
    public ?string $postalCode = null;

    public function toArray(): array
    {
        return array_filter([
            'city'       => $this->city,
            'state'      => $this->state,
            'street'     => $this->street,
            'postalCode' => $this->postalCode,
        ], fn($v) => $v !== null);
    }
}

class Individual
{
    public string   $firstName;
    public string   $lastName;
    public string   $email;
    public ?string  $phone     = null;
    public ?string  $phoneCode = null;
    public ?string  $country   = null;
    public ?Address $address   = null;

    public function __construct(string $firstName, string $lastName, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
    }

    public function toArray(): array
    {
        $data = [
            'firstName' => $this->firstName,
            'lastName'  => $this->lastName,
            'email'     => $this->email,
        ];

        if ($this->phone     !== null) $data['phone']     = $this->phone;
        if ($this->phoneCode !== null) $data['phoneCode'] = $this->phoneCode;
        if ($this->country   !== null) $data['country']   = $this->country;
        if ($this->address   !== null) $data['address']   = $this->address->toArray();

        return $data;
    }
}

class ProductDetails
{
    public string  $name;
    public ?string $description = null;
    public ?string $imageUrl    = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function toArray(): array
    {
        $data = ['name' => $this->name];

        if ($this->description !== null) $data['description'] = $this->description;
        if ($this->imageUrl    !== null) $data['imageUrl']    = $this->imageUrl;

        return $data;
    }
}

class PaymentInvoiceRequest
{
    public string          $paymentLinkId;
    public string          $amount;
    public string          $currency;
    public string          $successRedirectUrl;
    public string          $failureRedirectUrl;
    public ?ProductDetails $productDetails  = null;
    public ?Individual     $individual      = null;
    public ?string         $customerOrderId = null;

    public function __construct(
        string $paymentLinkId,
        string $amount,
        string $currency,
        string $successRedirectUrl,
        string $failureRedirectUrl
    ) {
        $this->paymentLinkId      = $paymentLinkId;
        $this->amount             = $amount;
        $this->currency           = $currency;
        $this->successRedirectUrl = $successRedirectUrl;
        $this->failureRedirectUrl = $failureRedirectUrl;
    }

    public function toArray(): array
    {
        $data = [
            'paymentLinkId'      => $this->paymentLinkId,
            'amount'             => $this->amount,
            'currency'           => $this->currency,
            'successRedirectUrl' => $this->successRedirectUrl,
            'failureRedirectUrl' => $this->failureRedirectUrl,
        ];

        if ($this->productDetails  !== null) $data['productDetails']  = $this->productDetails->toArray();
        if ($this->individual      !== null) $data['individual']      = $this->individual->toArray();
        if ($this->customerOrderId !== null) $data['customerOrderId'] = $this->customerOrderId;

        return $data;
    }
}