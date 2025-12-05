<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Compression;

use NfseNacional\Application\Contract\Compression\CompressionHandlerInterface;

/**
 * Implementação do CompressionHandler usando GZip
 */
class GzipCompressionHandler implements CompressionHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function compressAndEncode(string $data): string
    {
        $compressed = $this->compress($data);
        return base64_encode($compressed);
    }

    /**
     * {@inheritdoc}
     */
    public function decodeAndDecompress(string $data): string
    {
        $decoded = base64_decode($data, true);
        if ($decoded === false) {
            throw new \InvalidArgumentException("Dados Base64 inválidos");
        }

        return $this->decompress($decoded);
    }

    /**
     * {@inheritdoc}
     */
    public function compress(string $data): string
    {
        $compressed = gzencode($data, 9);
        if ($compressed === false) {
            throw new \RuntimeException("Erro ao comprimir dados com GZip");
        }

        return $compressed;
    }

    /**
     * {@inheritdoc}
     */
    public function decompress(string $data): string
    {
        $decompressed = gzdecode($data);
        if ($decompressed === false) {
            throw new \RuntimeException("Erro ao descomprimir dados GZip");
        }

        return $decompressed;
    }
}

