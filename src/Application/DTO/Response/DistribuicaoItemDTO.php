<?php

declare(strict_types=1);

namespace NfseNacional\Application\DTO\Response;

use NfseNacional\Shared\Enum\TipoDocumentoRequisicao;
use NfseNacional\Shared\Enum\TipoEvento;

/**
 * DTO para item de distribuição
 */
final class DistribuicaoItemDTO
{
    public function __construct(
        public readonly int $nsu,
        public readonly ?string $chaveAcesso = null,
        public readonly ?TipoDocumentoRequisicao $tipoDocumento = null,
        public readonly ?TipoEvento $tipoEvento = null,
        public readonly ?string $xml = null,
        public readonly ?\DateTimeInterface $dataHoraGeracao = null
    ) {
    }

    /**
     * Cria a partir de um array
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            nsu: (int)($data['NSU'] ?? $data['nsu'] ?? 0),
            chaveAcesso: $data['ChaveAcesso'] ?? $data['chaveAcesso'] ?? null,
            tipoDocumento: isset($data['TipoDocumento'])
                ? TipoDocumentoRequisicao::tryFrom($data['TipoDocumento'])
                : null,
            tipoEvento: isset($data['TipoEvento'])
                ? TipoEvento::tryFrom($data['TipoEvento'])
                : null,
            xml: $data['Xml'] ?? $data['xml'] ?? null,
            dataHoraGeracao: isset($data['DataHoraGeracao'])
                ? new \DateTime($data['DataHoraGeracao'])
                : null
        );
    }
}

