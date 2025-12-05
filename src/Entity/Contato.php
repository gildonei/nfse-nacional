<?php

declare(strict_types=1);

namespace NfseNacional\Entity;

/**
 * Entidade para Contato
 */
class Contato
{
    public function __construct(
        public readonly ?Telefone $telefone = null,
        public readonly ?Email $email = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        $telefone = null;

        // Aceita Telefone, string ou array
        if (isset($data['telefone']) || isset($data['Telefone'])) {
            $telefoneData = $data['telefone'] ?? $data['Telefone'];

            if ($telefoneData instanceof Telefone) {
                $telefone = $telefoneData;
            } elseif (is_string($telefoneData)) {
                $telefone = Telefone::fromString($telefoneData);
            } elseif (is_array($telefoneData)) {
                $telefone = Telefone::fromArray($telefoneData);
            }
        }

        $email = null;

        // Aceita Email ou string
        if (isset($data['email']) || isset($data['Email'])) {
            $emailData = $data['email'] ?? $data['Email'];

            if ($emailData instanceof Email) {
                $email = $emailData;
            } elseif (is_string($emailData) && !empty($emailData)) {
                $email = Email::fromString($emailData);
            }
        }

        return new self(
            telefone: $telefone,
            email: $email
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return array_filter([
            'telefone' => $this->telefone?->toString(),
            'email' => $this->email?->toString(),
        ], fn($value) => $value !== null);
    }
}

