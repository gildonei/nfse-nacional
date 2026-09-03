<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Danfse;

use Mpdf\QrCode\Output\Png;
use Mpdf\QrCode\QrCode;
use NfseNacional\Domain\Danfse\DanfseData;
use NfseNacional\Domain\Danfse\DanfseException;
use NfseNacional\Domain\Danfse\DanfseOptions;

final class DanfseHtmlRenderer
{
    public const VERSAO_LAYOUT = 'DANFSe v2.0 - NT 008 v1.02';
    public const URL_CONSULTA = 'https://www.nfse.gov.br/ConsultaPublica/?tpc=1&chave=';

    public function renderizar(DanfseData $data, ?DanfseOptions $options = null): string
    {
        $options ??= new DanfseOptions();
        $logo = dirname(__DIR__, 2) . '/Resources/danfse/logo-nfse-horizontal.png';
        $template = dirname(__DIR__, 2) . '/Resources/danfse/template.php';
        if (!is_file($logo) || !is_file($template)) {
            throw new DanfseException('Os recursos do DANFSe não foram encontrados no pacote.');
        }

        $consulta = self::URL_CONSULTA . $data->chaveAcesso();
        $qrCode = new QrCode($consulta, QrCode::ERROR_CORRECTION_MEDIUM);
        $qrCodeDataUri = 'data:image/png;base64,' . base64_encode((new Png())->output($qrCode, 220));
        $logoDataUri = 'data:image/png;base64,' . base64_encode((string) file_get_contents($logo));
        $informacoesComplementares = $this->montarInformacoesComplementares($data);

        ob_start();
        require $template;
        $html = ob_get_clean();
        if (!is_string($html)) {
            throw new DanfseException('Não foi possível renderizar o DANFSe.');
        }

        return $html;
    }

    private function montarInformacoesComplementares(DanfseData $data): string
    {
        $info = $data->informacoesComplementares;
        $partes = [];
        $campos = [
            'informacao' => 'Inf. Cont.',
            'nfseSubstituida' => 'NFS-e Subst.',
            'documentoReferenciado' => 'Doc. Ref.',
            'codigoObra' => 'Cod. Obra',
            'inscricaoImobiliaria' => 'Insc. Imob.',
            'codigoEvento' => 'Cod. Evt.',
            'documentoTecnico' => 'Doc. Tec.',
            'numeroPedido' => 'Núm. Ped.',
            'itemPedido' => 'Item Ped.',
            'administracaoTributaria' => 'Inf. A. T. Mun.',
        ];
        foreach ($campos as $campo => $rotulo) {
            if (($info[$campo] ?? '') !== '') {
                $partes[] = $rotulo . ': ' . $info[$campo];
            }
        }

        $federal = $this->tributoAproximado($info, 'Federal');
        $estadual = $this->tributoAproximado($info, 'Estadual');
        $municipal = $this->tributoAproximado($info, 'Municipal');
        $tributos = 'Totais Aproximados dos Tributos cfe. Lei nº 12.741/2012: Federais: ' . $federal
            . '; Estaduais: ' . $estadual . '; Municipais: ' . $municipal;

        $texto = implode(' | ', $partes);
        $limiteTexto = max(0, 1997 - mb_strlen($tributos) - 3);
        $texto = $limiteTexto > 0 ? DanfseFormatter::texto($texto, $limiteTexto) : '';

        return ($texto !== '-' && $texto !== '' ? $texto . ' | ' : '') . $tributos;
    }

    private function tributoAproximado(array $info, string $tipo): string
    {
        $valor = $info['tributosValor' . $tipo] ?? '';
        if ($valor !== '') {
            return DanfseFormatter::moeda($valor);
        }
        $percentual = $info['tributosPercentual' . $tipo] ?? '';
        return DanfseFormatter::percentual($percentual);
    }
}
