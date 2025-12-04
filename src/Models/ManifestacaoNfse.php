<?php

declare(strict_types=1);

namespace NfseNacional\Models;

use NfseNacional\Models\Enums\TipoManifestacao;

/**
 * Modelo para manifestação de NFS-e recebida
 */
class ManifestacaoNfse
{
    public function __construct(
        public readonly string $chaveAcesso,
        public readonly TipoManifestacao $tipoManifestacao,
        public readonly ?string $justificativa = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            chaveAcesso: $data['ChaveAcesso'] ?? $data['chaveAcesso'] ?? '',
            tipoManifestacao: TipoManifestacao::from($data['TipoManifestacao'] ?? $data['tipoManifestacao']),
            justificativa: $data['Justificativa'] ?? $data['justificativa'] ?? null
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return array_filter([
            'ChaveAcesso' => $this->chaveAcesso,
            'TipoManifestacao' => $this->tipoManifestacao->value,
            'Justificativa' => $this->justificativa,
        ], fn($value) => $value !== null);
    }
}

