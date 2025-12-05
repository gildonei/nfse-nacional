<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Exception;

/**
 * Exceção para erros de validação de domínio
 */
class ValidationException extends DomainException
{
    /**
     * @var array<string, string[]> Erros de validação por campo
     */
    private array $errors = [];

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
     * Adiciona um erro de validação
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

