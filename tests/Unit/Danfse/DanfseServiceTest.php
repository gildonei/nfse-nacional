<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Danfse;

use Mpdf\Mpdf;
use NfseNacional\Application\Service\DanfseService;
use NfseNacional\Domain\Danfse\DanfseOptions;
use NfseNacional\Domain\Danfse\DanfseStatus;
use PHPUnit\Framework\TestCase;
use setasign\Fpdi\PdfParser\StreamReader;

final class DanfseServiceTest extends TestCase
{
    private string $xml;

    protected function setUp(): void
    {
        $this->xml = (string) file_get_contents(dirname(__DIR__, 2) . '/Fixture/Danfse/nfse-completa.xml');
    }

    public function testGeraHtmlComIdentificacaoEHomologacao(): void
    {
        $html = (new DanfseService())->gerarHtml($this->xml);

        self::assertStringContainsString('DANFSe v2.0', $html);
        self::assertStringContainsString('NFS-e SEM VALIDADE JURÍDICA', $html);
        self::assertStringContainsString('42054075060066100012600000000000000000000000000001', $html);
        self::assertStringContainsString('data:image/png;base64,', $html);
    }

    public function testGeraPdfA4ComUmaPagina(): void
    {
        $pdf = (new DanfseService())->gerarPdf($this->xml);

        self::assertStringStartsWith('%PDF-', $pdf);
        $reader = new Mpdf();
        self::assertSame(1, $reader->setSourceFile(StreamReader::createByString($pdf)));
    }

    public function testGeraPdfDiretamenteDeArquivo(): void
    {
        $arquivo = dirname(__DIR__, 2) . '/Fixture/Danfse/nfse-completa.xml';
        $pdf = (new DanfseService())->gerarPdfDeArquivo($arquivo);
        self::assertStringStartsWith('%PDF-', $pdf);
    }

    public function testAplicaMarcaDeCancelamentoECanhoto(): void
    {
        $html = (new DanfseService())->gerarHtml(
            $this->xml,
            new DanfseOptions(status: DanfseStatus::Cancelada, incluirCanhoto: true),
        );
        self::assertStringContainsString('CANCELADA', $html);
        self::assertStringContainsString('Data Cientificação', $html);
    }

    public function testOcultaLinhaPisCofinsApos2026(): void
    {
        $xml = str_replace('<dCompet>2026-08-20</dCompet>', '<dCompet>2027-01-01</dCompet>', $this->xml);
        $html = (new DanfseService())->gerarHtml($xml);
        self::assertStringNotContainsString('PIS - Débito Apuração Própria', $html);
    }
}
