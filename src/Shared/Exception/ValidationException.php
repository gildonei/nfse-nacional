<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Exception;

/**
 * Exceção para erros de validação
 */
class ValidationException extends NfseException
{
    /**
     * @var array<string, string[]>
     */
    private array $errors = [];

    /**
     * @param string $message
     * @param array<string, string[]> $errors
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = "Erro de validação",
        array $errors = [],
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * Retorna os erros de validação
     *
     * @return array<string, string[]>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Adiciona um erro
     */
    public function addError(string $field, string $message): self
    {
        $this->errors[$field][] = $message;
        return $this;
    }

    /**
     * Verifica se há erros
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}

