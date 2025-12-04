<?php

declare(strict_types=1);

namespace NfseNacional\Exceptions;

/**
 * Exceção lançada quando há erros na comunicação com a API
 */
class ApiException extends NfseException
{
    protected ?int $statusCode = null;

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(?int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}

