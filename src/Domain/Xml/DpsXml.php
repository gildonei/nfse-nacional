<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Xml;

use DOMDocument;
use DOMElement;
use InvalidArgumentException;
use NfseNacional\Domain\Contract\DpsInterface;
use NfseNacional\Domain\Entity\Dps;
use NfseNacional\Domain\Entity\Emitente;
use NfseNacional\Domain\Enum\AmbienteGeradorNfse;
use NfseNacional\Domain\Enum\ProcessoEmissao;
use NfseNacional\Domain\Enum\SituacoesPossiveisNfse;
use NfseNacional\Domain\Enum\TipoBeneficioMunicipal;
use NfseNacional\Domain\Enum\TipoEmitente;
use NfseNacional\Domain\Enum\TipoEmissaoNfse;
use NfseNacional\Domain\ValueObject\Cpf;
use NfseNacional\Domain\ValueObject\Cnpj;

/**
 * Classe responsável por gerar XML a partir da entidade DPS
 *
 * @package NfseNacional\Domain\Xml
 */
class DpsXml implements DpsInterface
{
    private const DPS_NAMESPACE = 'http://www.sped.fazenda.gov.br/nfse';
    private const DPS_VERSION = '1.00';
    private const SIGNATURE_NAMESPACE = 'http://www.w3.org/2000/09/xmldsig#';

    /**
     * Entidade DPS
     * @var Dps
     */
    private Dps $dps;

    /**
     * Emitente (opcional)
     * @var Emitente|null
     */
    private ?Emitente $emitente = null;

    /**
     * Número da NFSe
     * @var int|null
     */
    private ?int $nNFSe = null;

    /**
     * Descrição do código da NBS
     * @var string|null
     */
    private ?string $xNBS = null;

    /**
     * Processo de emissão
     * @var ProcessoEmissao|null
     */
    private ?ProcessoEmissao $processoEmissao = null;

    /**
     * Tipo de emissão NFS-e
     * @var TipoEmissaoNfse|null
     */
    private ?TipoEmissaoNfse $tipoEmissaoNfse = null;

    /**
     * Ambiente gerador NFS-e
     * @var AmbienteGeradorNfse|null
     */
    private ?AmbienteGeradorNfse $ambienteGeradorNfse = null;

    /**
     * Tipo de benefício municipal
     * @var TipoBeneficioMunicipal|null
     */
    private ?TipoBeneficioMunicipal $tipoBeneficioMunicipal = null;

    /**
     * Situação possível da NFS-e
     * @var SituacoesPossiveisNfse|null
     */
    private ?SituacoesPossiveisNfse $situacaoPossivelNfse = null;

    /**
     * Documento XML
     * @var DOMDocument
     */
    private DOMDocument $dom;

    /**
     * Construtor
     *
     * @param Dps $dps
     * @param Emitente|null $emitente
     * @param int|null $nNFSe Número da NFSe
     * @param string|null $xNBS Descrição do código da NBS (máximo 600 caracteres)
     * @param ProcessoEmissao|null $processoEmissao Processo de emissão
     * @param TipoEmissaoNfse|null $tipoEmissaoNfse Tipo de emissão NFS-e
     * @param AmbienteGeradorNfse|null $ambienteGeradorNfse Ambiente gerador NFS-e
     * @param SituacoesPossiveisNfse|null $situacaoPossivelNfse Situação possível da NFS-e
     */
    public function __construct(
        Dps $dps,
        ?Emitente $emitente = null,
        ?int $nNFSe = null,
        ?string $xNBS = null,
        ?ProcessoEmissao $processoEmissao = null,
        ?TipoEmissaoNfse $tipoEmissaoNfse = null,
        ?AmbienteGeradorNfse $ambienteGeradorNfse = null,
        ?SituacoesPossiveisNfse $situacaoPossivelNfse = null
    ) {
        $this->dps = $dps;
        $this->emitente = $emitente;
        $this->nNFSe = $nNFSe;
        if ($xNBS !== null) {
            $this->definirXNBS($xNBS);
        }
        $this->processoEmissao = $processoEmissao ?? ProcessoEmissao::AplicativoContribuinte;
        $this->tipoEmissaoNfse = $tipoEmissaoNfse ?? TipoEmissaoNfse::EmissaoNormal;
        $this->ambienteGeradorNfse = $ambienteGeradorNfse ?? AmbienteGeradorNfse::SefinNacionalNfse;
        $this->situacaoPossivelNfse = $situacaoPossivelNfse;
        $this->dom = new DOMDocument('1.0', 'utf-8');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = false;
    }

    /**
     * Define o número da NFSe
     *
     * @param int $nNFSe
     * @return self
     */
    public function definirNNFSe(int $nNFSe): self
    {
        $this->nNFSe = $nNFSe;
        return $this;
    }

    /**
     * Retorna o número da NFSe
     *
     * @return int|null
     */
    public function obterNNFSe(): ?int
    {
        return $this->nNFSe;
    }

    /**
     * Define a descrição do código da NBS
     *
     * @param string $xNBS Descrição do código da NBS (máximo 600 caracteres)
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirXNBS(string $xNBS): self
    {
        $xNBS = trim($xNBS);

        if (strlen($xNBS) > 600) {
            throw new InvalidArgumentException('A descrição do código da NBS (xNBS) não pode exceder 600 caracteres!');
        }

        $this->xNBS = $xNBS;
        return $this;
    }

    /**
     * Retorna a descrição do código da NBS
     *
     * @return string|null
     */
    public function obterXNBS(): ?string
    {
        return $this->xNBS;
    }

