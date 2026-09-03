<?php

declare(strict_types=1);

use NfseNacional\Domain\Danfse\DanfseStatus;
use NfseNacional\Infrastructure\Danfse\DanfseFormatter as F;

$e = static fn (?string $value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$txt = static fn (?string $value, int $limit = 0): string => $e(F::texto($value, $limit));
$join = static fn (array $values): string => implode(' / ', array_values(array_filter($values, static fn ($value): bool => trim((string) $value) !== '')));
$person = static function (string $title, array $pessoa) use ($e, $txt, $join): string {
    if (!($pessoa['identificado'] ?? false)) {
        return '<div class="section compact"><div class="section-title">' . $e($title) . '</div><div class="notice">'
            . $e($title . ' DA OPERAÇÃO NÃO IDENTIFICADO NA NFS-e') . '</div></div>';
    }
    $municipio = $join([$pessoa['municipio'] ?: $pessoa['codigoMunicipio'], $pessoa['uf']]);
    $codigoCep = $join([$pessoa['codigoMunicipio'], F::cep($pessoa['cep'])]);
    return '<div class="section person"><div class="section-title">' . $e($title) . '</div><table><tr>'
        . '<td><b>CNPJ / CPF / NIF</b>&nbsp;<span>' . $txt(F::documento($pessoa['documento'])) . '</span></td>'
        . '<td><b>Indicador Municipal (Inscrição)</b>&nbsp;<span>' . $txt($pessoa['inscricaoMunicipal']) . '</span></td>'
        . '<td><b>Telefone</b>&nbsp;<span>' . $e(F::telefone($pessoa['telefone'])) . '</span></td></tr><tr>'
        . '<td colspan="2"><b>Nome / Nome Empresarial</b>&nbsp;<span>' . $txt($pessoa['nome'], 80) . '</span></td>'
        . '<td><b>Município / Sigla UF</b>&nbsp;<span>' . $txt($municipio, 40) . '</span></td></tr><tr>'
        . '<td colspan="2"><b>Endereço</b>&nbsp;<span>' . $e(F::endereco($pessoa)) . '</span></td>'
        . '<td><b>Código IBGE / CEP</b>&nbsp;<span>' . $e($codigoCep) . '</span></td></tr><tr class="optional">'
        . '<td colspan="2"><b>Email</b>&nbsp;<span>' . $txt($pessoa['email'], 80) . '</span></td>'
        . '<td></td></tr></table></div>';
};

$id = $data->identificacao;
$servico = $data->servico;
$issqn = $data->issqn;
$federal = $data->tributosFederais;
$ibs = $data->ibsCbs;
$totais = $data->totais;
$municipioEmitente = trim($id['municipio'] . ($id['uf'] ? ' / ' . $id['uf'] : ''));
$exibirPisCofins = $id['competencia'] === '' || substr($id['competencia'], 0, 4) <= '2026';
$marca = match ($options->status) {
    DanfseStatus::Cancelada => 'CANCELADA',
    DanfseStatus::Substituida => 'SUBSTITUÍDA',
    default => '',
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
@page { margin: 1.5mm; }
* { box-sizing: border-box; }
body { margin: 0; color: #000; font-family: microsoftsansserif, dejavusans, sans-serif; font-size: 7pt; }
.danfse { position: relative; border: 1pt solid #000; width: 100%; height: 293.5mm; overflow: hidden; }
.watermark { position: fixed; top: 118mm; left: 23mm; width: 165mm; text-align: center; transform: rotate(-35deg); font: 50pt arial, dejavusans, sans-serif; color: #a6a6a6; opacity: .45; z-index: 99; }
.header { height: 12mm; background: #f2f2f2; border-bottom: .5pt solid #000; width: 100%; }
.header td { border: 0; vertical-align: middle; padding: 1mm 2mm; }
.logo { width: 40mm; max-height: 8.5mm; }
.header-title { text-align: center; font-family: arial, dejavusans, sans-serif; font-size: 9pt; font-weight: bold; line-height: 1.4; }
.invalid { color: #ed1c24; }
.header-city { text-align: right; font-size: 6pt; line-height: 1.3; }
.header-city strong { font-size: 8pt; font-weight: normal; }
.identification { height: 28mm; border-bottom: .5pt solid #000; }
.identification table, .section table { width: 100%; border-collapse: collapse; table-layout: fixed; }
td { border-right: .5pt solid #000; border-bottom: .5pt solid #000; padding: .55mm 1mm; vertical-align: top; overflow: hidden; }
td:last-child { border-right: 0; }
tr:last-child td { border-bottom: 0; }
b { display: inline; font-family: arial, dejavusans, sans-serif; font-size: 6pt; text-transform: none; line-height: 1.05; }
span { display: inline; line-height: 1.15; margin-top: .25mm; }
.identification b { font-size: 7pt; text-transform: uppercase; }
.identification td { height: 7mm; }
.key { font-size: 8pt; letter-spacing: .3pt; white-space: nowrap; }
.qr-cell { width: 49mm; text-align: center; padding: 1mm; }
.qr { width: 15.2mm; height: 15.2mm; }
.qr-note { font-size: 6pt; line-height: 1.1; margin-top: .4mm; }
.section { border-bottom: .5pt solid #000; }
.section-title { height: 4mm; padding: .75mm 1mm; background: #f2f2f2; border-bottom: .5pt solid #000; font-family: arial, dejavusans, sans-serif; font-size: 7pt; font-weight: bold; text-transform: uppercase; }
.section td { height: 6.2mm; }
.person .optional td { height: 5mm; }
.compact { min-height: 7.2mm; }
.notice { height: 3.2mm; padding: .6mm 1mm; font-weight: bold; }
.service-description { height: 12mm; padding: 1mm; border-top: .5pt solid #000; overflow: hidden; }
.service-description b { margin-bottom: .7mm; }
.grid-4 td { width: 25%; }
.grid-3 td { width: 33.333%; }
.gray-value { background: #f2f2f2; }
.complementary { height: 10mm; padding: 1mm; line-height: 1.18; overflow: hidden; }
.stub { height: 8mm; }
.stub td { height: 8mm; }
</style>
</head>
<body>
<div class="danfse">
<?php if ($marca !== ''): ?><div class="watermark"><?= $e($marca) ?></div><?php endif; ?>
<table class="header"><tr>
    <td style="width:26%"><img class="logo" src="<?= $e($logoDataUri) ?>" alt="NFS-e"></td>
    <td class="header-title" style="width:49%">DANFSe v2.0<br>Documento Auxiliar da NFS-e<?php if ($data->homologacao()): ?><br><span class="invalid">NFS-e SEM VALIDADE JURÍDICA</span><?php endif; ?></td>
    <td class="header-city" style="width:25%"><strong><?= $txt($municipioEmitente, 40) ?></strong><br><?= $txt($id['ambienteGerador'], 40) ?><br><?= $txt($id['tipoAmbiente']) ?></td>
</tr></table>

<div class="identification"><table><tr>
    <td colspan="3"><b>Chave de Acesso da NFS-e</b><span class="key"><?= $e($id['chaveAcesso']) ?></span></td>
    <td class="qr-cell" rowspan="4"><img class="qr" src="<?= $e($qrCodeDataUri) ?>" alt="QR Code"><div class="qr-note">A autenticidade desta NFS-e pode ser verificada pela leitura deste código QR ou pela consulta da chave de acesso no portal nacional da NFS-e</div></td>
</tr><tr>
    <td><b>Número da NFS-e</b>&nbsp;<span><?= $txt($id['numero']) ?></span></td>
    <td><b>Competência da NFS-e</b>&nbsp;<span><?= $e(F::data($id['competencia'])) ?></span></td>
    <td><b>Data e Hora da Emissão</b>&nbsp;<span><?= $e(F::data($id['emissaoNfse'], true)) ?></span></td>
</tr><tr>
    <td><b>Número da DPS</b>&nbsp;<span><?= $txt($id['numeroDps']) ?></span></td>
    <td><b>Série da DPS</b>&nbsp;<span><?= $txt($id['serieDps']) ?></span></td>
    <td><b>Data e Hora da Emissão da DPS</b>&nbsp;<span><?= $e(F::data($id['emissaoDps'], true)) ?></span></td>
</tr><tr>
    <td><b>Emitente da NFS-e</b><span class="gray-value"><?= $txt($id['emitente'], 40) ?></span></td>
    <td><b>Situação da NFS-e</b>&nbsp;<span><?= $txt($id['situacao'], 40) ?></span></td>
    <td><b>Finalidade</b>&nbsp;<span><?= $txt($id['finalidade'], 40) ?></span></td>
</tr></table></div>

<?= $person('PRESTADOR / FORNECEDOR', $data->prestador) ?>
<?php if ($data->prestador['identificado'] ?? false): ?>
<div class="section"><table><tr>
    <td><b>Simples Nacional na Data de Competência</b>&nbsp;<span><?= $txt($data->prestador['simplesNacional'], 40) ?></span></td>
    <td colspan="3"><b>Regime de Apuração Tributária pelo SN</b>&nbsp;<span><?= $txt($data->prestador['regimeSimples'], 80) ?></span></td>
</tr></table></div>
<?php endif; ?>
<?= $person('TOMADOR / ADQUIRENTE', $data->tomador) ?>
<?php
$destinatarioEhTomador = ($data->destinatario['identificado'] ?? false)
    && ($data->tomador['identificado'] ?? false)
    && $data->destinatario['documento'] !== ''
    && $data->destinatario['documento'] === $data->tomador['documento'];
?>
<?php if ($destinatarioEhTomador): ?>
<div class="section compact"><div class="section-title">DESTINATÁRIO</div><div class="notice">O DESTINATÁRIO É O PRÓPRIO TOMADOR/ADQUIRENTE DA OPERAÇÃO</div></div>
<?php else: ?><?= $person('DESTINATÁRIO', $data->destinatario) ?><?php endif; ?>
<?= $person('INTERMEDIÁRIO', $data->intermediario) ?>

<div class="section"><div class="section-title">SERVIÇO PRESTADO</div><table class="grid-3"><tr>
    <td><b>Código de Tributação Nacional / Municipal</b>&nbsp;<span><?= $txt($join([$servico['codigoTributacaoNacional'], $servico['codigoTributacaoMunicipal']]), 40) ?></span></td>
    <td><b>Código da NBS</b>&nbsp;<span><?= $txt($servico['codigoNbs']) ?></span></td>
    <td><b>Local da Prestação / UF / País</b>&nbsp;<span><?= $txt($join([$servico['codigoLocalPrestacao'], $servico['localPrestacao'], $servico['ufPrestacao'], $servico['paisPrestacao']]), 56) ?></span></td>
</tr><tr><td colspan="3"><b>Descrição do Código de Tributação Nacional / Municipal</b>&nbsp;<span><?= $txt($servico['descricaoTributacao'], 120) ?></span></td></tr></table>
<div class="service-description"><b>Descrição do Serviço</b><?= nl2br($txt($servico['descricao'], 1000)) ?></div></div>

<?php if ($issqn['tipoTributacaoCodigo'] !== '1' && $issqn['tipoTributacaoCodigo'] !== ''): ?>
<div class="section compact"><div class="section-title">TRIBUTAÇÃO MUNICIPAL (ISSQN)</div><div class="notice">TRIBUTAÇÃO MUNICIPAL (ISSQN) - OPERAÇÃO NÃO SUJEITA AO ISSQN</div></div>
<?php else: ?>
<div class="section"><div class="section-title">TRIBUTAÇÃO MUNICIPAL (ISSQN)</div><table class="grid-4"><tr>
    <td><b>Tipo de Tributação do ISSQN</b>&nbsp;<span><?= $txt($issqn['tipoTributacao'], 40) ?></span></td>
    <td colspan="2"><b>Município / UF / País de Incidência</b>&nbsp;<span><?= $txt($join([$issqn['codigoMunicipioIncidencia'], $issqn['municipioIncidencia'], $issqn['ufIncidencia'], $issqn['paisIncidencia']]), 80) ?></span></td>
    <td><b>Regime Especial</b>&nbsp;<span><?= $txt($issqn['regimeEspecial'], 40) ?></span></td>
</tr><tr>
    <td><b>Tipo de Imunidade</b>&nbsp;<span><?= $txt($issqn['imunidade'], 40) ?></span></td>
    <td><b>Suspensão da Exigibilidade</b>&nbsp;<span><?= $txt($issqn['suspensao'], 40) ?></span></td>
    <td><b>Número Processo Suspensão</b>&nbsp;<span><?= $txt($issqn['processoSuspensao'], 40) ?></span></td>
    <td><b>Benefício Municipal</b>&nbsp;<span><?= $txt($issqn['beneficioMunicipal'], 40) ?></span></td>
</tr><tr>
    <td><b>Cálculo do BM</b>&nbsp;<span><?= $e(F::moeda($issqn['calculoBeneficio'])) ?></span></td>
    <td><b>Total Deduções/Reduções</b>&nbsp;<span><?= $e(F::moeda($issqn['deducoesReducoes'])) ?></span></td>
    <td colspan="2"><b>Desconto Incondicionado</b>&nbsp;<span><?= $e(F::moeda($issqn['descontoIncondicionado'])) ?></span></td>
</tr><tr>
    <td><b>BC ISSQN</b>&nbsp;<span><?= $e(F::moeda($issqn['baseCalculo'])) ?></span></td>
    <td><b>Alíquota Aplicada</b>&nbsp;<span><?= $e(F::percentual($issqn['aliquota'])) ?></span></td>
    <td><b>Retenção do ISSQN</b>&nbsp;<span><?= $txt($issqn['retencao'], 40) ?></span></td>
    <td><b>ISSQN Apurado</b>&nbsp;<span><?= $e(F::moeda($issqn['valor'])) ?></span></td>
</tr></table></div>
<?php endif; ?>

<div class="section"><div class="section-title">TRIBUTAÇÃO FEDERAL (EXCETO CBS)</div><table class="grid-4"><tr>
    <td><b>IRRF</b>&nbsp;<span><?= $e(F::moeda($federal['irrf'])) ?></span></td>
    <td><b>Contribuição Previdenciária - Retida</b>&nbsp;<span><?= $e(F::moeda($federal['previdenciaria'])) ?></span></td>
    <td><b>Contribuições Sociais - Retidas</b>&nbsp;<span><?= $e(F::moeda($federal['contribuicoesSociais'])) ?></span></td>
    <td><b>Descrição Contrib. Sociais - Retidas</b>&nbsp;<span><?= $txt($federal['tipoRetencaoPisCofins'], 35) ?></span></td>
</tr><?php if ($exibirPisCofins): ?><tr>
    <td colspan="2"><b>PIS - Débito Apuração Própria</b>&nbsp;<span><?= $e(F::moeda($federal['pis'])) ?></span></td>
    <td colspan="2"><b>COFINS - Débito Apuração Própria</b>&nbsp;<span><?= $e(F::moeda($federal['cofins'])) ?></span></td>
</tr><?php endif; ?></table></div>

<?php if ($ibs['presente']): ?>
<div class="section"><div class="section-title">TRIBUTAÇÃO IBS / CBS</div><table class="grid-4"><tr>
    <td><b>CST / cClassTrib</b>&nbsp;<span><?= $txt($join([$ibs['cst'], $ibs['classificacaoTributaria']])) ?></span></td>
    <td colspan="3"><b>Indicador de Operação / Código IBGE / Município / UF</b>&nbsp;<span><?= $txt($join([$ibs['indicadorOperacao'], $ibs['codigoLocalIncidencia'], $ibs['localIncidencia'], $ibs['ufIncidencia']]), 56) ?></span></td>
</tr><tr>
    <td><b>Exclusões e Reduções da Base</b>&nbsp;<span><?= $e(F::moeda($ibs['exclusoesReducoes'])) ?></span></td>
    <td><b>Base Após Exclusões e Reduções</b>&nbsp;<span><?= $e(F::moeda($ibs['baseCalculo'])) ?></span></td>
    <td><b>Red. Alíquota IBS UF / Mun / CBS</b>&nbsp;<span><?= $e(F::percentual($ibs['reducaoAliquotaUf']) . ' / ' . F::percentual($ibs['reducaoAliquotaMunicipal']) . ' / ' . F::percentual($ibs['reducaoAliquotaCbs'])) ?></span></td>
    <td><b>Alíquota IBS UF / Mun</b>&nbsp;<span><?= $e(F::percentual($ibs['aliquotaUf']) . ' / ' . F::percentual($ibs['aliquotaMunicipal'])) ?></span></td>
</tr><tr>
    <td><b>Alíquota Efetiva Municipal - IBS</b>&nbsp;<span><?= $e(F::percentual($ibs['aliquotaEfetivaMunicipal'])) ?></span></td>
    <td><b>Valor Apurado Municipal - IBS</b>&nbsp;<span><?= $e(F::moeda($ibs['valorMunicipal'])) ?></span></td>
    <td><b>Alíquota Efetiva Estadual - IBS</b>&nbsp;<span><?= $e(F::percentual($ibs['aliquotaEfetivaUf'])) ?></span></td>
    <td><b>Valor Apurado Estadual - IBS</b>&nbsp;<span><?= $e(F::moeda($ibs['valorUf'])) ?></span></td>
</tr><tr>
    <td><b>Valor Total Apurado - IBS</b>&nbsp;<span><?= $e(F::moeda($ibs['valorIbs'])) ?></span></td>
    <td><b>Alíquota - CBS</b>&nbsp;<span><?= $e(F::percentual($ibs['aliquotaCbs'])) ?></span></td>
    <td><b>Alíquota Efetiva - CBS</b>&nbsp;<span><?= $e(F::percentual($ibs['aliquotaEfetivaCbs'])) ?></span></td>
    <td><b>Valor Total Apurado - CBS</b>&nbsp;<span><?= $e(F::moeda($ibs['valorCbs'])) ?></span></td>
</tr></table></div>
<?php endif; ?>

<div class="section"><div class="section-title">VALOR TOTAL DA NFS-e</div><table class="grid-4"><tr>
    <td><b>Valor da Operação / Serviço</b>&nbsp;<span><?= $e(F::moeda($totais['servico'])) ?></span></td>
    <td><b>Desconto Incondicionado</b>&nbsp;<span><?= $e(F::moeda($totais['descontoIncondicionado'])) ?></span></td>
    <td><b>Desconto Condicionado</b>&nbsp;<span><?= $e(F::moeda($totais['descontoCondicionado'])) ?></span></td>
    <td><b>Total das Retenções</b>&nbsp;<span><?= $e(F::moeda($totais['retencoes'])) ?></span></td>
</tr><tr>
    <td><b>Valor Líquido da NFS-e</b>&nbsp;<span><?= $e(F::moeda($totais['liquido'])) ?></span></td>
    <td><b>Total do IBS/CBS</b>&nbsp;<span><?= $e(F::moeda($totais['ibsCbs'])) ?></span></td>
    <td colspan="2" class="gray-value"><b>Valor Líquido da NFS-e + IBS/CBS</b>&nbsp;<span><?= $e(F::moeda($totais['liquidoComIbsCbs'])) ?></span></td>
</tr></table></div>

<div class="section"><div class="section-title">INFORMAÇÕES COMPLEMENTARES</div><div class="complementary"><?= nl2br($e($informacoesComplementares)) ?></div></div>

<?php if ($options->incluirCanhoto): ?>
<table class="stub"><tr><td><b>Data Cientificação</b></td><td><b>Identificação e Assinatura</b></td><td colspan="2"><b>Nº NFS-e / Chave NFS-e</b>&nbsp;<span><?= $txt($id['numero'] . ' / ' . $id['chaveAcesso'], 66) ?></span></td></tr></table>
<?php endif; ?>
</div>
</body>
</html>
