<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Xml;

use DateTime;
use DOMDocument;
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
use PHPUnit\Framework\TestCase;

/**
 * A DPS de EXPORTAÇÃO de serviço.
 *
 * O caso: prestador brasileiro, tomador pessoa jurídica no exterior sem
 * CPF/CNPJ, serviço consumido fora do país, cobrado em moeda estrangeira.
 *
 * É um ramo inteiro do layout que o caso nacional não exercita — outro
 * identificador de tomador (`cNaoNIF` ou `NIF` em vez de CNPJ), outro lado do
 * `<xs:choice>` do local de prestação (país em vez de município) e um bloco a
 * mais (`comExt`), que é onde a moeda e o valor em moeda estrangeira vivem.
 */
final class DpsXmlExportacaoTest extends TestCase
{
    private const FLORIANOPOLIS = 4205407;

    /**
     * Valida contra o schema da versão que a própria DPS DECLARA.
     *
     * É o que o Sefin faz: o atributo `versao` da raiz diz qual schema vale.
     * Validar contra outra versão testaria uma regra que não se aplica ao
     * documento — e foi o que aconteceu na primeira forma deste teste, que
     * usava o 1.01 e precisava tolerar um erro do próprio schema (ver
     * `testSchema101RecusaQualquerSerieNumerica`).
     *
     * Tolerância zero: qualquer recusa falha o teste.
     */
    public function testExportacaoDeServicoValidaContraOSchemaDaVersaoDeclarada(): void
    {
        $xml = $this->dpsDeExportacao();

        $versao = $this->versaoDeclarada($xml);
        $erros  = $this->validarContraSchema($xml, $versao);

        self::assertSame([], $erros, "schema {$versao}: " . implode(' | ', $erros));
    }

    /**
     * Registra, como fato, o defeito do schema 1.01 — que não é desta
     * biblioteca, mas morde quem validar contra ele.
     *
     * O `TSSerieDPS` do 1.01 traz `^0{0,4}\d{1,5}$`. Em XML Schema o `pattern`
     * já é ancorado, então `^` e `$` valem como caracteres literais e NENHUMA
     * série numérica casa. É o único dos 54 padrões do schema escrito assim,
     * e no 1.00 o mesmo tipo não tinha padrão nenhum.
     *
     * Se o schema empacotado for corrigido, este teste falha — e é esse o
     * aviso de que ele pode ser apagado.
     */
    public function testSchema101RecusaQualquerSerieNumerica(): void
    {
        $erros = $this->validarContraSchema($this->dpsDeExportacao(), '1.01');

        self::assertCount(1, $erros, implode(' | ', $erros));
        self::assertStringContainsString('serie', $erros[0]);
    }

    public function testLocalDePrestacaoNoExteriorNaoEmiteMunicipio(): void
    {
        $xml = $this->dpsDeExportacao();

        // TCLocPrest é <xs:choice>: ou o país, ou o município — nunca os dois.
        self::assertStringContainsString('<cPaisPrestacao>US</cPaisPrestacao>', $xml);
        self::assertStringNotContainsString('<cLocPrestacao>', $xml);
    }

    public function testTomadorSemDocumentoEIdentificadoAntesDoNome(): void
    {
        $xml = $this->dpsDeExportacao();

        // O TCInfoPessoa exige a identificação como PRIMEIRO filho.
        self::assertMatchesRegularExpression('#<toma><cNaoNIF>2</cNaoNIF><xNome>#', $xml);
    }

    public function testTomadorComNifUsaNifNoLugarDoMotivo(): void
    {
        $tomador = $this->tomadorDoExterior();
        $tomador->definirNif('ATU12345678');

        $xml = $this->render($this->comoExportacao($this->dps($tomador)));

        self::assertStringContainsString('<NIF>ATU12345678</NIF>', $xml);
        self::assertStringNotContainsString('<cNaoNIF>', $xml);
    }