    /**
     * Define o processo de emissão
     *
     * @param ProcessoEmissao $processoEmissao
     * @return self
     */
    public function definirProcessoEmissao(ProcessoEmissao $processoEmissao): self
    {
        $this->processoEmissao = $processoEmissao;
        return $this;
    }

    /**
     * Retorna o processo de emissão
     *
     * @return ProcessoEmissao|null
     */
    public function obterProcessoEmissao(): ?ProcessoEmissao
    {
        return $this->processoEmissao;
    }

    /**
     * Define o tipo de emissão NFS-e
     *
     * @param TipoEmissaoNfse $tipoEmissaoNfse
     * @return self
     */
    public function definirTipoEmissaoNfse(TipoEmissaoNfse $tipoEmissaoNfse): self
    {
        $this->tipoEmissaoNfse = $tipoEmissaoNfse;
        return $this;
    }

    /**
     * Retorna o tipo de emissão NFS-e
     *
     * @return TipoEmissaoNfse|null
     */
    public function obterTipoEmissaoNfse(): ?TipoEmissaoNfse
    {
        return $this->tipoEmissaoNfse;
    }

    /**
     * Define o ambiente gerador NFS-e
     *
     * @param AmbienteGeradorNfse $ambienteGeradorNfse
     * @return self
     */
    public function definirAmbienteGeradorNfse(AmbienteGeradorNfse $ambienteGeradorNfse): self
    {
        $this->ambienteGeradorNfse = $ambienteGeradorNfse;
        return $this;
    }

    /**
     * Retorna o ambiente gerador NFS-e
     *
     * @return AmbienteGeradorNfse|null
     */
    public function obterAmbienteGeradorNfse(): ?AmbienteGeradorNfse
    {
        return $this->ambienteGeradorNfse;
    }

    /**
     * Define o tipo de benefício municipal
     *
     * @param TipoBeneficioMunicipal $tipoBeneficioMunicipal
     * @return self
     */
    public function definirTipoBeneficioMunicipal(TipoBeneficioMunicipal $tipoBeneficioMunicipal): self
    {
        $this->tipoBeneficioMunicipal = $tipoBeneficioMunicipal;
        return $this;
    }

    /**
     * Retorna o tipo de benefício municipal
     *
     * @return TipoBeneficioMunicipal|null
     */
    public function obterTipoBeneficioMunicipal(): ?TipoBeneficioMunicipal
    {
        return $this->tipoBeneficioMunicipal;
    }

    /**
     * Define a situação possível da NFS-e
     *
     * @param SituacoesPossiveisNfse $situacaoPossivelNfse
     * @return self
     */
    public function definirSituacaoPossivelNfse(SituacoesPossiveisNfse $situacaoPossivelNfse): self
    {
        $this->situacaoPossivelNfse = $situacaoPossivelNfse;
        return $this;
    }

    /**
     * Retorna a situação possível da NFS-e
     *
     * @return SituacoesPossiveisNfse|null
     */
    public function obterSituacaoPossivelNfse(): ?SituacoesPossiveisNfse
    {
        return $this->situacaoPossivelNfse;
    }

    /**
     * Retorna o emitente
     *
     * @return Emitente|null
     */
    public function obterEmitente(): ?Emitente
    {
        return $this->emitente;
    }

    /**
     * Retorna a entidade DPS
     *
     * @return Dps
     */
    public function obterDps(): Dps
    {
        return $this->dps;
    }

