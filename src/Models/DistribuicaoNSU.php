<?php

declare(strict_types=1);

namespace NfseNacional\Models;

use NfseNacional\Models\Enums\TipoDocumentoRequisicao;
use NfseNacional\Models\Enums\TipoEvento;

/**
 * Modelo para distribuição NSU
 */
class DistribuicaoNSU
{
    public function __construct(
        public readonly ?int $nsu = null,
        public readonly ?string $chaveAcesso = null,
        public readonly ?TipoDocumentoRequisicao $tipoDocumento = null,
        public readonly ?TipoEvento $tipoEvento = null,
        public readonly ?string $arquivoXml = null,
        public readonly ?\DateTimeInterface $dataHoraGeracao = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            nsu: isset($data['NSU']) ? (int) $data['NSU'] : null,
            chaveAcesso: $data['ChaveAcesso'] ?? null,
            tipoDocumento: isset($data['TipoDocumento'])
                ? TipoDocumentoRequisicao::from($data['TipoDocumento'])
                : null,
            tipoEvento: isset($data['TipoEvento'])
                ? TipoEvento::from($data['TipoEvento'])
                : null,
            arquivoXml: $data['ArquivoXml'] ?? null,
            dataHoraGeracao: isset($data['DataHoraGeracao'])
                ? new \DateTime($data['DataHoraGeracao'])
                : null
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return array_filter([
            'NSU' => $this->nsu,
            'ChaveAcesso' => $this->chaveAcesso,
            'TipoDocumento' => $this->tipoDocumento?->value,
            'TipoEvento' => $this->tipoEvento?->value,
            'ArquivoXml' => $this->arquivoXml,
            'DataHoraGeracao' => $this->dataHoraGeracao?->format('c'),
        ], fn($value) => $value !== null);
    }
}

