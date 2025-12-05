<?php

declare(strict_types=1);

namespace NfseNacional\Application\UseCase\Emissao;

use NfseNacional\Domain\Entity\Dps;

/**
 * Request DTO para emissão de NFS-e
 */
final class EmitirNfseRequest
{
    public function __construct(
        public readonly Dps $dps
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
            dps: Dps::fromArray($data)
        );
    }
}

