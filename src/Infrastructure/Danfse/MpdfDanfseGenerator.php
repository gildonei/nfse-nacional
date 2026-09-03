<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Danfse;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use NfseNacional\Domain\Danfse\DanfseData;
use NfseNacional\Domain\Danfse\DanfseException;
use NfseNacional\Domain\Danfse\DanfseOptions;

final class MpdfDanfseGenerator
{
    public function __construct(private readonly DanfseHtmlRenderer $renderer = new DanfseHtmlRenderer())
    {
    }

    public function gerar(DanfseData $data, ?DanfseOptions $options = null): string
    {
        $options ??= new DanfseOptions();
        $config = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 1.5,
            'margin_right' => 1.5,
            'margin_top' => 1.5,
            'margin_bottom' => 1.5,
            'default_font' => 'dejavusans',
            'default_font_size' => 7,
            'tempDir' => $options->diretorioTemporario ?? sys_get_temp_dir(),
        ];
        $config = $this->configurarFontes($config, $options);

        $mpdf = new Mpdf($config);
        $mpdf->SetTitle('DANFSe ' . $data->identificacao['numero']);
        $mpdf->SetAuthor('Sistema Nacional NFS-e');
        $mpdf->SetCreator(DanfseHtmlRenderer::VERSAO_LAYOUT);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($this->renderer->renderizar($data, $options));

        if (count($mpdf->pages) !== 1) {
            throw new DanfseException('O conteúdo da NFS-e excedeu uma página A4 e não pode gerar um DANFSe válido.');
        }

        return $mpdf->Output('', Destination::STRING_RETURN);
    }

    private function configurarFontes(array $config, DanfseOptions $options): array
    {
        if ($options->diretorioFontes === null) {
            return $config;
        }
        $fontes = array_filter([
            'arial' => $options->arquivoArial,
            'microsoftsansserif' => $options->arquivoMicrosoftSansSerif,
        ]);
        foreach ($fontes as $arquivo) {
            if (!is_file($options->diretorioFontes . DIRECTORY_SEPARATOR . $arquivo)) {
                throw new DanfseException('Arquivo de fonte não encontrado: ' . $arquivo);
            }
        }

        $fontDirs = (new ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];
        foreach ($fontes as $nome => $arquivo) {
            $fontData[$nome] = ['R' => $arquivo, 'B' => $arquivo];
        }
        $config['fontDir'] = array_merge($fontDirs, [$options->diretorioFontes]);
        $config['fontdata'] = $fontData;

        return $config;
    }
}
