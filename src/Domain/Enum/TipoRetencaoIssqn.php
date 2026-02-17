<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Tipo de Retenção ISSQN
 *
 * @package NfseNacional\Domain\Enum
 */
enum TipoRetencaoIssqn: int
{
    case NaoRetido = 1;
    case RetidoPeloTomador = 2;
    case RetidoPeloIntermediario = 3;

    /**
     * Retorna a descrição do tipo de retenção ISSQN
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::NaoRetido => 'Não Retido',
            self::RetidoPeloTomador => 'Retido pelo Tomador',
            self::RetidoPeloIntermediario => 'Retido pelo Intermediário',
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
