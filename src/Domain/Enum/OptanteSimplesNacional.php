<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Optante Simples Nacional
 *
 * @package NfseNacional\Domain\Enum
 */
enum OptanteSimplesNacional: int
{
    case NaoOptante = 1;
    case OptanteMEI = 2;
    case OptanteMEEPP = 3;

    /**
     * Retorna a descrição do optante simples nacional
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::NaoOptante => 'Não Optante',
            self::OptanteMEI => 'Optante - Microempreendedor Individual (MEI)',
            self::OptanteMEEPP => 'Optante - Microempresa ou Empresa de Pequeno Porte (ME/EPP)',
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

