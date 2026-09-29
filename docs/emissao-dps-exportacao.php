<?php

/**
 * Exemplo de DPS de EXPORTAÇÃO DE SERVIÇO
 *
 * O par do `emissao-dps.php`: lá o tomador é brasileiro e o serviço se consuma
 * aqui; aqui o tomador é pessoa jurídica no exterior, o serviço se consuma
 * fora do país e a cobrança foi em moeda estrangeira.
 *
 * Três diferenças, e é só isso:
 *
 *   1. o tomador não tem CPF/CNPJ — identifica-se por NIF, ou declara o motivo
 *      de não ter um (`cNaoNIF`);
 *   2. o local de prestação é o PAÍS, não o município — os dois são mutuamente
 *      exclusivos no schema;
 *   3. o bloco `comExt` carrega a moeda e o valor na moeda estrangeira. O
 *      `valorServico` continua em reais: é o que se declara.
 *
 * Roda no terminal e não precisa de certificado — monta a DPS, valida contra o
 * XSD e imprime o XML. Para assinar e enviar, ver `emissao-dps.php`.
 *
 *   php docs/emissao-dps-exportacao.php
 *
 * @package NfseNacional\Docs
 */

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use NfseNacional\Domain\Entity\Dps;
use NfseNacional\Domain\Entity\Prestador;
use NfseNacional\Domain\Entity\Tomador;
use NfseNacional\Domain\Enum\ListaServicosNacional;
use NfseNacional\Domain\Enum\ModoPrestacao;
use NfseNacional\Domain\Enum\MotivoNaoInformarNif;
use NfseNacional\Domain\Enum\OptanteSimplesNacional;
use NfseNacional\Domain\Enum\RegimeEspecialTributacaoMunicipal;
use NfseNacional\Domain\Enum\RegimeTributacaoSimplesNacional;
use NfseNacional\Domain\Enum\TipoEmitente;
use NfseNacional\Domain\Enum\TributacaoIssqn;
use NfseNacional\Domain\Enum\VinculoEntrePartes;
use NfseNacional\Domain\ValueObject\Cnpj;
use NfseNacional\Domain\ValueObject\Email;
use NfseNacional\Domain\ValueObject\Endereco;
use NfseNacional\Domain\Xml\DpsXml;
use NfseNacional\Application\Service\SefinNacionalService;

date_default_timezone_set('America/Sao_Paulo');

$codigoMunicipio = 4205407;   // Florianópolis/SC

// ── O prestador, como em qualquer DPS ───────────────────────────────────────
$prestador = new Prestador(
    email: new Email('contato@prestadora.com.br'),
    documento: new Cnpj('50600661000126'),
    endereco: new Endereco(
        bairro: 'Centro',
        cep: '88010000',
        estado: 'SC',
        cidade: 'Florianopolis',
        logradouro: 'Rua Exemplo',
        numero: '100',
        codigoCidade: $codigoMunicipio
    )
);
$prestador->definirOptanteSimplesNacional(OptanteSimplesNacional::OptanteMEEPP);
$prestador->definirRegimeTributacaoSimplesNacional(
    RegimeTributacaoSimplesNacional::RegimeApuracaoTributosFederaisMunicipalSN
);
$prestador->definirRegimeEspecialTributacao(RegimeEspecialTributacaoMunicipal::Nenhum);

// ── 1. O tomador do exterior ────────────────────────────────────────────────
$tomador = new Tomador(email: new Email('billing@acme-example.com'));
$tomador->definirNome('ACME Inc.');

/* Tendo NIF, informe o NIF. Não tendo, declare o motivo — são as duas únicas
   saídas do <xs:choice> para quem não tem CPF nem CNPJ, e informar um zera o
   outro. */
$tomador->definirMotivoNaoInformarNif(MotivoNaoInformarNif::NaoExigenciaNIF);
// $tomador->definirNif('ATU12345678');

