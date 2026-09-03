<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Danfse;

final readonly class DanfseData
{
    public function __construct(
        public array $identificacao,
        public array $prestador,
        public array $tomador,
        public array $destinatario,
        public array $intermediario,
        public array $servico,
        public array $issqn,
        public array $tributosFederais,
        public array $ibsCbs,
        public array $totais,
        public array $informacoesComplementares,
    ) {
    }

    public function chaveAcesso(): string
    {
        return $this->identificacao['chaveAcesso'];
    }

    public function homologacao(): bool
    {
        return $this->identificacao['tipoAmbienteCodigo'] === '2';
    }
}
