<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Tipo de Emissão NFS-e
 *
 * @package NfseNacional\Domain\Enum
 */
enum TipoEmissaoNfse: int
{
    case EmissaoNormal = 1;
    case EmissaoOriginalLeiauteProprio = 2;

    /**
     * Retorna a descrição do tipo de emissão
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::EmissaoNormal => 'Emissão normal no modelo da NFS-e Nacional',
            self::EmissaoOriginalLeiauteProprio => 'Emissão original em leiaute próprio do município com transcrição para o modelo da NFS-e Nacional',
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

