<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Modo de Prestação
 *
 * @package NfseNacional\Domain\Enum
 */
enum ModoPrestacao: int
{
    case Desconhecido = 0;
    case Transfronteirico = 1;
    case ConsumoNoBrasil = 2;
    case PresencaComercialExterior = 3;
    case MovimentoTemporarioPessoasFisicas = 4;

    /**
     * Retorna a descrição do modo de prestação
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::Desconhecido => 'Desconhecido (tipo não informado na nota de origem)',
            self::Transfronteirico => 'Transfronteiriço',
            self::ConsumoNoBrasil => 'Consumo no Brasil',
            self::PresencaComercialExterior => 'Presença Comercial no Exterior',
            self::MovimentoTemporarioPessoasFisicas => 'Movimento Temporário de Pessoas Físicas',
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

