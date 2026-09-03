<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Danfse;

final readonly class DanfseOptions
{
    public function __construct(
        public DanfseStatus $status = DanfseStatus::Normal,
        public bool $incluirCanhoto = false,
        public bool $validarChaveAcesso = true,
        public bool $validarEsquema = false,
        public ?string $diretorioTemporario = null,
        public ?string $diretorioFontes = null,
        public ?string $arquivoArial = null,
        public ?string $arquivoMicrosoftSansSerif = null,
    ) {
    }
}