    /**
     * Converte os dados da DPS em XML
     *
     * @return string XML formatado
     * @throws InvalidArgumentException
     */
    public function render(): string
    {
        $this->dom = new DOMDocument('1.0', 'utf-8');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = false;

        // Elemento raiz NFSe
        $nfseElement = $this->dom->createElement('NFSe');
        $nfseElement->setAttribute('versao', self::DPS_VERSION);
        // $nfseElement->setAttribute('xmlns', self::DPS_NAMESPACE);

        // Elemento infNFSe
        $infNfseElement = $this->dom->createElement('infNFSe');
        $infNfseElement->setAttribute('Id', $this->gerarIdNfse());

        // Campos do infNFSe
        $this->adicionarCamposInfNfse($infNfseElement);

        // Elemento emit (emitente)
        $this->adicionarEmitente($infNfseElement);

        // Elemento valores (valores totais)
        $this->adicionarValoresTotais($infNfseElement);

        // Elemento DPS (estrutura atual)
        $dpsElement = $this->dom->createElement('DPS');
        $dpsElement->setAttribute('versao', self::DPS_VERSION);
        // $dpsElement->setAttribute('xmlns', self::DPS_NAMESPACE);

        $infDpsInner = $this->dom->createElement('infDPS');
        $infDpsInner->setAttribute('Id', $this->gerarId());

        // Campos obrigatórios do infDPS
        $this->addChild($infDpsInner, 'tpAmb', (string) $this->dps->obterTipoAmbiente(), true);
        $this->addChild($infDpsInner, 'dhEmi', $this->dps->obterDataHoraEmissao()?->format('Y-m-d\TH:i:sP'), true);
        $this->addChild($infDpsInner, 'verAplic', $this->dps->obterVersaoAplicacao(), true);
        $this->addChild($infDpsInner, 'serie', $this->dps->obterSerie(), true);
        $this->addChild($infDpsInner, 'nDPS', $this->dps->obterNumeroDps(), true);
        $this->addChild($infDpsInner, 'dCompet', $this->dps->obterDataCompetencia()->format('Y-m-d'), true);

        // tpEmit - Tipo de emitente (validado pelo enum)
        // Se não estiver definido, determina automaticamente pela comparação dos documentos
        $tipoEmitente = $this->dps->obterTipoEmitente();
        if ($tipoEmitente === null) {
            $tipoEmitente = $this->determinarTipoEmitente();
        }
        /** @var TipoEmitente $tipoEmitente */
        $this->addChild($infDpsInner, 'tpEmit', (string) $tipoEmitente->valor(), true);

        // Campos opcionais
        if ($this->dps->obterCodigoMotivoEmissaoTI() !== null) {
            $this->addChild($infDpsInner, 'cMotivoEmisTI', (string) $this->dps->obterCodigoMotivoEmissaoTI());
        }

        if ($this->dps->obterChaveNfseRejeitada() !== null) {
            $this->addChild($infDpsInner, 'chNFSeRej', $this->dps->obterChaveNfseRejeitada());
        }

        $this->addChild($infDpsInner, 'cLocEmi', $this->dps->obterCodigoLocalEmissao(), true);

        // Substituição
        if ($this->dps->obterChaveSubstituida() !== null) {
            $substInner = $this->dom->createElement('subst');
            $infDpsInner->appendChild($substInner);
            $this->addChild($substInner, 'chSubstda', $this->dps->obterChaveSubstituida(), true);
            $this->addChild($substInner, 'cMotivo', (string) $this->dps->obterCodigoMotivoSubstituicao(), true);
            $this->addChild($substInner, 'xMotivo', $this->dps->obterDescricaoMotivoSubstituicao(), true);
        }

        // Prestador
        $prestador = $this->dps->obterPrestador();
        if ($prestador->obterNome() !== null || $prestador->obterDocumento() !== null) {
            $prestInner = $this->dom->createElement('prest');
            $infDpsInner->appendChild($prestInner);

            $documento = $prestador->obterDocumento();
            if ($documento !== null) {
                if ($documento instanceof Cnpj) {
                    $this->addChild($prestInner, 'CNPJ', $documento->obterNumero(), true);
                } elseif ($documento instanceof Cpf) {
                    $this->addChild($prestInner, 'CPF', $documento->obterNumero(), true);
                }
            }

            if ($prestador->obterCmc() !== null) {
                $this->addChild($prestInner, 'IM', (string) $prestador->obterCmc());
            }

            if ($prestador->obterNome() !== null) {
                $this->addChild($prestInner, 'xNome', $prestador->obterNome());
            }

            // Endereço do prestador
            $endereco = $prestador->obterEndereco();
            if ($endereco->obterLogradouro() !== null) {
                $endInner = $this->dom->createElement('end');
                $prestInner->appendChild($endInner);

                if ($endereco->obterCodigoCidade() !== null && $endereco->obterCep() !== null) {
                    $endNacInner = $this->dom->createElement('endNac');
                    $endInner->appendChild($endNacInner);
                    $this->addChild($endNacInner, 'cMun', (string) $endereco->obterCodigoCidade(), true);
                    if ($endereco->obterEstado() !== null) {
                        $this->addChild($endNacInner, 'UF', $endereco->obterEstado(), true);
                    }
                    $this->addChild($endNacInner, 'CEP', $endereco->obterCep(), true);
                }

                $this->addChild($endInner, 'xLgr', $endereco->obterLogradouro(), true);
                $this->addChild($endInner, 'nro', $endereco->obterNumero() ?? '', true);

                if ($endereco->obterComplemento() !== null) {
                    $this->addChild($endInner, 'xCpl', $endereco->obterComplemento());
                }

                $this->addChild($endInner, 'xBairro', $endereco->obterBairro() ?? '', true);
            }

            // Telefone do prestador
            $telefone = $prestador->obterTelefone();
            if ($telefone->obterTelefone() !== '') {
                $this->addChild($prestInner, 'fone', $telefone->obterTelefone());
            }

            // Email do prestador
            $email = $prestador->obterEmail();
            if ($email !== null) {
                $this->addChild($prestInner, 'email', $email->obterEndereco());
            }

            // vincPrest - Vínculo entre partes (validado pelo enum)
            $vinculoEntrePartes = $this->dps->obterVinculoEntrePartes();
            if ($vinculoEntrePartes !== null) {
                $this->addChild($prestInner, 'vincPrest', (string) $vinculoEntrePartes->valor());
            }

            // Regime tributário
            $regTribInner = $this->dom->createElement('regTrib');
            $prestInner->appendChild($regTribInner);
            // opSimpNac - Optante Simples Nacional (validado pelo enum)
            $optanteSimplesNacional = $prestador->obterOptanteSimplesNacional();
            if ($optanteSimplesNacional === null) {
                throw new InvalidArgumentException('Optante Simples Nacional é obrigatório!');
            }
            /** @var \NfseNacional\Domain\Enum\OptanteSimplesNacional $optanteSimplesNacional */
            $this->addChild($regTribInner, 'opSimpNac', (string) $optanteSimplesNacional->valor(), true);

            // regApTribSN - Regime de apuração tributação simples nacional (validado pelo enum)
            $regimeTributacaoSimplesNacional = $prestador->obterRegimeTributacaoSimplesNacional();
            if ($regimeTributacaoSimplesNacional !== null) {
                $this->addChild($regTribInner, 'regApTribSN', (string) $regimeTributacaoSimplesNacional->valor());
            }

            $this->addChild($regTribInner, 'regEspTrib', (string) ($prestador->obterValorRegimeEspecialTributacao() ?? 0), true);
        }

        // Tomador
        $tomador = $this->dps->obterTomador();
        if ($tomador->obterNome() !== null || $tomador->obterDocumento() !== null) {
            $tomaInner = $this->dom->createElement('toma');
            $infDpsInner->appendChild($tomaInner);

            $documento = $tomador->obterDocumento();
            if ($documento !== null) {
                if ($documento instanceof Cnpj) {
                    $this->addChild($tomaInner, 'CNPJ', $documento->obterNumero(), true);
                } elseif ($documento instanceof Cpf) {
                    $this->addChild($tomaInner, 'CPF', $documento->obterNumero(), true);
                }
            }

            if ($tomador->obterCmc() !== null) {
                $this->addChild($tomaInner, 'IM', (string) $tomador->obterCmc());
            }

            $this->addChild($tomaInner, 'xNome', $tomador->obterNome() ?? '', true);

            // Endereço do tomador
            $endereco = $tomador->obterEndereco();
            if ($endereco->obterLogradouro() !== null) {
                $endInner = $this->dom->createElement('end');
                $tomaInner->appendChild($endInner);

                if ($endereco->obterCodigoCidade() !== null && $endereco->obterCep() !== null) {
                    $endNacInner = $this->dom->createElement('endNac');
                    $endInner->appendChild($endNacInner);
                    $this->addChild($endNacInner, 'cMun', (string) $endereco->obterCodigoCidade(), true);
                    if ($endereco->obterEstado() !== null) {
                        $this->addChild($endNacInner, 'UF', $endereco->obterEstado(), true);
                    }
                    $this->addChild($endNacInner, 'CEP', $endereco->obterCep(), true);
                }

                $this->addChild($endInner, 'xLgr', $endereco->obterLogradouro(), true);
                $this->addChild($endInner, 'nro', $endereco->obterNumero() ?? '', true);

                if ($endereco->obterComplemento() !== null) {
                    $this->addChild($endInner, 'xCpl', $endereco->obterComplemento());
                }

                $this->addChild($endInner, 'xBairro', $endereco->obterBairro() ?? '', true);
            }

            // Telefone do tomador
            $telefone = $tomador->obterTelefone();
            if ($telefone->obterTelefone() !== '') {
                $this->addChild($tomaInner, 'fone', $telefone->obterTelefone());
            }

            // Email do tomador
            $email = $tomador->obterEmail();
            if ($email !== null) {
                $this->addChild($tomaInner, 'email', $email->obterEndereco());
            }

            // cNaoNIF - Motivo de não informar NIF (validado pelo enum)
            $motivoNaoInformarNif = $tomador->obterMotivoNaoInformarNif();
            if ($motivoNaoInformarNif !== null) {
                /** @var \NfseNacional\Domain\Enum\MotivoNaoInformarNif $motivoNaoInformarNif */
                $this->addChild($tomaInner, 'cNaoNIF', (string) $motivoNaoInformarNif->valor());
            }
        }

        // Serviço
        $servInner = $this->dom->createElement('serv');
        $infDpsInner->appendChild($servInner);

        $locPrestInner = $this->dom->createElement('locPrest');
        $servInner->appendChild($locPrestInner);
        $this->addChild($locPrestInner, 'cLocPrestacao', $this->dps->obterCodigoLocalPrestacao(), true);

        if ($this->dps->obterCodigoPaisPrestacao() !== null) {
            $this->addChild($locPrestInner, 'cPaisPrestacao', (string) $this->dps->obterCodigoPaisPrestacao());
        }

        // mdPrestacao - Modo de prestação (validado pelo enum)
        $modoPrestacao = $this->dps->obterModoPrestacao();
        if ($modoPrestacao !== null) {
            /** @var \NfseNacional\Domain\Enum\ModoPrestacao $modoPrestacao */
            $this->addChild($locPrestInner, 'mdPrestacao', (string) $modoPrestacao->valor());
        }

        $cServInner = $this->dom->createElement('cServ');
        $servInner->appendChild($cServInner);
        $this->addChild($cServInner, 'cTribNac', $this->dps->obterCodigoTributacaoNacional(), true);

        if ($this->dps->obterCodigoTributacaoMunicipal() !== null) {
            $this->addChild($cServInner, 'cTribMun', $this->dps->obterCodigoTributacaoMunicipal());
        }

        $this->addChild($cServInner, 'xDescServ', $this->dps->obterDescricaoServico(), true);

        if ($this->dps->obterCodigoNbs() !== null) {
            $this->addChild($cServInner, 'cNBS', $this->dps->obterCodigoNbs());
        }

        if ($this->dps->obterCodigoInternoContribuinte() !== null) {
            $this->addChild($cServInner, 'cIntContrib', $this->dps->obterCodigoInternoContribuinte());
        }

        // Valores
        $valoresInner = $this->dom->createElement('valores');
        $infDpsInner->appendChild($valoresInner);
        $vServPrestInner = $this->dom->createElement('vServPrest');
        $valoresInner->appendChild($vServPrestInner);

        if ($this->dps->obterValorRecebido() !== null) {
            $this->addChild($vServPrestInner, 'vReceb', $this->formatarValor($this->dps->obterValorRecebido()));
        }

        $this->addChild($vServPrestInner, 'vServ', $this->formatarValor($this->dps->obterValorServico()), true);

        // Tributação
        $tribInner = $this->dom->createElement('trib');
        $valoresInner->appendChild($tribInner);

        $tribMunInner = $this->dom->createElement('tribMun');
        $tribInner->appendChild($tribMunInner);

        // tribISSQN - Tributação ISSQN (validado pelo enum)
        $tributacaoIssqn = $this->dps->obterTributacaoIssqn();
        if ($tributacaoIssqn !== null) {
            /** @var \NfseNacional\Domain\Enum\TributacaoIssqn $tributacaoIssqn */
            $this->addChild($tribMunInner, 'tribISSQN', (string) $tributacaoIssqn->valor(), true);
        } else {
            $this->addChild($tribMunInner, 'tribISSQN', '0', true);
        }

        if ($this->dps->obterTipoRetencaoIssqn() !== null) {
            $this->addChild($tribMunInner, 'tpRetISSQN', (string) $this->dps->obterTipoRetencaoIssqn());
        }

        if ($this->dps->obterPercentualAliquota() !== null) {
            $this->addChild($tribMunInner, 'pAliq', $this->formatarValor($this->dps->obterPercentualAliquota()));
        }

        // Totalização de Tributos
        $totTribInner = $this->dom->createElement('totTrib');
        $tribMunInner->appendChild($totTribInner);
        // indTotTrib: 0 = Não, 1 = Sim (indica se há totalização de tributos)
        // Por padrão, vamos usar 0 (não há totalização)
        $this->addChild($totTribInner, 'indTotTrib', '0', true);

        $dpsElement->appendChild($infDpsInner);
        $infNfseElement->appendChild($dpsElement);

        // Adiciona infNFSe ao NFSe
        $nfseElement->appendChild($infNfseElement);

        // Elemento Signature (estrutura básica - será assinado posteriormente)
        $this->adicionarSignature($nfseElement, $infNfseElement->getAttribute('Id'));

        $this->dom->appendChild($nfseElement);

        // Salva o XML garantindo encoding utf-8
        $xml = $this->dom->saveXML();

        // Garante que o XML está em utf-8
        if (!mb_check_encoding($xml, 'utf-8')) {
            $xml = mb_convert_encoding($xml, 'utf-8', 'auto');
        }

        return $xml;
    }

