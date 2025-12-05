<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Exception;

/**
 * Exceção para erros relacionados ao certificado digital
 */
class CertificateException extends NfseException
{
    public function __construct(
        string $message = "Erro no certificado digital",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

