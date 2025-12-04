<?php

declare(strict_types=1);

namespace NfseNacional\Models;

/**
 * Modelo para rascunho de DPS
 */
class RascunhoDPS
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $nome = null,
        public readonly ?array $dadosDPS = null,
        public readonly ?\DateTimeInterface $dataCriacao = null,
        public readonly ?\DateTimeInterface $dataAtualizacao = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['Id'] ?? $data['id'] ?? null,
            nome: $data['Nome'] ?? $data['nome'] ?? null,
            dadosDPS: $data['DadosDPS'] ?? $data['dadosDPS'] ?? null,
            dataCriacao: isset($data['DataCriacao'])
                ? new \DateTime($data['DataCriacao'])
                : (isset($data['dataCriacao']) ? new \DateTime($data['dataCriacao']) : null),
            dataAtualizacao: isset($data['DataAtualizacao'])
                ? new \DateTime($data['DataAtualizacao'])
                : (isset($data['dataAtualizacao']) ? new \DateTime($data['dataAtualizacao']) : null)
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return array_filter([
            'Id' => $this->id,
            'Nome' => $this->nome,
            'DadosDPS' => $this->dadosDPS,
            'DataCriacao' => $this->dataCriacao?->format('c'),
            'DataAtualizacao' => $this->dataAtualizacao?->format('c'),
        ], fn($value) => $value !== null);
    }
}

