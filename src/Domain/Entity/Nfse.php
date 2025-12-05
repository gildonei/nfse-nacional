<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

use NfseNacional\Domain\ValueObject\ChaveAcesso;
use NfseNacional\Shared\Enum\SituacaoNfse;

/**
 * Entidade para NFS-e (Nota Fiscal de Serviço Eletrônica)
 */
class Nfse
{
    public function __construct(
        public readonly ChaveAcesso|string $chaveAcesso,
        public readonly string $numero,
        public readonly string $serie,
        public readonly string $codigoVerificacao,
        public readonly \DateTimeInterface $dataEmissao,
        public readonly SituacaoNfse $situacao,
        public readonly ?Prestador $prestador = null,
        public readonly ?Tomador $tomador = null,
        public readonly ?Servico $servico = null,
        public readonly ?string $xml = null,
        public readonly ?string $danfse = null,
        public readonly ?array $informacoesAdicionais = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $chaveAcesso = $data['ChaveAcesso'] ?? $data['chaveAcesso'] ?? '';

        $prestador = null;
        if (isset($data['DadosPrestador']) && is_array($data['DadosPrestador'])) {
            $prestador = Prestador::fromArray($data['DadosPrestador']);
        } elseif (isset($data['prestador']) && is_array($data['prestador'])) {
            $prestador = Prestador::fromArray($data['prestador']);
        }

        $tomador = null;
        if (isset($data['DadosTomador']) && is_array($data['DadosTomador'])) {
            $tomador = Tomador::fromArray($data['DadosTomador']);
        } elseif (isset($data['tomador']) && is_array($data['tomador'])) {
            $tomador = Tomador::fromArray($data['tomador']);
        }

        $servico = null;
        if (isset($data['Servicos']) && is_array($data['Servicos'])) {
            $servico = Servico::fromArray($data['Servicos']);
        } elseif (isset($data['servico']) && is_array($data['servico'])) {
            $servico = Servico::fromArray($data['servico']);
        }

        return new self(
            chaveAcesso: $chaveAcesso,
            numero: $data['Numero'] ?? $data['numero'] ?? '',
            serie: $data['Serie'] ?? $data['serie'] ?? '',
            codigoVerificacao: $data['CodigoVerificacao'] ?? $data['codigoVerificacao'] ?? '',
            dataEmissao: isset($data['DataEmissao'])
                ? new \DateTime($data['DataEmissao'])
                : (isset($data['dataEmissao']) ? new \DateTime($data['dataEmissao']) : new \DateTime()),
            situacao: isset($data['Situacao'])
                ? SituacaoNfse::from($data['Situacao'])
                : (isset($data['situacao']) ? SituacaoNfse::from($data['situacao']) : SituacaoNfse::NORMAL),
            prestador: $prestador,
            tomador: $tomador,
            servico: $servico,
            xml: $data['Xml'] ?? $data['xml'] ?? null,
            danfse: $data['Danfse'] ?? $data['danfse'] ?? null,
            informacoesAdicionais: $data['InformacoesAdicionais'] ?? $data['informacoesAdicionais'] ?? null
        );
    }

    /**
     * Converte para array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $chaveAcessoStr = $this->chaveAcesso instanceof ChaveAcesso
            ? $this->chaveAcesso->toString()
            : $this->chaveAcesso;

        return array_filter([
            'ChaveAcesso' => $chaveAcessoStr,
            'Numero' => $this->numero,
            'Serie' => $this->serie,
            'CodigoVerificacao' => $this->codigoVerificacao,
            'DataEmissao' => $this->dataEmissao->format('c'),
            'Situacao' => $this->situacao->value,
            'Prestador' => $this->prestador?->toArray(),
            'Tomador' => $this->tomador?->toArray(),
            'Servico' => $this->servico?->toArray(),
            'Xml' => $this->xml,
            'Danfse' => $this->danfse,
            'InformacoesAdicionais' => $this->informacoesAdicionais,
        ], fn($value) => $value !== null);
    }

    /**
     * Retorna a chave de acesso como string
     */
    public function getChaveAcessoString(): string
    {
        return $this->chaveAcesso instanceof ChaveAcesso
            ? $this->chaveAcesso->toString()
            : $this->chaveAcesso;
    }
}

