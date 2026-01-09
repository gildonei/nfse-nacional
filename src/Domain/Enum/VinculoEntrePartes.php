<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Vínculo Entre Partes
 *
 * @package NfseNacional\Domain\Enum
 */
enum VinculoEntrePartes: int
{
    case SemVinculo = 0;
    case Controlada = 1;
    case Controladora = 2;
    case Coligada = 3;
    case Matriz = 4;
    case FilialOuSucursal = 5;
    case OutroVinculo = 6;

    /**
     * Retorna a descrição do vínculo entre partes
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::SemVinculo => 'Sem vínculo com o tomador/ Prestador',
            self::Controlada => 'Controlada',
            self::Controladora => 'Controladora',
            self::Coligada => 'Coligada',
            self::Matriz => 'Matriz',
            self::FilialOuSucursal => 'Filial ou sucursal',
            self::OutroVinculo => 'Outro vínculo',
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