// ── A DPS ───────────────────────────────────────────────────────────────────
$dps = (new Dps())
    ->definirNumeroDps('1')
    ->definirSerie('1')
    ->definirPrestador($prestador)
    ->definirTomador($tomador)
    ->definirTipoAmbiente(SefinNacionalService::AMBIENTE_HOMOLOGACAO)
    ->definirTipoEmitente(TipoEmitente::Prestador)
    ->definirDataHoraEmissao(new DateTime('now'))
    ->definirDataCompetencia(new DateTime('now'))
    ->definirVersaoAplicacao('1.0')
    ->definirCodigoLocalEmissao((string) $codigoMunicipio)

    // ── 2. O local de prestação é o PAÍS ────────────────────────────────────
    // Código ISO alfa-2. Não se informa município junto: no schema os dois são
    // um <xs:choice>, e numa exportação não existe município de prestação.
    ->definirCodigoPaisPrestacao('US')

    ->definirCodigoTributacaoNacional(ListaServicosNacional::S010501)
    ->definirDescricaoServico('Licenciamento de uso de software como servico (SaaS)')

    // ── 3. Moeda e valor ────────────────────────────────────────────────────
    // O valor em reais é o que se declara; o valor em moeda estrangeira é o
    // que o cliente efetivamente pagou. Os dois convivem na mesma nota.
    ->definirValorServico(515.75)          // BRL
    ->definirValorRecebido(515.75)
    ->definirCodigoMoeda('220')            // 3 dígitos do BACEN: 220 = dólar dos EUA
    ->definirValorServicoMoeda(100.00)     // USD

    ->definirModoPrestacao(ModoPrestacao::Transfronteirico)
    ->definirVinculoEntrePartes(VinculoEntrePartes::SemVinculo)
    ->definirTributacaoIssqn(TributacaoIssqn::ExportacaoServico);

/* Os demais campos do comExt (mecanismos de apoio ao comércio exterior,
   movimentação temporária de bens, envio ao MDIC) são obrigatórios dentro do
   bloco e recebem o valor neutro do schema quando não informados. Para mudar:

   $dps->definirMecanismoApoioComexPrestador(MecanismoApoioComexPrestador::ProexFinanciamento)
       ->definirMecanismoApoioComexTomador(MecanismoApoioComexTomador::RoyaltiesAssistenciaTecnica)
       ->definirMovimentacaoTemporariaBens(MovimentacaoTemporariaBens::Nao)
       ->definirEnvioMdic(EnvioMdic::Enviar)
       ->definirNumeroRegistroExportacao('123456789');   // opcional
*/

$xml = (new DpsXml($dps))->renderDps();

echo $xml, PHP_EOL, PHP_EOL;

// ── Conferência contra o XSD da versão que a DPS declara ────────────────────
// O atributo `versao` da raiz diz qual schema vale — é o que o Sefin usa.
preg_match('#<DPS[^>]*versao="([^"]+)"#', $xml, $m);
$versao = $m[1] ?? '1.00';

libxml_use_internal_errors(true);
$doc = new DOMDocument();
$doc->loadXML($xml);
$doc->schemaValidate(__DIR__ . "/../src/Resources/danfse/schemas/{$versao}/DPS_v{$versao}.xsd");

$erros = array_map(
    static fn (LibXMLError $e): string => trim($e->message),
    libxml_get_errors()
);
libxml_clear_errors();

if ($erros === []) {
    echo "XML valido contra o DPS_v{$versao}.xsd.", PHP_EOL;
    exit(0);
}

/* Se for validar contra o schema 1.01 em vez da versão declarada: o
   TSSerieDPS dele traz o padrão `^0{0,4}\d{1,5}$`. Em XML Schema o pattern já
   é ancorado, então `^` e `$` valem como caracteres literais e nenhuma série
   numérica casa — é o único dos 54 padrões do schema escrito assim. */
foreach ($erros as $erro) {
    echo '  · ', $erro, PHP_EOL;
}
exit(1);
