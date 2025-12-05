<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Factory;

use NfseNacional\Domain\ValueObject\Documento\Cnpj;
use NfseNacional\Domain\ValueObject\Documento\Cpf;
use NfseNacional\Domain\ValueObject\Documento\DocumentoInterface;

/**
 * Factory para criar instâncias de DocumentoInterface baseado no número de caracteres
 */
class DocumentoFactory
{
    /**
     * Cria uma instância de DocumentoInterface baseado no número de caracteres
     * - 11 caracteres: CPF
     * - 14 caracteres: CNPJ
     *
     * @param string $documento Documento com ou sem formatação
     * @return DocumentoInterface
     * @throws \InvalidArgumentException
     */
    public static function criar(string $documento): DocumentoInterface
    {
        $numeros = preg_replace('/\D/', '', $documento);
        $tamanho = strlen($numeros);

        if ($tamanho === 11) {
            return new Cpf($numeros);
        }

        if ($tamanho === 14) {
            return new Cnpj($numeros);
        }

        throw new \InvalidArgumentException(
            "Documento inválido. Deve ter 11 dígitos (CPF) ou 14 dígitos (CNPJ). Recebido: {$tamanho} dígitos"
        );
    }

    /**
     * Cria a partir de um array
     *
     * @param array<string, mixed> $data
     * @return DocumentoInterface
     * @throws \InvalidArgumentException
     */
    public static function fromArray(array $data): DocumentoInterface
    {
        // Tenta CPF primeiro
        if (isset($data['cpf']) || isset($data['Cpf']) || isset($data['CPF'])) {
            $cpf = $data['cpf'] ?? $data['Cpf'] ?? $data['CPF'];
            return new Cpf($cpf);
        }

        // Tenta CNPJ
        if (isset($data['cnpj']) || isset($data['Cnpj']) || isset($data['CNPJ'])) {
            $cnpj = $data['cnpj'] ?? $data['Cnpj'] ?? $data['CNPJ'];
            return new Cnpj($cnpj);
        }

        // Tenta documento genérico
        if (isset($data['documento']) || isset($data['Documento'])) {
            $documento = $data['documento'] ?? $data['Documento'];
            return self::criar($documento);
        }

        throw new \InvalidArgumentException("Nenhum documento válido encontrado no array");
    }

    /**
     * Verifica se o documento é um CPF válido
     */
    public static function isCpf(string $documento): bool
    {
        $numeros = preg_replace('/\D/', '', $documento);
        return strlen($numeros) === 11;
    }

    /**
     * Verifica se o documento é um CNPJ válido
     */
    public static function isCnpj(string $documento): bool
    {
        $numeros = preg_replace('/\D/', '', $documento);
        return strlen($numeros) === 14;
    }
}