    /**
     * Adiciona os campos do infNFSe
     *
     * @param DOMElement $infNfseElement
     * @return void
     */
    private function adicionarCamposInfNfse(DOMElement $infNfseElement): void
    {
        $prestador = $this->dps->obterPrestador();
        $enderecoPrestador = $prestador->obterEndereco();

        // xLocEmi - Nome do local de emissão (pode ser obtido do endereço do prestador)
        if ($enderecoPrestador->obterCidade() !== null) {
            $this->addChild($infNfseElement, 'xLocEmi', $enderecoPrestador->obterCidade());
        }

        // xLocPrestacao - Nome do local de prestação
        if ($enderecoPrestador->obterCidade() !== null) {
            $this->addChild($infNfseElement, 'xLocPrestacao', $enderecoPrestador->obterCidade());
        }

        // nNFSe - Número da NFSe
        if ($this->nNFSe !== null) {
            $this->addChild($infNfseElement, 'nNFSe', (string) $this->nNFSe);
        }

        // cLocIncid - Código do local de incidência
        if ($enderecoPrestador->obterCodigoCidade() !== null) {
            $this->addChild($infNfseElement, 'cLocIncid', (string) $enderecoPrestador->obterCodigoCidade());
        }

        // xLocIncid - Nome do local de incidência
        if ($enderecoPrestador->obterCidade() !== null) {
            $this->addChild($infNfseElement, 'xLocIncid', $enderecoPrestador->obterCidade());
        }

        // xTribNac - Descrição da tributação nacional
        if ($this->dps->obterCodigoTributacaoNacional() !== null) {
            // Pode ser preenchido com a descrição do serviço ou deixado vazio
            $this->addChild($infNfseElement, 'xTribNac', $this->dps->obterDescricaoServico() ?? '');
        }

        // xNBS - Descrição do código da NBS
        if ($this->xNBS !== null) {
            $this->addChild($infNfseElement, 'xNBS', $this->xNBS);
        }

        // verAplic - Versão da aplicação
        if ($this->dps->obterVersaoAplicacao() !== null) {
            $this->addChild($infNfseElement, 'verAplic', $this->dps->obterVersaoAplicacao());
        }

        // ambGer - Ambiente gerador NFS-e
        if ($this->ambienteGeradorNfse !== null) {
            $this->addChild($infNfseElement, 'ambGer', (string) $this->ambienteGeradorNfse->valor(), true);
        }

        // tpEmis - Tipo de emissão NFS-e
        if ($this->tipoEmissaoNfse !== null) {
            $this->addChild($infNfseElement, 'tpEmis', (string) $this->tipoEmissaoNfse->valor(), true);
        }

        // procEmi - Processo de emissão
        if ($this->processoEmissao !== null) {
            $this->addChild($infNfseElement, 'procEmi', (string) $this->processoEmissao->valor(), true);
        }

        // cStat - Situação possível da NFS-e (validado pelo enum)
        if ($this->situacaoPossivelNfse !== null) {
            $this->addChild($infNfseElement, 'cStat', (string) $this->situacaoPossivelNfse->valor());
        }

        // dhProc - Data e hora do processamento (data/hora atual no timezone de Brasília)
        $dataHoraProcessamento = $this->dps->obterDataHoraEmissao();
        if ($dataHoraProcessamento === null) {
            $dataHoraProcessamento = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        } else {
            // Garantir que a data/hora está no timezone de Brasília
            if ($dataHoraProcessamento->getTimezone()->getName() !== 'America/Sao_Paulo') {
                $dataHoraProcessamento = new \DateTime(
                    $dataHoraProcessamento->format('Y-m-d H:i:s'),
                    new \DateTimeZone('America/Sao_Paulo')
                );
            }
        }
        $this->addChild($infNfseElement, 'dhProc', $dataHoraProcessamento->format('Y-m-d\TH:i:sP'));
    }

