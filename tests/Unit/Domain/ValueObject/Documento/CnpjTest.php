<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Domain\ValueObject\Documento;

use NfseNacional\Domain\ValueObject\Documento\Cnpj;
use PHPUnit\Framework\TestCase;

class CnpjTest extends TestCase
{
    public function testCriarCnpjValido(): void
    {
        $cnpj = new Cnpj('11222333000181');

        $this->assertEquals('11222333000181', $cnpj->getSemFormatacao());
        $this->assertEquals('11.222.333/0001-81', $cnpj->getFormatado());
        $this->assertEquals('CNPJ', $cnpj->getTipo());
        $this->assertTrue($cnpj->validar());
    }

    public function testCriarCnpjComFormatacao(): void
    {
        $cnpj = new Cnpj('11.222.333/0001-81');

        $this->assertEquals('11222333000181', $cnpj->getSemFormatacao());
    }

    public function testCnpjInvalidoSequenciaRepetida(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('sequência repetida');

        new Cnpj('11111111111111');
    }

    public function testCnpjInvalidoDigitosVerificadores(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('dígitos verificadores');

        new Cnpj('12345678000190');
    }

    public function testCnpjInvalidoTamanho(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('14 dígitos');

        new Cnpj('1234567800019');
    }

    public function testToString(): void
    {
        $cnpj = new Cnpj('11222333000181');

        $this->assertEquals('11222333000181', (string) $cnpj);
    }

    public function testFromString(): void
    {
        $cnpj = Cnpj::fromString('11222333000181');

        $this->assertEquals('11222333000181', $cnpj->getSemFormatacao());
    }

    public function testToArray(): void
    {
        $cnpj = new Cnpj('11222333000181');
        $array = $cnpj->toArray();

        $this->assertArrayHasKey('cnpj', $array);
        $this->assertArrayHasKey('formatado', $array);
        $this->assertArrayHasKey('tipo', $array);
        $this->assertEquals('11222333000181', $array['cnpj']);
    }
}

