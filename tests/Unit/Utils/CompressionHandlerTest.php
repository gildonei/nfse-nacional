<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Utils;

use NfseNacional\Utils\CompressionHandler;
use PHPUnit\Framework\TestCase;

class CompressionHandlerTest extends TestCase
{
    public function testCompressAndDecompress(): void
    {
        $original = 'Teste de compressão';
        $compressed = CompressionHandler::compress($original);
        $decompressed = CompressionHandler::decompress($compressed);
        $this->assertEquals($original, $decompressed);
    }

    public function testEncodeAndDecodeBase64(): void
    {
        $original = 'Teste de codificação';
        $encoded = CompressionHandler::encodeBase64($original);
        $decoded = CompressionHandler::decodeBase64($encoded);
        $this->assertEquals($original, $decoded);
    }

    public function testCompressAndEncode(): void
    {
        $original = 'Teste completo';
        $encoded = CompressionHandler::compressAndEncode($original);
        $decoded = CompressionHandler::decodeAndDecompress($encoded);
        $this->assertEquals($original, $decoded);
    }
}

