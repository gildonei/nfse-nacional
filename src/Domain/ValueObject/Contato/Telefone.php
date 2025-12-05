<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject\Contato;

/**
 * Value Object para Telefone
 */
final class Telefone
{
    private string $ddd;
    private string $numero;
    private ?string $tipo = null;

    public function __construct(
        string $ddd,
        string $numero,
        ?string $tipo = null
    ) {
        $this->setDdd($ddd);
        $this->setNumero($numero);
        $this->tipo = $tipo;
    }

    /**
     * Define o DDD
     */
    private function setDdd(string $ddd): void
    {
        $ddd = preg_replace('/\D/', '', $ddd);

        if (strlen($ddd) !== 2) {
            throw new \InvalidArgumentException("DDD deve conter exatamente 2 dígitos");
        }

        $this->ddd = $ddd;
    }

    /**
     * Define o número
     */
    private function setNumero(string $numero): void
    {
        $numero = preg_replace('/\D/', '', $numero);

        if (strlen($numero) < 8 || strlen($numero) > 9) {
            throw new \InvalidArgumentException("Número de telefone deve conter 8 ou 9 dígitos");
        }

        $this->numero = $numero;
    }

    /**
     * Cria uma instância a partir de um número completo
     */
    public static function fromString(string $phoneString, ?string $tipo = null): self
    {
        $cleaned = preg_replace('/\D/', '', $phoneString);

        if (strlen($cleaned) < 10 || strlen($cleaned) > 11) {
            throw new \InvalidArgumentException("Número de telefone inválido. Deve conter 10 ou 11 dígitos");
        }

        $ddd = substr($cleaned, 0, 2);
        $numero = substr($cleaned, 2);

        return new self($ddd, $numero, $tipo);
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        if (isset($data['telefone']) || isset($data['Telefone'])) {
            $telefone = $data['telefone'] ?? $data['Telefone'];
            $tipo = $data['tipo'] ?? $data['Tipo'] ?? null;
            return self::fromString($telefone, $tipo);
        }

        $ddd = $data['ddd'] ?? $data['Ddd'] ?? $data['DDD'] ?? '';
        $numero = $data['numero'] ?? $data['Numero'] ?? '';
        $tipo = $data['tipo'] ?? $data['Tipo'] ?? null;

        if (empty($ddd) || empty($numero)) {
            throw new \InvalidArgumentException("DDD e número são obrigatórios");
        }

        return new self($ddd, $numero, $tipo);
    }

    public function getDdd(): string
    {
        return $this->ddd;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function getNumeroCompleto(): string
    {
        return $this->ddd . $this->numero;
    }

    public function getNumeroFormatado(): string
    {
        if (strlen($this->numero) === 9) {
            return sprintf(
                '(%s) %s-%s',
                $this->ddd,
                substr($this->numero, 0, 5),
                substr($this->numero, 5)
            );
        }

        return sprintf(
            '(%s) %s-%s',
            $this->ddd,
            substr($this->numero, 0, 4),
            substr($this->numero, 4)
        );
    }

    public function toString(): string
    {
        return $this->getNumeroCompleto();
    }

    public function toArray(): array
    {
        return array_filter([
            'ddd' => $this->ddd,
            'numero' => $this->numero,
            'telefone' => $this->getNumeroCompleto(),
            'tipo' => $this->tipo,
        ], fn($value) => $value !== null);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

