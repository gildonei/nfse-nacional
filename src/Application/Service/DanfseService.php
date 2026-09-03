<?php

declare(strict_types=1);

namespace NfseNacional\Application\Service;

use NfseNacional\Domain\Danfse\DanfseData;
use NfseNacional\Domain\Danfse\DanfseException;
use NfseNacional\Domain\Danfse\DanfseOptions;
use NfseNacional\Infrastructure\Danfse\DanfseHtmlRenderer;
use NfseNacional\Infrastructure\Danfse\MpdfDanfseGenerator;
use NfseNacional\Infrastructure\Danfse\NfseXmlReader;

final class DanfseService
{
    public function __construct(
        private readonly NfseXmlReader $reader = new NfseXmlReader(),
        private readonly DanfseHtmlRenderer $htmlRenderer = new DanfseHtmlRenderer(),
        private readonly MpdfDanfseGenerator $pdfGenerator = new MpdfDanfseGenerator(),
    ) {
    }

    public function lerXml(string $xml, ?DanfseOptions $options = null): DanfseData
    {
        return $this->reader->ler($xml, $options);
    }

    public function gerarHtml(string $xml, ?DanfseOptions $options = null): string
    {
        return $this->htmlRenderer->renderizar($this->lerXml($xml, $options), $options);
    }

    public function gerarPdf(string $xml, ?DanfseOptions $options = null): string
    {
        return $this->pdfGenerator->gerar($this->lerXml($xml, $options), $options);
    }

    public function lerArquivo(string $arquivo, ?DanfseOptions $options = null): DanfseData
    {
        return $this->lerXml($this->conteudoArquivo($arquivo), $options);
    }

    public function gerarHtmlDeArquivo(string $arquivo, ?DanfseOptions $options = null): string
    {
        return $this->gerarHtml($this->conteudoArquivo($arquivo), $options);
    }

    public function gerarPdfDeArquivo(string $arquivo, ?DanfseOptions $options = null): string
    {
        return $this->gerarPdf($this->conteudoArquivo($arquivo), $options);
    }

    private function conteudoArquivo(string $arquivo): string
    {
        if (!is_file($arquivo) || !is_readable($arquivo)) {
            throw new DanfseException('O arquivo XML da NFS-e não existe ou não pode ser lido.');
        }
        $conteudo = file_get_contents($arquivo);
        if (!is_string($conteudo)) {
            throw new DanfseException('Não foi possível ler o arquivo XML da NFS-e.');
        }
        return $conteudo;
    }
}
