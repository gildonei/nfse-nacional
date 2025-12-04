<?php

declare(strict_types=1);

namespace NfseNacional\Models;

use NfseNacional\Models\Enums\SituacaoNfse;

/**
 * Modelo para NFS-e (Nota Fiscal de Serviço Eletrônica)
 */
class Nfse
{
    public function __construct(
        public readonly string $chaveAcesso,
        public readonly string $numero,
        public readonly string $serie,
        public readonly string $codigoVerificacao,
        public readonly \DateTimeInterface $dataEmissao,
        public readonly SituacaoNfse $situacao,
        public readonly ?string $xml = null,
        public readonly ?string $danfse = null,
        public readonly ?array $dadosPrestador = null,
        public readonly ?array $dadosTomador = null,
        public readonly ?array $servicos = null,
        public readonly ?array $valores = null,
        public readonly ?array $informacoesAdicionais = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            chaveAcesso: $data['ChaveAcesso'] ?? $data['chaveAcesso'] ?? '',
            numero: $data['Numero'] ?? $data['numero'] ?? '',
            serie: $data['Serie'] ?? $data['serie'] ?? '',
            codigoVerificacao: $data['CodigoVerificacao'] ?? $data['codigoVerificacao'] ?? '',
            dataEmissao: isset($data['DataEmissao'])
                ? new \DateTime($data['DataEmissao'])
                : (isset($data['dataEmissao']) ? new \DateTime($data['dataEmissao']) : new \DateTime()),
            situacao: isset($data['Situacao'])
                ? SituacaoNfse::from($data['Situacao'])
                : (isset($data['situacao']) ? SituacaoNfse::from($data['situacao']) : SituacaoNfse::NORMAL),
            xml: $data['Xml'] ?? $data['xml'] ?? null,
            danfse: $data['Danfse'] ?? $data['danfse'] ?? null,
            dadosPrestador: $data['DadosPrestador'] ?? $data['dadosPrestador'] ?? null,
            dadosTomador: $data['DadosTomador'] ?? $data['dadosTomador'] ?? null,
            servicos: $data['Servicos'] ?? $data['servicos'] ?? null,
            valores: $data['Valores'] ?? $data['valores'] ?? null,
            informacoesAdicionais: $data['InformacoesAdicionais'] ?? $data['informacoesAdicionais'] ?? null
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return array_filter([
            'ChaveAcesso' => $this->chaveAcesso,
            'Numero' => $this->numero,
            'Serie' => $this->serie,
            'CodigoVerificacao' => $this->codigoVerificacao,
            'DataEmissao' => $this->dataEmissao->format('c'),
            'Situacao' => $this->situacao->value,
            'Xml' => $this->xml,
            'Danfse' => $this->danfse,
            'DadosPrestador' => $this->dadosPrestador,
            'DadosTomador' => $this->dadosTomador,
            'Servicos' => $this->servicos,
            'Valores' => $this->valores,
            'InformacoesAdicionais' => $this->informacoesAdicionais,
        ], fn($value) => $value !== null);
    }
}

