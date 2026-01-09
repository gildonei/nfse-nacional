<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Processo de Emissão
 *
 * @package NfseNacional\Domain\Enum
 */
enum ProcessoEmissao: int
{
    case AplicativoContribuinte = 1;
    case AplicativoFiscoWeb = 2;
    case AplicativoFiscoApp = 3;

    /**
     * Retorna a descrição do processo de emissão
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::AplicativoContribuinte => 'Emissão com aplicativo do contribuinte (via Web Service)',
            self::AplicativoFiscoWeb => 'Emissão com aplicativo disponibilizado pelo fisco (Web)',
            self::AplicativoFiscoApp => 'Emissão com aplicativo disponibilizado pelo fisco (App)',
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

