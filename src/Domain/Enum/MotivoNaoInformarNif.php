<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Motivo de Não Informar NIF
 *
 * @package NfseNacional\Domain\Enum
 */
enum MotivoNaoInformarNif: int
{
    case NaoInformadoNotaOrigem = 0;
    case DispensadoNIF = 1;
    case NaoExigenciaNIF = 2;

    /**
     * Retorna a descrição do motivo de não informar NIF
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::NaoInformadoNotaOrigem => 'Não informado na nota de origem',
            self::DispensadoNIF => 'Dispensado do NIF',
            self::NaoExigenciaNIF => 'Não exigência do NIF',
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

