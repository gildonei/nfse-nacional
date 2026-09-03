<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Danfse;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use NfseNacional\Domain\Danfse\DanfseData;
use NfseNacional\Domain\Danfse\DanfseException;
use NfseNacional\Domain\Danfse\DanfseOptions;
use NfseNacional\Domain\Enum\AmbienteGeradorNfse;
use NfseNacional\Domain\Enum\OptanteSimplesNacional;
use NfseNacional\Domain\Enum\RegimeTributacaoSimplesNacional;
use NfseNacional\Domain\Enum\SituacoesPossiveisNfse;
use NfseNacional\Domain\Enum\TipoEmitente;
use NfseNacional\Domain\Enum\TipoRetencaoIssqn;
use NfseNacional\Domain\Enum\TributacaoIssqn;

final class NfseXmlReader
{
    public const NAMESPACE_NFSE = 'http://www.sped.fazenda.gov.br/nfse';
    private const TAMANHO_MAXIMO_XML = 5_242_880;

    private DOMXPath $xpath;
    private DOMElement $infNfse;
    private DOMElement $infDps;

    public function __construct(private readonly LocalidadeResolver $localidades = new LocalidadeResolver())
    {
    }

    public function ler(string $xml, ?DanfseOptions $options = null): DanfseData
    {
        $options ??= new DanfseOptions();
        $this->carregar($xml, $options);

        $chave = preg_replace('/^NFS/', '', $this->infNfse->getAttribute('Id')) ?? '';
        if ($options->validarChaveAcesso && !preg_match('/^\d{50}$/', $chave)) {
            throw new DanfseException('A chave de acesso da NFS-e deve conter exatamente 50 dígitos.');
        }

        return new DanfseData(
            identificacao: $this->identificacao($chave),
            prestador: $this->participante('prest', true),
            tomador: $this->participante('toma'),
            destinatario: $this->participante('dest'),
            intermediario: $this->participante('interm'),
            servico: $this->servico(),
            issqn: $this->issqn(),
            tributosFederais: $this->tributosFederais(),
            ibsCbs: $this->ibsCbs(),
            totais: $this->totais(),
            informacoesComplementares: $this->informacoesComplementares(),
        );
    }