    public function testMoedaEValorEmMoedaEstrangeiraChegamAoXml(): void
    {
        $xml = $this->dpsDeExportacao();

        // O valor em reais e o valor na moeda da transação convivem: um diz
        // quanto foi declarado, o outro quanto o cliente efetivamente pagou.
        self::assertStringContainsString('<tpMoeda>220</tpMoeda>', $xml);
        self::assertStringContainsString('<vServMoeda>100.00</vServMoeda>', $xml);
        self::assertStringContainsString('<vServ>515.75</vServ>', $xml);
    }

    public function testComExteriorTrazOsObrigatoriosMesmoSemOChamadorInformar(): void
    {
        $xml = $this->dpsDeExportacao();

        // O TCComExterior exige todos os filhos menos nDI e nRE. Bloco
        // incompleto é recusado inteiro, então os não informados recebem o
        // valor neutro do schema em vez de ficarem de fora.
        self::assertStringContainsString('<mdPrestacao>1</mdPrestacao>', $xml);
        self::assertStringContainsString('<vincPrest>0</vincPrest>', $xml);
        self::assertStringContainsString('<mecAFComexP>01</mecAFComexP>', $xml);
        self::assertStringContainsString('<mecAFComexT>01</mecAFComexT>', $xml);
        self::assertStringContainsString('<movTempBens>1</movTempBens>', $xml);
        self::assertStringContainsString('<mdic>0</mdic>', $xml);
    }

    public function testVendaNacionalNaoGanhaBlocoDeComercioExterior(): void
    {
        $tomador = new Tomador(
            email: new Email('cliente@exemplo.com.br'),
            documento: new Cnpj('51877676000107')
        );
        $tomador->definirNome('Cliente Nacional Ltda');

        $dps = $this->dps($tomador)
            ->definirCodigoLocalPrestacao((string) self::FLORIANOPOLIS)
            ->definirTributacaoIssqn(TributacaoIssqn::OperacaoTributavel);

        $xml = $this->render($dps);

        // O gatilho do bloco é o modo de prestação. Sem ele, nada muda para
        // quem já emitia — que é a garantia de que este acréscimo não mexe no
        // caminho nacional.
        self::assertStringNotContainsString('<comExt>', $xml);
        self::assertStringContainsString('<cLocPrestacao>4205407</cLocPrestacao>', $xml);
        self::assertStringContainsString('<CNPJ>51877676000107</CNPJ>', $xml);
    }

    public function testModoDePrestacaoSozinhoNaoDisparaComercioExterior(): void
    {
        // O `docs/emissao-dps.php` define ModoPrestacao::ConsumoNoBrasil numa
        // venda NACIONAL. Se o bloco fosse disparado pelo modo de prestação,
        // toda emissão nacional existente passaria a exigir `tpMoeda` e
        // quebraria sem nada ter mudado do lado de quem chama. O gatilho é a
        // operação tocar o exterior: país de prestação ou moeda estrangeira.
        $tomador = new Tomador(
            email: new Email('cliente@exemplo.com.br'),
            documento: new Cnpj('51877676000107')
        );
        $tomador->definirNome('Cliente Nacional Ltda');

        $dps = $this->dps($tomador)
            ->definirCodigoLocalPrestacao((string) self::FLORIANOPOLIS)
            ->definirModoPrestacao(ModoPrestacao::ConsumoNoBrasil)
            ->definirVinculoEntrePartes(VinculoEntrePartes::SemVinculo)
            ->definirTributacaoIssqn(TributacaoIssqn::OperacaoTributavel);

        $xml = $this->render($dps);

        self::assertStringNotContainsString('<comExt>', $xml);
        self::assertStringContainsString('<cLocPrestacao>4205407</cLocPrestacao>', $xml);
    }

