<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Tipo de Emitente
 *
 * @package NfseNacional\Domain\Enum
 */
enum TipoEmitente: int
{
    case Prestador = 1;
    case Tomador = 2;
    case Intermediario = 3;

    /**
     * Retorna a descrição do tipo de emitente
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::Prestador => 'Prestador',
            self::Tomador => 'Tomador',
            self::Intermediario => 'Intermediário',
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

