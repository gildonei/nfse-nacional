<?php

declare(strict_types=1);

namespace NfseNacional\Models;

use NfseNacional\Models\Enums\StatusProcessamentoDistribuicao;
use NfseNacional\Models\Enums\TipoAmbiente;

/**
 * Modelo para resposta de lote de distribuição NSU
 */
class LoteDistribuicaoNSUResponse
{
    /**
     * @param StatusProcessamentoDistribuicao $statusProcessamento
     * @param DistribuicaoNSU[]|null $loteDFe
     * @param MensagemProcessamento[]|null $alertas
     * @param MensagemProcessamento[]|null $erros
     * @param TipoAmbiente $tipoAmbiente
     * @param string|null $versaoAplicativo
     * @param \DateTimeInterface $dataHoraProcessamento
     */
    public function __construct(
        public readonly StatusProcessamentoDistribuicao $statusProcessamento,
        public readonly TipoAmbiente $tipoAmbiente,
        public readonly \DateTimeInterface $dataHoraProcessamento,
        public readonly ?array $loteDFe = null,
        public readonly ?array $alertas = null,
        public readonly ?array $erros = null,
        public readonly ?string $versaoAplicativo = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        $loteDFe = null;
        if (isset($data['LoteDFe']) && is_array($data['LoteDFe'])) {
            $loteDFe = array_map(
                fn($item) => DistribuicaoNSU::fromArray($item),
                $data['LoteDFe']
            );
        }

        $alertas = null;
        if (isset($data['Alertas']) && is_array($data['Alertas'])) {
            $alertas = array_map(
                fn($item) => MensagemProcessamento::fromArray($item),
                $data['Alertas']
            );
        }

        $erros = null;
        if (isset($data['Erros']) && is_array($data['Erros'])) {
            $erros = array_map(
                fn($item) => MensagemProcessamento::fromArray($item),
                $data['Erros']
            );
        }

        return new self(
            statusProcessamento: StatusProcessamentoDistribuicao::from($data['StatusProcessamento']),
            tipoAmbiente: TipoAmbiente::from($data['TipoAmbiente']),
            dataHoraProcessamento: new \DateTime($data['DataHoraProcessamento']),
            loteDFe: $loteDFe,
            alertas: $alertas,
            erros: $erros,
            versaoAplicativo: $data['VersaoAplicativo'] ?? null
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return [
            'StatusProcessamento' => $this->statusProcessamento->value,
            'LoteDFe' => $this->loteDFe ? array_map(
                fn($item) => $item->toArray(),
                $this->loteDFe
            ) : null,
            'Alertas' => $this->alertas ? array_map(
                fn($item) => $item->toArray(),
                $this->alertas
            ) : null,
            'Erros' => $this->erros ? array_map(
                fn($item) => $item->toArray(),
                $this->erros
            ) : null,
            'TipoAmbiente' => $this->tipoAmbiente->value,
            'VersaoAplicativo' => $this->versaoAplicativo,
            'DataHoraProcessamento' => $this->dataHoraProcessamento->format('c'),
        ];
    }
}

