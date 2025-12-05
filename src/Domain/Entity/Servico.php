<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

/**
 * Entidade para Serviço prestado
 */
class Servico
{
    public function __construct(
        public readonly string $itemListaServico,
        public readonly string $discriminacao,
        public readonly float $valorServicos,
        public readonly ?string $codigoMunicipio = null,
        public readonly ?string $codigoPais = null,
        public readonly ?float $valorDeducoes = null,
        public readonly ?float $valorPis = null,
        public readonly ?float $valorCofins = null,
        public readonly ?float $valorInss = null,
        public readonly ?float $valorIr = null,
        public readonly ?float $valorCsll = null,
        public readonly ?float $valorIss = null,
        public readonly ?float $aliquota = null,
        public readonly ?float $descontoIncondicionado = null,
        public readonly ?float $descontoCondicionado = null,
        public readonly ?bool $issRetido = null,
        public readonly ?string $responsavelRetencao = null,
        public readonly ?string $codigoCnae = null,
        public readonly ?string $codigoTributacaoMunicipio = null,
        public readonly ?int $exigibilidadeIss = null,
        public readonly ?string $municipioIncidencia = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $valores = $data['valores'] ?? $data['Valores'] ?? [];

        return new self(
            itemListaServico: $data['itemListaServico'] ?? $data['ItemListaServico'] ?? '',
            discriminacao: $data['discriminacao'] ?? $data['Discriminacao'] ?? '',
            valorServicos: (float)($valores['valorServicos'] ?? $valores['ValorServicos'] ?? $data['valorServicos'] ?? 0),
            codigoMunicipio: $data['codigoMunicipio'] ?? $data['CodigoMunicipio'] ?? null,
            codigoPais: $data['codigoPais'] ?? $data['CodigoPais'] ?? null,
            valorDeducoes: isset($valores['valorDeducoes']) ? (float)$valores['valorDeducoes'] : null,
            valorPis: isset($valores['valorPis']) ? (float)$valores['valorPis'] : null,
            valorCofins: isset($valores['valorCofins']) ? (float)$valores['valorCofins'] : null,
            valorInss: isset($valores['valorInss']) ? (float)$valores['valorInss'] : null,
            valorIr: isset($valores['valorIr']) ? (float)$valores['valorIr'] : null,
            valorCsll: isset($valores['valorCsll']) ? (float)$valores['valorCsll'] : null,
            valorIss: isset($valores['valorIss']) ? (float)$valores['valorIss'] : null,
            aliquota: isset($valores['aliquota']) ? (float)$valores['aliquota'] : null,
            descontoIncondicionado: isset($valores['descontoIncondicionado']) ? (float)$valores['descontoIncondicionado'] : null,
            descontoCondicionado: isset($valores['descontoCondicionado']) ? (float)$valores['descontoCondicionado'] : null,
            issRetido: isset($data['issRetido']) ? (bool)$data['issRetido'] : null,
            responsavelRetencao: $data['responsavelRetencao'] ?? null,
            codigoCnae: $data['codigoCnae'] ?? null,
            codigoTributacaoMunicipio: $data['codigoTributacaoMunicipio'] ?? null,
            exigibilidadeIss: isset($data['exigibilidadeIss']) ? (int)$data['exigibilidadeIss'] : null,
            municipioIncidencia: $data['municipioIncidencia'] ?? null
        );
    }

    /**
     * Converte para array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $valores = array_filter([
            'valorServicos' => $this->valorServicos,
            'valorDeducoes' => $this->valorDeducoes,
            'valorPis' => $this->valorPis,
            'valorCofins' => $this->valorCofins,
            'valorInss' => $this->valorInss,
            'valorIr' => $this->valorIr,
            'valorCsll' => $this->valorCsll,
            'valorIss' => $this->valorIss,
            'aliquota' => $this->aliquota,
            'descontoIncondicionado' => $this->descontoIncondicionado,
            'descontoCondicionado' => $this->descontoCondicionado,
        ], fn($value) => $value !== null);

        return array_filter([
            'itemListaServico' => $this->itemListaServico,
            'discriminacao' => $this->discriminacao,
            'valores' => $valores,
            'codigoMunicipio' => $this->codigoMunicipio,
            'codigoPais' => $this->codigoPais,
            'issRetido' => $this->issRetido,
            'responsavelRetencao' => $this->responsavelRetencao,
            'codigoCnae' => $this->codigoCnae,
            'codigoTributacaoMunicipio' => $this->codigoTributacaoMunicipio,
            'exigibilidadeIss' => $this->exigibilidadeIss,
            'municipioIncidencia' => $this->municipioIncidencia,
        ], fn($value) => $value !== null);
    }

    /**
     * Calcula o valor líquido do serviço
     */
    public function getValorLiquido(): float
    {
        $deducoes = ($this->valorDeducoes ?? 0)
            + ($this->descontoIncondicionado ?? 0)
            + ($this->descontoCondicionado ?? 0);

        return $this->valorServicos - $deducoes;
    }

    /**
     * Calcula o total de impostos retidos
     */
    public function getTotalImpostosRetidos(): float
    {
        return ($this->valorPis ?? 0)
            + ($this->valorCofins ?? 0)
            + ($this->valorInss ?? 0)
            + ($this->valorIr ?? 0)
            + ($this->valorCsll ?? 0)
            + ($this->issRetido ? ($this->valorIss ?? 0) : 0);
    }
}

