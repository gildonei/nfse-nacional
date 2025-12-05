<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Domain\Factory;

use NfseNacional\Domain\Factory\DocumentoFactory;
use NfseNacional\Domain\ValueObject\Documento\Cnpj;
use NfseNacional\Domain\ValueObject\Documento\Cpf;
use PHPUnit\Framework\TestCase;

class DocumentoFactoryTest extends TestCase
{
    public function testCriarCpf(): void
    {
        $documento = DocumentoFactory::criar('11144477735');

        $this->assertInstanceOf(Cpf::class, $documento);
        $this->assertEquals('11144477735', $documento->getSemFormatacao());
    }

    public function testCriarCnpj(): void
    {
        $documento = DocumentoFactory::criar('11222333000181');

        $this->assertInstanceOf(Cnpj::class, $documento);
        $this->assertEquals('11222333000181', $documento->getSemFormatacao());
    }

    public function testCriarComFormatacao(): void
    {
        $documento = DocumentoFactory::criar('11.222.333/0001-81');

        $this->assertInstanceOf(Cnpj::class, $documento);
    }

    public function testFromArrayComCpf(): void
    {
        $documento = DocumentoFactory::fromArray(['cpf' => '11144477735']);

        $this->assertInstanceOf(Cpf::class, $documento);
    }

    public function testFromArrayComCnpj(): void
    {
        $documento = DocumentoFactory::fromArray(['cnpj' => '11222333000181']);

        $this->assertInstanceOf(Cnpj::class, $documento);
    }

    public function testFromArrayComDocumento(): void
    {
        $documento = DocumentoFactory::fromArray(['documento' => '11222333000181']);

        $this->assertInstanceOf(Cnpj::class, $documento);
    }

    public function testDocumentoInvalidoTamanho(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Documento inválido');

        DocumentoFactory::criar('12345');
    }

    public function testIsCpf(): void
    {
        $this->assertTrue(DocumentoFactory::isCpf('11144477735'));
        $this->assertFalse(DocumentoFactory::isCpf('11222333000181'));
    }

    public function testIsCnpj(): void
    {
        $this->assertTrue(DocumentoFactory::isCnpj('11222333000181'));
        $this->assertFalse(DocumentoFactory::isCnpj('11144477735'));
    }
}

