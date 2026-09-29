<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Compartilhar a NFS-e com a Secretaria de Comércio Exterior (MDIC)
 *
 * Usado em `comExt/mdic`. É uma escolha do emitente, não um dado da
 * operação: decide se a nota é compartilhada com o MDIC.
 *
 * Valores conforme `TSEnvMDIC` do schema da NFS-e Nacional.
 *
 * @package NfseNacional\Domain\Enum
 */
enum EnvioMdic: string
{
    case NaoEnviar = '0';
    case Enviar = '1';

    /**
     * Retorna a descrição
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::NaoEnviar => 'Não enviar para o MDIC',
            self::Enviar => 'Enviar para o MDIC',
        };
    }

    /**
     * Retorna o valor do enum
     *
     * @return string
     */
    public function valor(): string
    {
        return $this->value;
    }

    /**
     * Cria uma instância do enum a partir de um valor string
     *
     * @param string $valor
     * @return self
     * @throws \ValueError
     */
    public static function fromString(string $valor): self
    {
        return self::from($valor);
    }

    /**
     * Tenta criar uma instância do enum a partir de um valor string
     *
     * @param string $valor
     * @return self|null
     */
    public static function tryFromString(string $valor): ?self
    {
        return self::tryFrom($valor);
    }
}
