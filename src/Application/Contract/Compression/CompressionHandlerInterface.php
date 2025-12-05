<?php

declare(strict_types=1);

namespace NfseNacional\Application\Contract\Compression;

/**
 * Interface para compressão e codificação de dados
 */
interface CompressionHandlerInterface
{
    /**
     * Comprime uma string usando GZip e codifica em Base64
     */
    public function compressAndEncode(string $data): string;

    /**
     * Decodifica Base64 e descomprime GZip
     */
    public function decodeAndDecompress(string $data): string;

    /**
     * Apenas comprime com GZip
     */
    public function compress(string $data): string;

    /**
     * Apenas descomprime GZip
     */
    public function decompress(string $data): string;
}

