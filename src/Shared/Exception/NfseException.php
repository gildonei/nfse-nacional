<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Exception;

/**
 * Exceção base para todas as exceções do pacote NFS-e Nacional
 */
class NfseException extends \Exception
{
    public function __construct(
        string $message = "Erro no sistema NFS-e",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

