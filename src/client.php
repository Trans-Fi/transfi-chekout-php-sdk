<?php

namespace TransFi;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

class TransFiPaymentAPI
{
    private const API_BASE_URL = 'https://checkout-server.transfi.com';

    private string     $publicKey;
    private string     $secretKey;
    private HttpClient $httpClient;

    /**
     * @param SDKConfig|array $config
     */
    public function __construct($config)
    {
        if ($config instanceof SDKConfig) {
            $this->publicKey = $config->publicKey;
            $this->secretKey = $config->secretKey;
        } elseif (is_array($config)) {
            $this->publicKey = $config['publicKey'] ?? '';
            $this->secretKey = $config['secretKey'] ?? '';
        } else {
            throw new \InvalidArgumentException(
                'Config must be an SDKConfig instance or an associative array.'
            );
        }

        $this->httpClient = new HttpClient([
            'base_uri' => self::API_BASE_URL,
        ]);
    }

    /**
     * Create a payment invoice and return the payment URL.
     *
     * @param PaymentInvoiceRequest|array $paymentData
     * @return string
     * @throws TransFiError
     */
    public function createPaymentInvoice($paymentData): string
{
    $path = '/checkout/payment-link/invoice';

    $body       = $paymentData instanceof PaymentInvoiceRequest
        ? $paymentData->toArray()
        : $paymentData;

    $bodyString = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); // <-- fix
    $headers    = $this->generateHeaders('POST', $path, $bodyString);

    try {
        $response = $this->httpClient->post($path, [
            'headers' => $headers,
            'body'    => $bodyString,
        ]);

        $decoded = json_decode($response->getBody()->getContents(), true);
        return $decoded['data']['paymentUrl'] ?? '';
    } catch (RequestException $e) {
        if ($e->hasResponse()) {
            $statusCode   = $e->getResponse()->getStatusCode();
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            throw new TransFiError("API Error: {$statusCode}", $statusCode, $responseBody);
        }
        throw new TransFiError($e->getMessage() ?: 'Unknown network error');
    }
}

    /**
     * Generate HMAC-signed authentication headers.
     */
    private function generateHeaders(string $method, string $path, string $bodyString): array
    {
        $timestamp = (string) (int) (microtime(true) * 1000);

        $message   = strtoupper($method) . $path . $timestamp . $bodyString;
        $signature = hash_hmac('sha256', $message, $this->secretKey);

        return [
            'x-api-key'     => $this->publicKey,
            'x-timestamp'   => $timestamp,
            'x-signature'   => $signature,
            'Content-Type'  => 'application/json',
            'X-Api-Version' => 'v1',
        ];
    }
}