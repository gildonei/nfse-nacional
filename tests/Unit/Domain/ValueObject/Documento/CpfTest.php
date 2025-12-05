<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Domain\ValueObject\Documento;

use NfseNacional\Domain\ValueObject\Documento\Cpf;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{
    public function testCriarCpfValido(): void
    {
        $cpf = new Cpf('11144477735');

        $this->assertEquals('11144477735', $cpf->getSemFormatacao());
        $this->assertEquals('111.444.777-35', $cpf->getFormatado());
        $this->assertEquals('CPF', $cpf->getTipo());
        $this->assertTrue($cpf->validar());
    }

    public function testCriarCpfComFormatacao(): void
    {
        $cpf = new Cpf('111.444.777-35');

        $this->assertEquals('11144477735', $cpf->getSemFormatacao());
    }

    public function testCpfInvalidoSequenciaRepetida(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('sequência repetida');

        new Cpf('11111111111');
    }

    public function testCpfInvalidoDigitosVerificadores(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('dígitos verificadores');

        new Cpf('12345678901');
    }

    public function testCpfInvalidoTamanho(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('11 dígitos');

        new Cpf('1234567890');
    }

    public function testToString(): void
    {
        $cpf = new Cpf('11144477735');

        $this->assertEquals('11144477735', (string) $cpf);
    }

    public function testFromString(): void
    {
        $cpf = Cpf::fromString('11144477735');

        $this->assertEquals('11144477735', $cpf->getSemFormatacao());
    }

    public function testToArray(): void
    {
        $cpf = new Cpf('11144477735');
        $array = $cpf->toArray();

        $this->assertArrayHasKey('cpf', $array);
        $this->assertArrayHasKey('formatado', $array);
        $this->assertArrayHasKey('tipo', $array);
        $this->assertEquals('11144477735', $array['cpf']);
    }
}