    /**
     * Adiciona o elemento emit (emitente)
     *
     * @param DOMElement $infNfseElement
     * @return void
     */
    private function adicionarEmitente(DOMElement $infNfseElement): void
    {
        $emitente = $this->emitente;

        // Se não houver emitente definido, usa o prestador como emitente
        if ($emitente === null) {
            $prestador = $this->dps->obterPrestador();
            if ($prestador->obterDocumento() === null) {
                return;
            }
        } else {
            $prestador = $emitente;
        }

        $emitInner = $this->dom->createElement('emit');
        $infNfseElement->appendChild($emitInner);

        $documento = $prestador->obterDocumento();
        if ($documento !== null) {
            if ($documento instanceof Cnpj) {
                $this->addChild($emitInner, 'CNPJ', $documento->obterNumero(), true);
            } elseif ($documento instanceof Cpf) {
                $this->addChild($emitInner, 'CPF', $documento->obterNumero(), true);
            }
        }

        if ($prestador->obterNome() !== null) {
            $this->addChild($emitInner, 'xNome', $prestador->obterNome(), true);
        }

        // Endereço do emitente
        $endereco = $prestador->obterEndereco();
        if ($endereco->obterLogradouro() !== null) {
            $endNacInner = $this->dom->createElement('enderNac');
            $emitInner->appendChild($endNacInner);

            $this->addChild($endNacInner, 'xLgr', $endereco->obterLogradouro(), true);
            $this->addChild($endNacInner, 'nro', $endereco->obterNumero() ?? '', true);

            if ($endereco->obterBairro() !== null) {
                $this->addChild($endNacInner, 'xBairro', $endereco->obterBairro(), true);
            }

            if ($endereco->obterCodigoCidade() !== null) {
                $this->addChild($endNacInner, 'cMun', (string) $endereco->obterCodigoCidade(), true);
            }

            if ($endereco->obterEstado() !== null) {
                $this->addChild($endNacInner, 'UF', $endereco->obterEstado(), true);
            }

            if ($endereco->obterCep() !== null) {
                $this->addChild($endNacInner, 'CEP', $endereco->obterCep(), true);
            }
        }

        // Telefone do emitente
        $telefone = $prestador->obterTelefone();
        if ($telefone->obterTelefone() !== '') {
            $this->addChild($emitInner, 'fone', $telefone->obterTelefone());
        }

        // Email do emitente
        $email = $prestador->obterEmail();
        if ($email !== null) {
            $this->addChild($emitInner, 'email', $email->obterEndereco());
        }
    }

