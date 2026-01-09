<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Situações Possíveis da NFS-e
 *
 * @package NfseNacional\Domain\Enum
 */
enum SituacoesPossiveisNfse: int
{
    case NfseGerada = 100;
    case NfseSubstituicaoGerada = 101;
    case NfseDecisaoJudicial = 102;
    case NfseAvulsa = 103;

    /**
     * Retorna a descrição da situação da NFS-e
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::NfseGerada => 'NFS-e Gerada',
            self::NfseSubstituicaoGerada => 'NFS-e de Substituição Gerada',
            self::NfseDecisaoJudicial => 'NFS-e de Decisão Judicial',
            self::NfseAvulsa => 'NFS-e Avulsa',
        };
    }

    /**
     * Retorna o valor do enum
     *
     * @return int
     */
    public function valor(): int
    {
        return $this->value;
    }

    /**
     * Cria uma instância do enum a partir de um valor int
     *
     * @param int $valor
     * @return self
     * @throws \ValueError
     */
    public static function fromInt(int $valor): self
    {
        return self::from($valor);
    }

    /**
     * Tenta criar uma instância do enum a partir de um valor int
     *
     * @param int $valor
     * @return self|null
     */
    public static function tryFromInt(int $valor): ?self
    {
        return self::tryFrom($valor);
    }
}

