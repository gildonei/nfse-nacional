<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Exception;

/**
 * Exceção base para erros de domínio
 */
class DomainException extends \Exception
{
    public function __construct(
        string $message = "Erro de domínio",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

