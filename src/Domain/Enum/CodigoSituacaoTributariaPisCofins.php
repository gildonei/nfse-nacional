<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Código de Situação Tributária do PIS/COFINS
 *
 * @package NfseNacional\Domain\Enum
 */
enum CodigoSituacaoTributariaPisCofins: string
{
    case Nenhum = '00';
    case OperacaoTributavelAliquotaBasica = '01';
    case OperacaoTributavelAliquotaDiferenciada = '02';
    case OperacaoTributavelAliquotaPorUnidadeMedida = '03';
    case OperacaoTributavelMonofasicaRevendaAliquotaZero = '04';
    case OperacaoTributavelSubstituicaoTributaria = '05';
    case OperacaoTributavelAliquotaZero = '06';
    case OperacaoTributavelContribuicao = '07';
    case OperacaoSemIncidenciaContribuicao = '08';
    case OperacaoComSuspensaoContribuicao = '09';

    /**
     * Retorna a descrição do código de situação tributária do PIS/COFINS
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::Nenhum => 'Nenhum',
            self::OperacaoTributavelAliquotaBasica => 'Operação Tributável com Alíquota Básica',
            self::OperacaoTributavelAliquotaDiferenciada => 'Operação Tributável com Alíquota Diferenciada',
            self::OperacaoTributavelAliquotaPorUnidadeMedida => 'Operação Tributável com Alíquota por Unidade de Medida de Produto',
            self::OperacaoTributavelMonofasicaRevendaAliquotaZero => 'Operação Tributável monofásica - Revenda a Alíquota Zero',
            self::OperacaoTributavelSubstituicaoTributaria => 'Operação Tributável por Substituição Tributária',
            self::OperacaoTributavelAliquotaZero => 'Operação Tributável a Alíquota Zero',
            self::OperacaoTributavelContribuicao => 'Operação Tributável da Contribuição',
            self::OperacaoSemIncidenciaContribuicao => 'Operação sem Incidência da Contribuição',
            self::OperacaoComSuspensaoContribuicao => 'Operação com Suspensão da Contribuição',
        };
    }

    /**
     * Retorna o valor (código) do enum
     *
     * @return string
     */
    public function valor(): string
    {
        return $this->value;
    }

    /**
     * Cria uma instância do enum a partir de um valor string
     *
     * @param string $valor
     * @return self
     * @throws \ValueError
     */
    public static function fromString(string $valor): self
    {
        return self::from($valor);
    }

    /**
     * Tenta criar uma instância do enum a partir de um valor string
     *
     * @param string $valor
     * @return self|null
     */
    public static function tryFromString(string $valor): ?self
    {
        return self::tryFrom($valor);
    }
}
