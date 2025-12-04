<?php

declare(strict_types=1);

namespace NfseNacional\Models;

/**
 * Modelo para mensagem de processamento
 */
class MensagemProcessamento
{
    public function __construct(
        public readonly ?string $mensagem = null,
        public readonly ?array $parametros = null,
        public readonly ?string $codigo = null,
        public readonly ?string $descricao = null,
        public readonly ?string $complemento = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            mensagem: $data['Mensagem'] ?? null,
            parametros: $data['Parametros'] ?? null,
            codigo: $data['Codigo'] ?? null,
            descricao: $data['Descricao'] ?? null,
            complemento: $data['Complemento'] ?? null
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return array_filter([
            'Mensagem' => $this->mensagem,
            'Parametros' => $this->parametros,
            'Codigo' => $this->codigo,
            'Descricao' => $this->descricao,
            'Complemento' => $this->complemento,
        ], fn($value) => $value !== null);
    }
}

