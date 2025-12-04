<?php

declare(strict_types=1);

namespace NfseNacional\Utils;

use NfseNacional\Exceptions\NfseException;

/**
 * Classe utilitária para compressão GZip e codificação base64
 */
class CompressionHandler
{
    /**
     * Comprime uma string usando GZip e codifica em base64
     *
     * @param string $data
     * @return string
     * @throws NfseException
     */
    public static function compressAndEncode(string $data): string
    {
        $compressed = gzencode($data, 9);
        if ($compressed === false) {
            throw new NfseException("Erro ao comprimir dados com GZip");
        }

        return base64_encode($compressed);
    }

    /**
     * Decodifica base64 e descomprime GZip
     *
     * @param string $encodedData
     * @return string
     * @throws NfseException
     */
    public static function decodeAndDecompress(string $encodedData): string
    {
        $decoded = base64_decode($encodedData, true);
        if ($decoded === false) {
            throw new NfseException("Erro ao decodificar base64");
        }

        $decompressed = gzdecode($decoded);
        if ($decompressed === false) {
            throw new NfseException("Erro ao descomprimir dados GZip");
        }

        return $decompressed;
    }

    /**
     * Codifica uma string em base64
     *
     * @param string $data
     * @return string
     */
    public static function encodeBase64(string $data): string
    {
        return base64_encode($data);
    }

    /**
     * Decodifica uma string de base64
     *
     * @param string $encodedData
     * @return string
     * @throws NfseException
     */
    public static function decodeBase64(string $encodedData): string
    {
        $decoded = base64_decode($encodedData, true);
        if ($decoded === false) {
            throw new NfseException("Erro ao decodificar base64");
        }

        return $decoded;
    }

    /**
     * Comprime uma string usando GZip
     *
     * @param string $data
     * @param int $level Nível de compressão (0-9)
     * @return string
     * @throws NfseException
     */
    public static function compress(string $data, int $level = 9): string
    {
        $compressed = gzencode($data, $level);
        if ($compressed === false) {
            throw new NfseException("Erro ao comprimir dados com GZip");
        }

        return $compressed;
    }

    /**
     * Descomprime uma string GZip
     *
     * @param string $compressedData
     * @return string
     * @throws NfseException
     */
    public static function decompress(string $compressedData): string
    {
        $decompressed = gzdecode($compressedData);
        if ($decompressed === false) {
            throw new NfseException("Erro ao descomprimir dados GZip");
        }

        return $decompressed;
    }
}