    /**
     * Adiciona o elemento valores (valores totais)
     *
     * @param DOMElement $infNfseElement
     * @return void
     */
    private function adicionarValoresTotais(DOMElement $infNfseElement): void
    {
        $valoresInner = $this->dom->createElement('valores');
        $infNfseElement->appendChild($valoresInner);

        // vTotalRet - Valor total retido (padrão 0.00)
        $this->addChild($valoresInner, 'vTotalRet', '0.00', true);

        // vLiq - Valor líquido (valor do serviço menos retenções)
        $valorServico = $this->dps->obterValorServico() ?? 0.00;
        $this->addChild($valoresInner, 'vLiq', $this->formatarValor($valorServico), true);

        // tpBM - Tipo de benefício municipal (opcional, validado pelo enum)
        if ($this->tipoBeneficioMunicipal !== null) {
            $this->addChild($valoresInner, 'tpBM', (string) $this->tipoBeneficioMunicipal->valor());
        }
    }

    /**
     * Adiciona o elemento Signature (estrutura básica)
     *
     * @param DOMElement $nfseElement
     * @param string $idNfse
     * @return void
     */
    private function adicionarSignature(DOMElement $nfseElement, string $idNfse): void
    {
        // Cria o elemento Signature sem namespace prefixado para evitar conflitos
        $signatureElement = $this->dom->createElement('Signature');
        $signatureElement->setAttribute('xmlns', self::SIGNATURE_NAMESPACE);

        $signedInfoElement = $this->dom->createElement('SignedInfo');
        $signatureElement->appendChild($signedInfoElement);

        // CanonicalizationMethod
        $canonicalizationMethod = $this->dom->createElement('CanonicalizationMethod');
        $canonicalizationMethod->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
        $signedInfoElement->appendChild($canonicalizationMethod);

        // SignatureMethod
        $signatureMethod = $this->dom->createElement('SignatureMethod');
        $signatureMethod->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');
        $signedInfoElement->appendChild($signatureMethod);

        // Reference
        $referenceElement = $this->dom->createElement('Reference');
        $referenceElement->setAttribute('URI', '#' . $idNfse);
        $signedInfoElement->appendChild($referenceElement);

        // Transforms
        $transformsElement = $this->dom->createElement('Transforms');
        $referenceElement->appendChild($transformsElement);

        $transform1 = $this->dom->createElement('Transform');
        $transform1->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');
        $transformsElement->appendChild($transform1);

        $transform2 = $this->dom->createElement('Transform');
        $transform2->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
        $transformsElement->appendChild($transform2);

        // DigestMethod
        $digestMethod = $this->dom->createElement('DigestMethod');
        $digestMethod->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');
        $referenceElement->appendChild($digestMethod);

        // DigestValue (será preenchido durante a assinatura)
        $digestValue = $this->dom->createElement('DigestValue', '');
        $referenceElement->appendChild($digestValue);

        // SignatureValue (será preenchido durante a assinatura)
        $signatureValue = $this->dom->createElement('SignatureValue', '');
        $signatureElement->appendChild($signatureValue);

        // KeyInfo (será preenchido durante a assinatura)
        $keyInfo = $this->dom->createElement('KeyInfo');
        $signatureElement->appendChild($keyInfo);

        $x509Data = $this->dom->createElement('X509Data');
        $keyInfo->appendChild($x509Data);

        $x509Certificate = $this->dom->createElement('X509Certificate', '');
        $x509Data->appendChild($x509Certificate);

        $nfseElement->appendChild($signatureElement);
    }

