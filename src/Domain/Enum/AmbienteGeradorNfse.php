<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Ambiente Gerador NFS-e
 *
 * @package NfseNacional\Domain\Enum
 */
enum AmbienteGeradorNfse: int
{
    case SistemaProprioMunicipio = 1;
    case SefinNacionalNfse = 2;

    /**
     * Retorna a descrição do ambiente gerador
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::SistemaProprioMunicipio => 'Sistema Próprio do Município',
            self::SefinNacionalNfse => 'Sefin Nacional NFS-e',
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

