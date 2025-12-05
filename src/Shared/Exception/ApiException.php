<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Exception;

/**
 * Exceção para erros de comunicação com a API
 */
class ApiException extends NfseException
{
    private ?int $statusCode;
    private ?string $responseBody;

    public function __construct(
        string $message = "Erro na API",
        int $code = 0,
        ?\Throwable $previous = null,
        ?int $statusCode = null,
        ?string $responseBody = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }
}

