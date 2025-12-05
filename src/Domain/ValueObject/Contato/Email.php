<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject\Contato;

/**
 * Value Object para E-mail com validação
 */
final class Email
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
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("E-mail inválido: {$email}");
        }

        if (strlen($email) > 254) {
            throw new \InvalidArgumentException("E-mail excede o comprimento máximo de 254 caracteres");
        }

        $parts = explode('@', $email);
        if (strlen($parts[0]) > 64) {
            throw new \InvalidArgumentException("Parte local do e-mail excede 64 caracteres");
        }

        $this->email = strtolower($email);
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public static function fromArray(array $data): self
    {
        $email = $data['email'] ?? $data['Email'] ?? $data['EMAIL'] ?? '';

        if (empty($email)) {
            throw new \InvalidArgumentException("E-mail é obrigatório");
        }

        return new self($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDominio(): string
    {
        $parts = explode('@', $this->email);
        return $parts[1] ?? '';
    }

    public function getParteLocal(): string
    {
        $parts = explode('@', $this->email);
        return $parts[0] ?? '';
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
        ];
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