    /**
     * Determina automaticamente o tipo de emitente baseado na comparação dos documentos
     *
     * Regras:
     * - Se documento do Emitente = documento do Prestador → TipoEmitente::Prestador (1)
     * - Se documento do Emitente = documento do Tomador → TipoEmitente::Tomador (2)
     * - Caso contrário → TipoEmitente::Intermediario (3)
     *
     * @return TipoEmitente
     * @throws InvalidArgumentException
     */
    private function determinarTipoEmitente(): TipoEmitente
    {
        // Verifica se o Emitente está definido
        if ($this->emitente === null) {
            throw new InvalidArgumentException('Emitente é obrigatório para determinar o tipo de emitente automaticamente!');
        }

        $documentoEmitente = $this->emitente->obterDocumento();
        if ($documentoEmitente === null) {
            throw new InvalidArgumentException('Documento do emitente é obrigatório!');
        }

        $numeroEmitente = $documentoEmitente->obterNumero();

        // Compara com o documento do Prestador
        $prestador = $this->dps->obterPrestador();
        if ($prestador !== null) {
            $documentoPrestador = $prestador->obterDocumento();
            if ($documentoPrestador !== null) {
                $numeroPrestador = $documentoPrestador->obterNumero();
                if ($numeroEmitente === $numeroPrestador) {
                    return TipoEmitente::Prestador;
                }
            }
        }

        // Compara com o documento do Tomador
        $tomador = $this->dps->obterTomador();
        if ($tomador !== null) {
            $documentoTomador = $tomador->obterDocumento();
            if ($documentoTomador !== null) {
                $numeroTomador = $documentoTomador->obterNumero();
                if ($numeroEmitente === $numeroTomador) {
                    return TipoEmitente::Tomador;
                }
            }
        }

        // Se não corresponde a nenhum, é Intermediário
        return TipoEmitente::Intermediario;
    }

    /**
     * Gera o ID da NFSe com 53 caracteres
     *
     * Estrutura: "NFS" + Cód.Mun. (7) + Amb.Ger. (1) + Tipo Inscrição (1) +
     * Inscrição Federal (14) + nNFSe (13) + AnoMes Emis. (4) +
     * nNFSe 9 dígitos (9) + DV (1) = 53 caracteres
     *
     * @return string
     * @throws InvalidArgumentException
     */
    private function gerarIdNfse(): string
    {
        // 1. "NFS" (3 caracteres)
        $id = 'NFS';

        // 2. Cód.Mun. (7 caracteres) - Código do município
        $codigoLocalEmissao = $this->dps->obterCodigoLocalEmissao() ?? '';
        if (empty($codigoLocalEmissao)) {
            throw new InvalidArgumentException('Código do local de emissão é obrigatório para gerar o ID da NFSe!');
        }
        $id .= str_pad(substr($codigoLocalEmissao, 0, 7), 7, '0', STR_PAD_LEFT);

        // 3. Amb.Ger. (1 caractere) - Ambiente gerador
        if ($this->ambienteGeradorNfse === null) {
            throw new InvalidArgumentException('Ambiente gerador é obrigatório para gerar o ID da NFSe!');
        }
        $id .= (string) $this->ambienteGeradorNfse->valor();

        // 4. Tipo de Inscrição Federal (1 caractere) - 1 para CPF, 2 para CNPJ
        $prestador = $this->dps->obterPrestador();
        $documento = $prestador->obterDocumento();

        if ($documento === null) {
            throw new InvalidArgumentException('Documento do prestador é obrigatório para gerar o ID da NFSe!');
        }

        if ($documento instanceof Cnpj) {
            $id .= '2';
            $inscricao = $documento->obterNumero();
        } elseif ($documento instanceof Cpf) {
            $id .= '1';
            $inscricao = $documento->obterNumero();
        } else {
            $id .= '1';
            $inscricao = '00000000000000';
        }

        // 5. Inscrição Federal (14 caracteres) - CPF completar com 000 à esquerda
        if ($documento instanceof Cpf) {
            // CPF tem 11 dígitos, completar com 000 à esquerda para 14
            $id .= str_pad($inscricao, 14, '0', STR_PAD_LEFT);
        } else {
            // CNPJ já tem 14 dígitos
            $id .= str_pad($inscricao, 14, '0', STR_PAD_LEFT);
        }

        // 6. nNFSe (13 caracteres) - Número da NFSe
        if ($this->nNFSe === null) {
            throw new InvalidArgumentException('Número da NFSe (nNFSe) é obrigatório para gerar o ID da NFSe!');
        }
        $id .= str_pad((string) $this->nNFSe, 13, '0', STR_PAD_LEFT);

        // 7. AnoMes Emis. (4 caracteres) - Ano e mês da emissão (YYYYMM)
        $dataHoraEmissao = $this->dps->obterDataHoraEmissao();
        if ($dataHoraEmissao === null) {
            throw new InvalidArgumentException('Data e hora de emissão é obrigatória para gerar o ID da NFSe!');
        }
        $id .= $dataHoraEmissao->format('Ym');

        // 8. Valor do node nNFSe com 9 dígitos com trailing de zeros a esquerda
        $id .= str_pad((string) $this->nNFSe, 9, '0', STR_PAD_LEFT);

        // 9. DV (1 caractere) - Dígito verificador (módulo 11)
        $digitoVerificador = $this->calcularDigitoVerificador($id);
        $id .= (string) $digitoVerificador;

        return $id;
    }

