<?php

declare(strict_types=1);

namespace NfseNacional\Models;

use NfseNacional\Models\Enums\TipoEvento;

/**
 * Modelo para pedido de registro de evento (cancelamento, substituição, etc.)
 */
class PedidoRegistroEvento
{
    public function __construct(
        public readonly string $chaveAcesso,
        public readonly TipoEvento $tipoEvento,
        public readonly string $justificativa,
        public readonly ?string $dpsSubstituicao = null,
        public readonly ?array $documentosComprobatorios = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            chaveAcesso: $data['ChaveAcesso'] ?? $data['chaveAcesso'] ?? '',
            tipoEvento: TipoEvento::from($data['TipoEvento'] ?? $data['tipoEvento']),
            justificativa: $data['Justificativa'] ?? $data['justificativa'] ?? '',
            dpsSubstituicao: $data['DpsSubstituicao'] ?? $data['dpsSubstituicao'] ?? null,
            documentosComprobatorios: $data['DocumentosComprobatorios'] ?? $data['documentosComprobatorios'] ?? null
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return array_filter([
            'ChaveAcesso' => $this->chaveAcesso,
            'TipoEvento' => $this->tipoEvento->value,
            'Justificativa' => $this->justificativa,
            'DpsSubstituicao' => $this->dpsSubstituicao,
            'DocumentosComprobatorios' => $this->documentosComprobatorios,
        ], fn($value) => $value !== null);
    }
}

