<?php

declare(strict_types=1);

namespace NfseNacional\Entity;

/**
 * Entidade para E-mail com validação
 */
class Email
{
    private string $email;

    public function __construct(string $email)
    {
        $this->setEmail($email);
    }

    /**
     * Define e valida o e-mail
     */
    private function setEmail(string $email): void
    {
        // Remove espaços em branco
        $email = trim($email);

        // Valida o formato do e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("E-mail inválido: {$email}");
        }

        // Valida comprimento máximo (conforme RFC 5321)
        if (strlen($email) > 254) {
            throw new \InvalidArgumentException("E-mail excede o comprimento máximo de 254 caracteres");
        }

        // Valida comprimento da parte local (antes do @)
        $parts = explode('@', $email);
        if (strlen($parts[0]) > 64) {
            throw new \InvalidArgumentException("Parte local do e-mail excede 64 caracteres");
        }

        $this->email = strtolower($email);
    }

    /**
     * Cria uma instância a partir de uma string
     */
    public static function fromString(string $email): self
    {
        return new self($email);
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        $email = $data['email'] ?? $data['Email'] ?? $data['EMAIL'] ?? '';

        if (empty($email)) {
            throw new \InvalidArgumentException("E-mail é obrigatório");
        }

        return new self($email);
    }

    /**
     * Retorna o e-mail
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Retorna o domínio do e-mail
     */
    public function getDominio(): string
    {
        $parts = explode('@', $this->email);
        return $parts[1] ?? '';
    }

    /**
     * Retorna a parte local do e-mail (antes do @)
     */
    public function getParteLocal(): string
    {
        $parts = explode('@', $this->email);
        return $parts[0] ?? '';
    }

    /**
     * Retorna o e-mail como string
     */
    public function toString(): string
    {
        return $this->email;
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
        ];
    }

    /**
     * Representação em string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}

