<?php

namespace TransFi;

use Exception;

class TransFiError extends Exception
{
    private ?int $statusCode;
    private $responseData;

    public function __construct(string $message, ?int $statusCode = null, $responseData = null)
    {
        parent::__construct($message, $statusCode ?? 0);

        $this->statusCode   = $statusCode;
        $this->responseData = $responseData;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getResponseData()
    {
        return $this->responseData;
    }
}