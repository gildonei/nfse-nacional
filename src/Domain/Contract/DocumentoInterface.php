<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Contract;

/**
 * Interface para documentos (CPF/CNPJ)
 *
 * @package NfseNacional\Domain\Contract
 */
interface DocumentoInterface
{
    /**
     * Retorna o documento sem formatação
     *
     * @return string
     */
    public function obterNumero(): string;

    /**
     * Retorna o documento formatado
     *
     * @return string
     */
    public function obterFormatado(): string;

    /**
     * Retorna o documento como string
     *
     * @return string
     */
    public function __toString(): string;
}

