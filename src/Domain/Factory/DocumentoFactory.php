<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Factory;

use InvalidArgumentException;
use NfseNacional\Domain\Contract\DocumentoInterface;
use NfseNacional\Domain\ValueObject\Cpf;
use NfseNacional\Domain\ValueObject\Cnpj;

/**
 * Factory para criação de documentos (CPF/CNPJ)
 *
 * @package NfseNacional\Domain\Factory
 */
class DocumentoFactory
{
    /**
     * Cria uma instância de CPF ou CNPJ baseado na quantidade de dígitos
     *
     * @param string $documento
     * @return DocumentoInterface
     * @throws InvalidArgumentException
     */
    public static function criar(string $documento): DocumentoInterface
    {
        // Remove formatação
        $documentoLimpo = preg_replace('/[^0-9]/', '', $documento);

        if (empty($documentoLimpo)) {
            throw new InvalidArgumentException('Documento está vazio!');
        }

        $quantidadeDigitos = strlen($documentoLimpo);

        return match ($quantidadeDigitos) {
            11 => new Cpf($documento),
            14 => new Cnpj($documento),
            default => throw new InvalidArgumentException(
                sprintf(
                    'Documento inválido! CPF deve ter 11 dígitos e CNPJ deve ter 14 dígitos. ' .
                    'Documento informado possui %d dígito(s).',
                    $quantidadeDigitos
                )
            ),
        };
    }

    /**
     * Tenta criar uma instância de CPF ou CNPJ
     *
     * @param string $documento
     * @return DocumentoInterface|null Retorna null se o documento for inválido
     */
    public static function tentarCriar(string $documento): ?DocumentoInterface
    {
        try {
            return self::criar($documento);
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Verifica se o documento é um CPF
     *
     * @param string $documento
     * @return bool
     */
    public static function ehCpf(string $documento): bool
    {
        $documentoLimpo = preg_replace('/[^0-9]/', '', $documento);
        return strlen($documentoLimpo) === 11;
    }

    /**
     * Verifica se o documento é um CNPJ
     *
     * @param string $documento
     * @return bool
     */
    public static function ehCnpj(string $documento): bool
    {
        $documentoLimpo = preg_replace('/[^0-9]/', '', $documento);
        return strlen($documentoLimpo) === 14;
    }
}