    private function carregar(string $xml, DanfseOptions $options): void
    {
        if ($xml === '' || strlen($xml) > self::TAMANHO_MAXIMO_XML) {
            throw new DanfseException('O XML da NFS-e está vazio ou excede o limite de 5 MB.');
        }
        if (stripos($xml, '<!DOCTYPE') !== false || stripos($xml, '<!ENTITY') !== false) {
            throw new DanfseException('O XML da NFS-e contém declarações externas não permitidas.');
        }
        if (!mb_check_encoding($xml, 'UTF-8')) {
            throw new DanfseException('O XML da NFS-e deve utilizar codificação UTF-8.');
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $anterior = libxml_use_internal_errors(true);
        try {
            if (!$dom->loadXML($xml, LIBXML_NONET | LIBXML_NOBLANKS | LIBXML_NOCDATA | LIBXML_COMPACT)) {
                $erro = libxml_get_last_error();
                throw new DanfseException('XML da NFS-e inválido' . ($erro ? ': ' . trim($erro->message) : '.'));
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($anterior);
        }

        if ($dom->documentElement?->localName !== 'NFSe' || $dom->documentElement->namespaceURI !== self::NAMESPACE_NFSE) {
            throw new DanfseException('O arquivo não é uma NFS-e do padrão nacional.');
        }
        if ($options->validarEsquema) {
            $this->validarEsquema($dom);
        }

        $this->xpath = new DOMXPath($dom);
        $this->xpath->registerNamespace('n', self::NAMESPACE_NFSE);
        $infNfse = $this->xpath->query('/n:NFSe/n:infNFSe')->item(0);
        $infDps = $this->xpath->query('/n:NFSe/n:infNFSe/n:DPS/n:infDPS')->item(0);
        if (!$infNfse instanceof DOMElement || !$infDps instanceof DOMElement) {
            throw new DanfseException('O XML não contém os grupos infNFSe e infDPS exigidos pelo padrão nacional.');
        }
        $this->infNfse = $infNfse;
        $this->infDps = $infDps;
    }

    private function validarEsquema(DOMDocument $dom): void
    {
        $versao = $dom->documentElement?->getAttribute('versao') ?: '';
        if (!in_array($versao, ['1.00', '1.01'], true)) {
            throw new DanfseException('Não há esquema XSD empacotado para a versão ' . ($versao ?: 'não informada') . '.');
        }
        $schema = dirname(__DIR__, 2) . '/Resources/danfse/schemas/' . $versao . '/NFSe_v' . $versao . '.xsd';
        $anterior = libxml_use_internal_errors(true);
        try {
            if (!$dom->schemaValidate($schema)) {
                $erros = array_map(static fn (\LibXMLError $erro): string => trim($erro->message), libxml_get_errors());
                throw new DanfseException('O XML não atende ao XSD nacional ' . $versao . ': ' . implode('; ', array_slice($erros, 0, 3)));
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($anterior);
        }
    }

    private function identificacao(string $chave): array
    {
        $ambGer = $this->v($this->infNfse, 'n:ambGer');
        $tpEmit = $this->v($this->infDps, 'n:tpEmit');
        $cStat = $this->v($this->infNfse, 'n:cStat');
        $finalidade = $this->v($this->infDps, 'n:IBSCBS/n:finNFSe');

        $codigoMunicipio = $this->v($this->infDps, 'n:cLocEmi');
        $municipio = $this->localidades->municipio($codigoMunicipio);

        return [
            'chaveAcesso' => $chave,
            'numero' => $this->v($this->infNfse, 'n:nNFSe'),
            'competencia' => $this->v($this->infDps, 'n:dCompet'),
            'emissaoNfse' => $this->v($this->infNfse, 'n:dhProc'),
            'numeroDps' => $this->v($this->infDps, 'n:nDPS'),
            'serieDps' => $this->v($this->infDps, 'n:serie'),
            'emissaoDps' => $this->v($this->infDps, 'n:dhEmi'),
            'emitenteCodigo' => $tpEmit,
            'emitente' => $this->enumDescricao(TipoEmitente::class, $tpEmit),
            'situacaoCodigo' => $cStat,
            'situacao' => $this->enumDescricao(SituacoesPossiveisNfse::class, $cStat),
            'finalidadeCodigo' => $finalidade,
            'finalidade' => ['0' => 'NFS-e regular', '1' => 'Crédito', '2' => 'Débito'][$finalidade] ?? $finalidade,
            'municipio' => $this->v($this->infNfse, 'n:xLocEmi') ?: $municipio['nome'],
            'uf' => $this->v($this->infNfse, 'n:emit/n:enderNac/n:UF') ?: $municipio['uf'],
            'ambienteGeradorCodigo' => $ambGer,
            'ambienteGerador' => $this->enumDescricao(AmbienteGeradorNfse::class, $ambGer),
            'tipoAmbienteCodigo' => $this->v($this->infDps, 'n:tpAmb'),
            'tipoAmbiente' => $this->v($this->infDps, 'n:tpAmb') === '2' ? 'Homologação' : 'Produção',
        ];
    }

    private function participante(string $grupo, bool $usarEmitenteComoFallback = false): array
    {
        $node = $this->xpath->query('n:' . $grupo, $this->infDps)->item(0);
        $emit = $usarEmitenteComoFallback ? $this->xpath->query('n:emit', $this->infNfse)->item(0) : null;
        if (!$node instanceof DOMElement && !$emit instanceof DOMElement) {
            return ['identificado' => false];
        }

        $base = $node instanceof DOMElement ? $node : $emit;
        $documento = $this->primeiro($base, ['n:CNPJ', 'n:CPF', 'n:NIF']);
        $nome = $this->v($base, 'n:xNome');
        if ($emit instanceof DOMElement) {
            $documento = $documento ?: $this->primeiro($emit, ['n:CNPJ', 'n:CPF', 'n:NIF']);
            $nome = $nome ?: $this->v($emit, 'n:xNome');
        }
        $valor = function (array $caminhos, array $caminhosEmitente = []) use ($base, $emit): string {
            $resultado = $this->primeiro($base, $caminhos);
            if ($resultado === '' && $emit instanceof DOMElement) {
                $resultado = $this->primeiro($emit, $caminhosEmitente ?: $caminhos);
            }
            return $resultado;
        };
        $codigoMunicipio = $valor(['n:end/n:endNac/n:cMun'], ['n:enderNac/n:cMun']);
        $municipioDominio = $this->localidades->municipio($codigoMunicipio);
        $nomeMunicipio = $valor(['n:end/n:endExt/n:xCidade']);
        if ($nomeMunicipio === '' && $usarEmitenteComoFallback) {
            $nomeMunicipio = $this->v($this->infNfse, 'n:xLocEmi');
        }
        $nomeMunicipio = $nomeMunicipio ?: $municipioDominio['nome'];

        return [
            'identificado' => true,
            'documento' => $documento,
            'inscricaoMunicipal' => $valor(['n:IM']),
            'telefone' => $valor(['n:fone']),
            'nome' => $nome,
            'codigoMunicipio' => $codigoMunicipio,
            'municipio' => $nomeMunicipio,
            'uf' => $valor(['n:end/n:endNac/n:UF', 'n:end/n:endExt/n:xEstProvReg'], ['n:enderNac/n:UF']) ?: $municipioDominio['uf'],
            'pais' => $this->nomePais($valor(['n:end/n:endExt/n:cPais'])),
            'cep' => $valor(['n:end/n:endNac/n:CEP', 'n:end/n:endExt/n:cEndPost'], ['n:enderNac/n:CEP']),
            'logradouro' => $valor(['n:end/n:xLgr'], ['n:enderNac/n:xLgr']),
            'numero' => $valor(['n:end/n:nro'], ['n:enderNac/n:nro']),
            'complemento' => $valor(['n:end/n:xCpl'], ['n:enderNac/n:xCpl']),
            'bairro' => $valor(['n:end/n:xBairro'], ['n:enderNac/n:xBairro']),
            'email' => $valor(['n:email']),
            'simplesNacionalCodigo' => $this->v($base, 'n:regTrib/n:opSimpNac'),
            'simplesNacional' => $this->enumDescricao(OptanteSimplesNacional::class, $this->v($base, 'n:regTrib/n:opSimpNac')),
            'regimeSimplesCodigo' => $this->v($base, 'n:regTrib/n:regApTribSN'),
            'regimeSimples' => $this->enumDescricao(RegimeTributacaoSimplesNacional::class, $this->v($base, 'n:regTrib/n:regApTribSN')),
        ];
    }

    private function servico(): array
    {
        $codigoLocal = $this->v($this->infDps, 'n:serv/n:locPrest/n:cLocPrestacao');
        $municipio = $this->localidades->municipio($codigoLocal);
        $codigoPais = $this->v($this->infDps, 'n:serv/n:locPrest/n:cPaisPrestacao');
        return [
            'codigoTributacaoNacional' => $this->v($this->infDps, 'n:serv/n:cServ/n:cTribNac'),
            'codigoTributacaoMunicipal' => $this->v($this->infDps, 'n:serv/n:cServ/n:cTribMun'),
            'descricaoTributacao' => $this->primeiro($this->infNfse, ['n:xTribNac', 'n:xTribMun']),
            'codigoNbs' => $this->v($this->infDps, 'n:serv/n:cServ/n:cNBS'),
            'descricaoNbs' => $this->v($this->infNfse, 'n:xNBS'),
            'codigoLocalPrestacao' => $codigoLocal,
            'localPrestacao' => $this->v($this->infNfse, 'n:xLocPrestacao') ?: $municipio['nome'],
            'ufPrestacao' => $municipio['uf'],
            'paisPrestacao' => $this->nomePais($codigoPais),
            'descricao' => $this->v($this->infDps, 'n:serv/n:cServ/n:xDescServ'),
        ];
    }

    private function issqn(): array
    {
        $trib = $this->v($this->infDps, 'n:valores/n:trib/n:tribMun/n:tribISSQN');
        $ret = $this->v($this->infDps, 'n:valores/n:trib/n:tribMun/n:tpRetISSQN');

        $codigoMunicipio = $this->v($this->infNfse, 'n:cLocIncid');
        $municipio = $this->localidades->municipio($codigoMunicipio);
        $codigoPais = $this->v($this->infDps, 'n:valores/n:trib/n:tribMun/n:cPaisResult');
        return [
            'tipoTributacaoCodigo' => $trib,
            'tipoTributacao' => $this->enumDescricao(TributacaoIssqn::class, $trib),
            'codigoMunicipioIncidencia' => $codigoMunicipio,
            'municipioIncidencia' => $this->v($this->infNfse, 'n:xLocIncid') ?: $municipio['nome'],
            'ufIncidencia' => $municipio['uf'],
            'paisIncidencia' => $this->nomePais($codigoPais),
            'regimeEspecial' => $this->v($this->infDps, 'n:prest/n:regTrib/n:regEspTrib'),
            'imunidade' => $this->v($this->infDps, 'n:valores/n:trib/n:tribMun/n:tpImunidade'),
            'suspensao' => $this->v($this->infDps, 'n:valores/n:trib/n:tribMun/n:exigSusp/n:tpSusp'),
            'processoSuspensao' => $this->v($this->infDps, 'n:valores/n:trib/n:tribMun/n:exigSusp/n:nProcesso'),
            'beneficioMunicipal' => $this->v($this->infDps, 'n:valores/n:trib/n:tribMun/n:BM/n:tpBM'),
            'calculoBeneficio' => $this->primeiro($this->infNfse, ['n:valores/n:vCalcBM', 'n:valores/n:vRedBCBM']),
            'deducoesReducoes' => $this->v($this->infNfse, 'n:valores/n:vTotalDedRed'),
            'descontoIncondicionado' => $this->v($this->infDps, 'n:valores/n:vDescCondIncond/n:vDescIncond'),
            'baseCalculo' => $this->v($this->infNfse, 'n:valores/n:vBC'),
            'aliquota' => $this->v($this->infNfse, 'n:valores/n:pAliqAplic'),
            'retencaoCodigo' => $ret,
            'retencao' => $this->enumDescricao(TipoRetencaoIssqn::class, $ret),
            'valor' => $this->v($this->infNfse, 'n:valores/n:vISSQN'),
        ];
    }

    private function tributosFederais(): array
    {
        $base = 'n:valores/n:trib/n:tribFed/';
        $tpRet = $this->v($this->infDps, $base . 'n:piscofins/n:tpRetPisCofins');
        $pis = $this->v($this->infDps, $base . 'n:piscofins/n:vPis');
        $cofins = $this->v($this->infDps, $base . 'n:piscofins/n:vCofins');
        $csll = $this->v($this->infDps, $base . 'n:vRetCSLL');

        return [
            'irrf' => $this->v($this->infDps, $base . 'n:vRetIRRF'),
            'previdenciaria' => $this->v($this->infDps, $base . 'n:vRetCP'),
            'contribuicoesSociais' => $this->somar([$csll, $tpRet === '1' ? $pis : '', $tpRet === '1' ? $cofins : '']),
            'pis' => $tpRet === '1' ? '0.00' : $pis,
            'cofins' => $tpRet === '1' ? '0.00' : $cofins,
            'tipoRetencaoPisCofinsCodigo' => $tpRet,
            'tipoRetencaoPisCofins' => $this->descricaoRetencaoPisCofins($tpRet),
        ];
    }

    private function ibsCbs(): array
    {
        $dps = 'n:IBSCBS/';
        $nfse = 'n:IBSCBS/';

        $codigoLocal = $this->v($this->infNfse, $nfse . 'n:cLocalidadeIncid');
        $municipio = $this->localidades->municipio($codigoLocal);
        return [
            'presente' => $this->xpath->query('n:IBSCBS', $this->infNfse)->length > 0 || $this->xpath->query('n:IBSCBS', $this->infDps)->length > 0,
            'cst' => $this->v($this->infDps, $dps . 'n:valores/n:trib/n:gIBSCBS/n:CST'),
            'classificacaoTributaria' => $this->v($this->infDps, $dps . 'n:valores/n:trib/n:gIBSCBS/n:cClassTrib'),
            'indicadorOperacao' => $this->primeiro($this->infDps, [$dps . 'n:cIndOp', $dps . 'n:valores/n:trib/n:gIBSCBS/n:cIndOp']),
            'codigoLocalIncidencia' => $codigoLocal,
            'localIncidencia' => $this->v($this->infNfse, $nfse . 'n:xLocalidadeIncid') ?: $municipio['nome'],
            'ufIncidencia' => $municipio['uf'],
            'exclusoesReducoes' => $this->somar([
                $this->v($this->infDps, 'n:valores/n:vDescCondIncond/n:vDescIncond'),
                $this->v($this->infNfse, $nfse . 'n:valores/n:vCalcReeRepRes'),
                $this->v($this->infNfse, 'n:valores/n:vISSQN'),
                $this->v($this->infDps, 'n:valores/n:trib/n:tribFed/n:piscofins/n:vPis'),
                $this->v($this->infDps, 'n:valores/n:trib/n:tribFed/n:piscofins/n:vCofins'),
            ]),
            'baseCalculo' => $this->v($this->infNfse, $nfse . 'n:valores/n:vBC'),
            'reducaoAliquotaUf' => $this->v($this->infNfse, $nfse . 'n:valores/n:uf/n:pRedAliqUF'),
            'reducaoAliquotaMunicipal' => $this->v($this->infNfse, $nfse . 'n:valores/n:mun/n:pRedAliqMun'),
            'reducaoAliquotaCbs' => $this->v($this->infNfse, $nfse . 'n:valores/n:fed/n:pRedAliqCBS'),
            'aliquotaUf' => $this->v($this->infNfse, $nfse . 'n:valores/n:uf/n:pIBSUF'),
            'aliquotaMunicipal' => $this->v($this->infNfse, $nfse . 'n:valores/n:mun/n:pIBSMun'),
            'aliquotaEfetivaMunicipal' => $this->v($this->infNfse, $nfse . 'n:valores/n:mun/n:pAliqEfetMun'),
            'valorMunicipal' => $this->v($this->infNfse, $nfse . 'n:totCIBS/n:gIBS/n:gIBSMunTot/n:vIBSMun'),
            'aliquotaEfetivaUf' => $this->v($this->infNfse, $nfse . 'n:valores/n:uf/n:pAliqEfetUF'),
            'valorUf' => $this->v($this->infNfse, $nfse . 'n:totCIBS/n:gIBS/n:gIBSUFTot/n:vIBSUF'),
            'valorIbs' => $this->v($this->infNfse, $nfse . 'n:totCIBS/n:gIBS/n:vIBSTot'),
            'aliquotaCbs' => $this->v($this->infNfse, $nfse . 'n:valores/n:fed/n:pCBS'),
            'aliquotaEfetivaCbs' => $this->v($this->infNfse, $nfse . 'n:valores/n:fed/n:pAliqEfetCBS'),
            'valorCbs' => $this->v($this->infNfse, $nfse . 'n:totCIBS/n:gCBS/n:vCBS'),
        ];
    }

    private function totais(): array
    {
        $ibs = $this->v($this->infNfse, 'n:IBSCBS/n:totCIBS/n:gIBS/n:vIBSTot');
        $cbs = $this->v($this->infNfse, 'n:IBSCBS/n:totCIBS/n:gCBS/n:vCBS');

        return [
            'servico' => $this->v($this->infDps, 'n:valores/n:vServPrest/n:vServ'),
            'descontoIncondicionado' => $this->v($this->infDps, 'n:valores/n:vDescCondIncond/n:vDescIncond'),
            'descontoCondicionado' => $this->v($this->infDps, 'n:valores/n:vDescCondIncond/n:vDescCond'),
            'retencoes' => $this->v($this->infNfse, 'n:valores/n:vTotalRet'),
            'liquido' => $this->v($this->infNfse, 'n:valores/n:vLiq'),
            'ibsCbs' => $this->somar([$ibs, $cbs]),
            'liquidoComIbsCbs' => $this->v($this->infNfse, 'n:IBSCBS/n:totCIBS/n:vTotNF'),
        ];
    }

    private function informacoesComplementares(): array
    {
        return [
            'informacao' => $this->v($this->infDps, 'n:serv/n:infoCompl/n:xInfComp'),
            'nfseSubstituida' => $this->v($this->infDps, 'n:subst/n:chSubstda'),
            'documentoReferenciado' => $this->v($this->infDps, 'n:serv/n:infoCompl/n:docRef'),
            'codigoObra' => $this->v($this->infDps, 'n:serv/n:obra/n:cObra'),
            'inscricaoImobiliaria' => $this->v($this->infDps, 'n:IBSCBS/n:imovel/n:inscImobFisc'),
            'codigoEvento' => $this->v($this->infDps, 'n:serv/n:atvEvento/n:idAtvEvt'),
            'documentoTecnico' => $this->v($this->infDps, 'n:serv/n:infoCompl/n:idDocTec'),
            'numeroPedido' => $this->v($this->infDps, 'n:serv/n:infoCompl/n:xPed'),
            'itemPedido' => $this->v($this->infDps, 'n:serv/n:infoCompl/n:gItemPed/n:xItemPed'),
            'administracaoTributaria' => $this->v($this->infNfse, 'n:xOutInf'),
            'tributosValorFederal' => $this->v($this->infDps, 'n:valores/n:trib/n:totTrib/n:vTotTrib/n:vTotTribFed'),
            'tributosValorEstadual' => $this->v($this->infDps, 'n:valores/n:trib/n:totTrib/n:vTotTrib/n:vTotTribEst'),
            'tributosValorMunicipal' => $this->v($this->infDps, 'n:valores/n:trib/n:totTrib/n:vTotTrib/n:vTotTribMun'),
            'tributosPercentualFederal' => $this->v($this->infDps, 'n:valores/n:trib/n:totTrib/n:pTotTrib/n:pTotTribFed'),
            'tributosPercentualEstadual' => $this->v($this->infDps, 'n:valores/n:trib/n:totTrib/n:pTotTrib/n:pTotTribEst'),
            'tributosPercentualMunicipal' => $this->v($this->infDps, 'n:valores/n:trib/n:totTrib/n:pTotTrib/n:pTotTribMun'),
        ];
    }

    private function v(DOMNode $contexto, string $caminho): string
    {
        return trim((string) $this->xpath->evaluate('string(' . $caminho . ')', $contexto));
    }

    private function primeiro(DOMNode $contexto, array $caminhos): string
    {
        foreach ($caminhos as $caminho) {
            $valor = $this->v($contexto, $caminho);
            if ($valor !== '') {
                return $valor;
            }
        }
        return '';
    }

    private function somar(array $valores): string
    {
        $preenchido = false;
        $total = 0.0;
        foreach ($valores as $valor) {
            if ($valor !== '') {
                $preenchido = true;
                $total += (float) $valor;
            }
        }
        return $preenchido ? number_format($total, 2, '.', '') : '';
    }

    private function enumDescricao(string $enum, string $codigo): string
    {
        if ($codigo === '' || !is_numeric($codigo)) {
            return '';
        }
        return $enum::tryFrom((int) $codigo)?->descricao() ?? $codigo;
    }

    private function descricaoRetencaoPisCofins(string $codigo): string
    {
        return [
            '1' => 'PIS/COFINS/CSLL Retido',
            '2' => 'PIS/COFINS Retido e CSLL Não Retido',
            '3' => 'PIS/CSLL Retido e COFINS Não Retido',
            '4' => 'COFINS/CSLL Retido e PIS Não Retido',
            '5' => 'PIS Retido e COFINS/CSLL Não Retido',
            '6' => 'COFINS Retido e PIS/CSLL Não Retido',
            '7' => 'CSLL Retido e PIS/COFINS Não Retido',
            '8' => 'PIS/COFINS/CSLL Não Retido',
            '9' => 'Não informado',
        ][$codigo] ?? $codigo;
    }

    private function nomePais(string $codigo): string
    {
        if ($codigo === '') {
            return '';
        }
        $nome = $this->localidades->pais($codigo);
        return $nome === '' ? $codigo : $nome;
    }
}
