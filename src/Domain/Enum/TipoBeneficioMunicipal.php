<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Tipo de Benefício Municipal
 *
 * @package NfseNacional\Domain\Enum
 */
enum TipoBeneficioMunicipal: int
{
    case Isencao = 1;
    case ReducaoBCPercentual = 2;
    case ReducaoBCValor = 3;
    case AliquotaDiferenciada = 4;

    /**
     * Retorna a descrição do tipo de benefício municipal
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::Isencao => 'Isenção',
            self::ReducaoBCPercentual => 'Redução da BC em \'ppBM\' %',
            self::ReducaoBCValor => 'Redução da BC em R$ \'vInfoBM\' ',
            self::AliquotaDiferenciada => 'Alíquota Diferenciada de \'aliqDifBM\' %',
        };
    }

    /**
     * Retorna o valor numérico do enum
     *
     * @return int
     */
    public function valor(): int
    {
        return $this->value;
    }

    /**
     * Cria uma instância do enum a partir de um valor inteiro
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
     * Tenta criar uma instância do enum a partir de um valor inteiro
     *
     * @param int $valor
     * @return self|null
     */
    public static function tryFromInt(int $valor): ?self
    {
        return self::tryFrom($valor);
    }
}

