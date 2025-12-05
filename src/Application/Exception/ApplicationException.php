<?php

declare(strict_types=1);

namespace NfseNacional\Application\Exception;

/**
 * Exceção base para erros da camada de aplicação
 */
class ApplicationException extends \Exception
{
    public function __construct(
        string $message = "Erro na aplicação",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

