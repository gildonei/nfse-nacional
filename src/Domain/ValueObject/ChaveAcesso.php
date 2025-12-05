<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject;

/**
 * Value Object para Chave de Acesso da NFS-e
 */
final class ChaveAcesso
{
    private string $chave;

    public function __construct(string $chave)
    {
        $this->setChave($chave);
    }

    /**
     * Define e valida a chave de acesso
     */
    private function setChave(string $chave): void
    {
        // Remove espaços e caracteres especiais
        $chave = preg_replace('/\s+/', '', $chave);

        // A chave de acesso da NFS-e Nacional tem 50 caracteres
        if (strlen($chave) !== 50) {
            throw new \InvalidArgumentException("Chave de acesso deve ter 50 caracteres. Recebido: " . strlen($chave));
        }

        // Verifica se contém apenas caracteres alfanuméricos
        if (!ctype_alnum($chave)) {
            throw new \InvalidArgumentException("Chave de acesso deve conter apenas caracteres alfanuméricos");
        }

        $this->chave = $chave;
    }

    public static function fromString(string $chave): self
    {
        return new self($chave);
    }

    public function getChave(): string
    {
        return $this->chave;
    }

    public function toString(): string
    {
        return $this->chave;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Extrai o código do município da chave (posição 0-6)
     */
    public function getCodigoMunicipio(): string
    {
        return substr($this->chave, 0, 7);
    }

    /**
     * Verifica igualdade com outra chave
     */
    public function equals(ChaveAcesso $other): bool
    {
        return $this->chave === $other->chave;
    }
}

