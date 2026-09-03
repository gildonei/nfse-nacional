<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Danfse;

use NfseNacional\Domain\Danfse\DanfseException;
use NfseNacional\Domain\Danfse\DanfseOptions;
use NfseNacional\Infrastructure\Danfse\NfseXmlReader;
use PHPUnit\Framework\TestCase;

final class NfseXmlReaderTest extends TestCase
{
    private string $xml;

    protected function setUp(): void
    {
        $this->xml = (string) file_get_contents(dirname(__DIR__, 2) . '/Fixture/Danfse/nfse-completa.xml');
    }

    public function testLeXmlNacionalEMapeiaBlocosDoDanfse(): void
    {
        $data = (new NfseXmlReader())->ler($this->xml);

        self::assertSame('42054075060066100012600000000000000000000000000001', $data->chaveAcesso());
        self::assertSame('123', $data->identificacao['numero']);
        self::assertSame('Empresa Prestadora Ltda', $data->prestador['nome']);
        self::assertSame('Florianópolis', $data->tomador['municipio']);
        self::assertSame('SC', $data->tomador['uf']);
        self::assertSame('Cliente Tomador Ltda', $data->tomador['nome']);
        self::assertSame('1000.00', $data->totais['servico']);
        self::assertSame('11.00', $data->totais['ibsCbs']);
        self::assertTrue($data->homologacao());
    }

    public function testRejeitaXmlComDoctype(): void
    {
        $this->expectException(DanfseException::class);
        (new NfseXmlReader())->ler(str_replace('<NFSe ', '<!DOCTYPE NFSe [<!ENTITY xxe SYSTEM "file:///etc/passwd">]><NFSe ', $this->xml));
    }

    public function testRejeitaDocumentoForaDoNamespaceNacional(): void
    {
        $this->expectException(DanfseException::class);
        (new NfseXmlReader())->ler(str_replace('http://www.sped.fazenda.gov.br/nfse', 'urn:municipal', $this->xml));
    }

    public function testValidacaoXsdRejeitaVersaoSemEsquemaEmpacotado(): void
    {
        $this->expectException(DanfseException::class);
        $this->expectExceptionMessage('Não há esquema XSD empacotado');
        $xml = preg_replace('/versao="1\.01"/', 'versao="9.99"', $this->xml, 1);
        (new NfseXmlReader())->ler((string) $xml, new DanfseOptions(validarEsquema: true));
    }
}