    /**
     * Calcula o dígito verificador usando módulo 11
     *
     * @param string $valor String numérica para calcular o dígito verificador
     * @return int Dígito verificador (0-9 ou X se resto for 10)
     */
    private function calcularDigitoVerificador(string $valor): int
    {
        $soma = 0;
        $peso = 2;
        $tamanho = strlen($valor);

        // Multiplica cada dígito pelo peso, começando da direita para a esquerda
        for ($i = $tamanho - 1; $i >= 0; $i--) {
            $soma += (int) $valor[$i] * $peso;
            $peso++;
            if ($peso > 9) {
                $peso = 2;
            }
        }

        $resto = $soma % 11;

        // Se o resto for 0 ou 1, o dígito verificador é 0
        // Caso contrário, é 11 - resto
        if ($resto < 2) {
            return 0;
        }

        return 11 - $resto;
    }

    /**
     * Gera o ID da DPS
     *
     * @return string
     */
    /**
     * Gera o ID do infDPS com 45 caracteres
     *
     * Estrutura: "DPS" + Cód.Mun. (7) + Tipo Inscrição (1) +
     * Inscrição Federal (14 - CPF completar com 000 à esquerda) +
     * Série DPS (5) + Núm. DPS (15) = 45 caracteres
     *
     * @return string
     * @throws InvalidArgumentException
     */
    private function gerarId(): string
    {
        // 1. "DPS" (3 caracteres)
        $id = 'DPS';

        // 2. Cód.Mun. (7 caracteres) - Código do município
        $codigoLocalEmissao = $this->dps->obterCodigoLocalEmissao() ?? '';
        if (empty($codigoLocalEmissao)) {
            throw new InvalidArgumentException('Código do local de emissão é obrigatório para gerar o ID da DPS!');
        }
        $id .= str_pad(substr($codigoLocalEmissao, 0, 7), 7, '0', STR_PAD_LEFT);

        // 3. Tipo de Inscrição Federal (1 caractere) - 1 para CPF, 2 para CNPJ
        $prestador = $this->dps->obterPrestador();
        $documento = $prestador->obterDocumento();

        if ($documento === null) {
            throw new InvalidArgumentException('Documento do prestador é obrigatório para gerar o ID da DPS!');
        }

        if ($documento instanceof Cnpj) {
            $id .= '2';
            $inscricao = $documento->obterNumero();
        } elseif ($documento instanceof Cpf) {
            $id .= '1';
            $inscricao = $documento->obterNumero();
        } else {
            $id .= '1';
            $inscricao = '00000000000000';
        }

        // 4. Inscrição Federal (14 caracteres) - CPF completar com 000 à esquerda
        if ($documento instanceof Cpf) {
            // CPF tem 11 dígitos, completar com 000 à esquerda para 14
            $id .= str_pad($inscricao, 14, '0', STR_PAD_LEFT);
        } else {
            // CNPJ já tem 14 dígitos
            $id .= str_pad($inscricao, 14, '0', STR_PAD_LEFT);
        }

        // 5. Série DPS (5 caracteres)
        $serie = $this->dps->obterSerie();
        if ($serie === null || $serie === '') {
            throw new InvalidArgumentException('Série da DPS é obrigatória para gerar o ID da DPS!');
        }
        $id .= str_pad($serie, 5, '0', STR_PAD_LEFT);

        // 6. Núm. DPS (15 caracteres)
        $numeroDps = $this->dps->obterNumeroDps();
        if ($numeroDps === null || $numeroDps === '') {
            throw new InvalidArgumentException('Número da DPS é obrigatório para gerar o ID da DPS!');
        }
        $id .= str_pad($numeroDps, 15, '0', STR_PAD_LEFT);

        return $id;
    }

    /**
     * Adiciona um elemento filho ao nó pai
     *
     * @param DOMElement $parent
     * @param string $name
     * @param string|null $value
     * @param bool $required
     * @return DOMElement|null
     * @throws InvalidArgumentException
     */
    private function addChild(DOMElement $parent, string $name, ?string $value, bool $required = false): ?DOMElement
    {
        if ($value === null || $value === '') {
            if ($required) {
                throw new InvalidArgumentException("O campo '{$name}' é obrigatório e não pode ser vazio.");
            }
            return null;
        }

        $element = $this->dom->createElement($name, $value);
        $parent->appendChild($element);

        return $element;
    }

    /**
     * Formata um valor numérico para string com 2 casas decimais
     *
     * @param float $valor
     * @return string
     */
    private function formatarValor(float $valor): string
    {
        return number_format($valor, 2, '.', '');
    }
}