    public function testCodigoDePaisRecusaValorForaDoIsoAlfa2(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new Dps())->definirCodigoPaisPrestacao('2496');
    }

    public function testCodigoDeMoedaRecusaSigla(): void
    {
        // O TSCodMoeda pede os 3 dígitos do BACEN: o dólar é 220, não 'USD'.
        $this->expectException(\InvalidArgumentException::class);
        (new Dps())->definirCodigoMoeda('USD');
    }

    /* ─────────────────────────────────────────────────────────────────── */

    private function dpsDeExportacao(): string
    {
        return $this->render($this->comoExportacao($this->dps($this->tomadorDoExterior())));
    }

    private function tomadorDoExterior(): Tomador
    {
        $tomador = new Tomador(email: new Email('billing@acme-example.com'));
        $tomador->definirNome('ACME Inc.');
        $tomador->definirMotivoNaoInformarNif(MotivoNaoInformarNif::NaoExigenciaNIF);

        return $tomador;
    }

    private function dps(Tomador $tomador): Dps
    {
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
                codigoCidade: self::FLORIANOPOLIS
            )
        );
        $prestador->definirOptanteSimplesNacional(OptanteSimplesNacional::OptanteMEEPP);
        $prestador->definirRegimeTributacaoSimplesNacional(
            RegimeTributacaoSimplesNacional::RegimeApuracaoTributosFederaisMunicipalSN
        );
        $prestador->definirRegimeEspecialTributacao(RegimeEspecialTributacaoMunicipal::Nenhum);

        return (new Dps())
            ->definirNumeroDps('1')
            ->definirSerie('1')
            ->definirPrestador($prestador)
            ->definirTomador($tomador)
            ->definirTipoAmbiente(2)
            ->definirTipoEmitente(TipoEmitente::Prestador)
            ->definirDataHoraEmissao(new DateTime('2026-09-19 10:00:00'))
            ->definirDataCompetencia(new DateTime('2026-09-19 10:00:00'))
            ->definirVersaoAplicacao('1.0')
            ->definirCodigoLocalEmissao((string) self::FLORIANOPOLIS)
            ->definirCodigoTributacaoNacional(ListaServicosNacional::S010501)
            ->definirDescricaoServico('Licenciamento de uso de software como servico')
            ->definirValorServico(515.75)
            ->definirValorRecebido(515.75);
    }

    /**
     * O que transforma a DPS acima em exportação — e nada mais.
     *
     * Separado de propósito: é exatamente este conjunto que o caminho
     * nacional NÃO aplica, e é o que o teste da venda nacional prova não ter
     * mudado.
     */
    private function comoExportacao(Dps $dps): Dps
    {
        return $dps
            ->definirCodigoPaisPrestacao('US')
            ->definirModoPrestacao(ModoPrestacao::Transfronteirico)
            ->definirVinculoEntrePartes(VinculoEntrePartes::SemVinculo)
            ->definirCodigoMoeda('220')
            ->definirValorServicoMoeda(100.00)
            ->definirTributacaoIssqn(TributacaoIssqn::ExportacaoServico);
    }

    private function render(Dps $dps): string
    {
        return (new DpsXml($dps))->renderDps();
    }

    private function versaoDeclarada(string $xml): string
    {
        self::assertMatchesRegularExpression('#<DPS[^>]*versao="([^"]+)"#', $xml);
        preg_match('#<DPS[^>]*versao="([^"]+)"#', $xml, $m);

        return $m[1];
    }

    /** @return list<string> */
    private function validarContraSchema(string $xml, string $versao): array
    {
        $anterior = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $doc->schemaValidate(
            dirname(__DIR__, 3) . "/src/Resources/danfse/schemas/{$versao}/DPS_v{$versao}.xsd"
        );

        $erros = array_map(
            static fn (\LibXMLError $e): string => trim($e->message),
            libxml_get_errors()
        );

        libxml_clear_errors();
        libxml_use_internal_errors($anterior);

        return $erros;
    }
}
