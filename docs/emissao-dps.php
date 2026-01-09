<?php

/**
 * Exemplo de Emissão de DPS (Documento de Prestação de Serviços)
 *
 * Este exemplo demonstra como criar e gerar o XML de uma DPS usando
 * as entidades e value objects da biblioteca NFS-e Nacional.
 *
 * @package NfseNacional\Docs
 */

require_once __DIR__ . '/../vendor/autoload.php';

use DateTime;
use DOMDocument;
use DOMXPath;
use Exception;
use InvalidArgumentException;
use NfseNacional\Domain\Entity\Dps;
use NfseNacional\Domain\Entity\Emitente;
use NfseNacional\Domain\Entity\Prestador;
use NfseNacional\Domain\Entity\Tomador;
use NfseNacional\Domain\Enum\AmbienteGeradorNfse;
use NfseNacional\Domain\Enum\ModoPrestacao;
use NfseNacional\Domain\Enum\MotivoNaoInformarNif;
use NfseNacional\Domain\Enum\OptanteSimplesNacional;
use NfseNacional\Domain\Enum\ProcessoEmissao;
use NfseNacional\Domain\Enum\RegimeEspecialTributacaoMunicipal;
use NfseNacional\Domain\Enum\RegimeTributacaoSimplesNacional;
use NfseNacional\Domain\Enum\SituacoesPossiveisNfse;
use NfseNacional\Domain\Enum\TipoBeneficioMunicipal;
use NfseNacional\Domain\Enum\TipoEmitente;
use NfseNacional\Domain\Enum\TipoEmissaoNfse;
use NfseNacional\Domain\Enum\TributacaoIssqn;
use NfseNacional\Domain\Enum\VinculoEntrePartes;
use NfseNacional\Domain\Factory\DocumentoFactory;
use NfseNacional\Domain\ValueObject\Cnpj;
use NfseNacional\Domain\ValueObject\Cpf;
use NfseNacional\Domain\ValueObject\Email;
use NfseNacional\Domain\ValueObject\Endereco;
use NfseNacional\Domain\ValueObject\Telefone;
use NfseNacional\Domain\Xml\DpsXml;
use NfseNacional\Application\Service\EnvioDpsService;
use NfseNacional\Infrastructure\Security\AssinadorXml;
use NfseNacional\Infrastructure\Http\HttpClient;

// ============================================
// CONFIGURAÇÕES BÁSICAS
// ============================================
$codigoMunicipio = 4205407; // Florianópolis/SC - Pode ser obtido no site do viaCep https://www.viacep.com.br
$numeroDps = '1';
$serie = '1';
$valorServico = 1000.00;
$valorRecebido = 1000.00;

// ============================================
// CERTIFICADO DIGITAL
// ============================================
$caminhoCertificado = '/var/www/nfsenacional/docs/certificado.pfx'; // Substitua pelo caminho real
$senhaCertificado = '12345678'; // Senha certificado
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Emissão de DPS - NFS-e Nacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        pre {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <h5>Exemplo de Emissão de DPS (Documento de Prestação de Serviços)</h5>
            </div>
        </div>

        <?php
        // ============================================
        // CRIAR EMITENTE
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">1. Criando Emitente</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $emitente = new Emitente(
                        nome: 'Empresa Emitente LTDA',
                        documento: new Cnpj('50600661000126'), // CNPJ do emitente
                        endereco: new Endereco(
                            bairro: 'Centro',
                            cep: '88010000',
                            estado: 'SC',
                            cidade: 'Florianópolis',
                            rua: 'Rua Principal',
                            numero: '123',
                            codigoCidade: $codigoMunicipio,
                            complemento: 'Sala 101'
                        ),
                        telefone: new Telefone(
                            codigoPais: 55,
                            codigoArea: 48,
                            numero: 33334444
                        ),
                        email: 'emitente@empresa.com.br',
                        certificado: $caminhoCertificado,
                        senhaCertificado: $senhaCertificado,
                        cmc: 654321 // Inscrição Municipal (opcional)
                    );
                    ?>
                    <p><strong>Nome:</strong> <?= htmlspecialchars($emitente->obterNome())?></p>
                    <p><strong>CNPJ:</strong> <?= htmlspecialchars($emitente->obterDocumento()?->obterFormatado() ?? '')?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($emitente->obterEmail()?->obterEndereco() ?? '')?></p>
                    <p><strong>Certificado:</strong> <?= htmlspecialchars($caminhoCertificado)?></p>
                    <div class="alert alert-success">✓ Emitente criado com sucesso!</div>
                    <div class="alert alert-info mt-2 mb-0">
                        <small><strong>Nota:</strong> Em produção, você deve fornecer um certificado digital ICP-Brasil válido (.pfx ou .p12) para assinar o XML da DPS.</small>
                    </div>
                <?php } catch (InvalidArgumentException $e) {?>
                <div class="alert alert-danger">
                    <strong>Erro de Validação:</strong> <?= htmlspecialchars($e->getMessage())?>
                </div>
                <?php }?>
            </div>
        </div>

        <?php
        // ============================================
        // CRIAR PRESTADOR DE SERVIÇOS
        // =========================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">2. Criando Prestador de Serviços</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $prestador = new Prestador(
                        nome: 'Empresa Prestadora do Serviço LTDA',
                        email: new Email('contato@prestadora.com.br'),
                        cmc: 654321, // Inscrição Municipal
                        aedf: 123456, // Autorização para Emissão de Nota Fiscal
                        documento: new Cnpj('50600661000126'), // CNPJ do prestador
                        endereco: new Endereco(
                            bairro: 'Centro',
                            cep: '88010000',
                            estado: 'SC',
                            cidade: 'Florianópolis',
                            rua: 'Rua Principal',
                            numero: '123',
                            codigoCidade: $codigoMunicipio,
                            complemento: 'Sala 101'
                        ),
                        telefone: new Telefone(
                            codigoPais: 55,
                            codigoArea: 48,
                            numero: 33334444
                        ),
                        optanteSimplesNacional: OptanteSimplesNacional::OptanteMEEPP, // 1 = Não Optante, 2 = Optante MEI, 3 = Optante ME/EPP
                        regimeEspecialTributacao: RegimeEspecialTributacaoMunicipal::Nenhum
                    );

                    // Definir regime de tributação simples nacional
                    $prestador->definirRegimeTributacaoSimplesNacional(
                        RegimeTributacaoSimplesNacional::RegimeApuracaoTributosFederaisSNISSQNNfse
                    );
                ?>
                <p><strong>Nome:</strong> <?= htmlspecialchars($prestador->obterNome())?></p>
                <p><strong>CNPJ:</strong> <?= htmlspecialchars($prestador->obterDocumento()?->obterFormatado() ?? '')?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($prestador->obterEmail()?->obterEndereco() ?? '')?></p>
                <?php if ($prestador->obterRegimeTributacaoSimplesNacional() !== null) :?>
                <p><strong>Regime de Tributação Simples Nacional:</strong> <?= htmlspecialchars($prestador->obterRegimeTributacaoSimplesNacional()->valor() . ' - ' . $prestador->obterRegimeTributacaoSimplesNacional()->descricao())?></p>
                <?php endif;?>
                <div class="alert alert-success mb-0">✓ Prestador criado com sucesso!</div>

                <?php } catch (InvalidArgumentException $e) {?>
                <div class="alert alert-danger mb-0">
                    <strong>Erro de Validação:</strong> <?= htmlspecialchars($e->getMessage())?>
                </div>
                <?php }?>
            </div>
        </div>

        <?php
        // ============================================
        // CRIAR TOMADOR DE SERVIÇOS
        // =========================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">3. Criando Tomador de Serviços</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $tomador = new Tomador(
                        nome: 'Cliente Recebedor do Serviço',
                        razaoSocial: 'Cliente Recebedor do Serviço LTDA',
                        email: new Email('cliente@recebedor.com.br'),
                        cmc: 123456, // Inscrição Municipal (opcional)
                        documento: new Cnpj('51877676000107'), // CNPJ ou CPF do tomador
                        endereco: new Endereco(
                            bairro: 'Centro',
                            cep: '88010000',
                            estado: 'SC',
                            cidade: 'Florianópolis',
                            rua: 'Avenida Secundária',
                            numero: '456',
                            codigoCidade: $codigoMunicipio,
                            complemento: 'Andar 2'
                        ),
                        dadosAdicionais: 'Informações adicionais sobre o tomador',
                        telefone: new Telefone(
                            codigoPais: 55,
                            codigoArea: 48,
                            numero: 99998888
                        )
                    );

                    // Definir motivo de não informar NIF (opcional)
                    // $tomador->definirMotivoNaoInformarNif(MotivoNaoInformarNif::DispensadoNIF);
                ?>
                <p><strong>Nome:</strong> <?= htmlspecialchars($tomador->obterNome())?></p>
                <p><strong>Razão Social:</strong> <?= htmlspecialchars($tomador->obterRazaoSocial() ?? '')?></p>
                <p><strong>CNPJ:</strong> <?= htmlspecialchars($tomador->obterDocumento()?->obterFormatado() ?? '')?></p>
                <div class="alert alert-success mb-0">✓ Tomador criado com sucesso!</div>

                <?php } catch (InvalidArgumentException $e) {?>
                <div class="alert alert-danger mb-0">
                    <strong>Erro de Validação:</strong> <?= htmlspecialchars($e->getMessage())?>
                </div>
                <?php }?>
            </div>
        </div>

        <?php
        // ============================================
        // CRIAR DPS
        // =========================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">4. Criando DPS</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $dps = new Dps();
                    $dps->definirPrestador($prestador)
                        ->definirTomador($tomador)
                        ->definirTipoAmbiente(2) // 1 = Produção, 2 = Homologação
                        ->definirDataHoraEmissao(new DateTime('now', new \DateTimeZone('America/Sao_Paulo')))
                        ->definirVersaoAplicacao('1.0')
                        ->definirSerie($serie)
                        ->definirNumeroDps($numeroDps)
                        ->definirDataCompetencia(new DateTime('now', new \DateTimeZone('America/Sao_Paulo')))
                        // Tipo de emitente será determinado automaticamente pela comparação dos documentos:
                        // - Se documento do Emitente = documento do Prestador → 1 (Prestador)
                        // - Se documento do Emitente = documento do Tomador → 2 (Tomador)
                        // - Caso contrário → 3 (Intermediário)
                        // ->definirTipoEmitente(TipoEmitente::Prestador) // Opcional: pode ser definido manualmente
                        ->definirCodigoLocalEmissao((string) $codigoMunicipio)
                        ->definirCodigoLocalPrestacao((string) $codigoMunicipio)
                        ->definirModoPrestacao(ModoPrestacao::ConsumoNoBrasil) // 0 = Desconhecido, 1 = Transfronteiriço, 2 = Consumo no Brasil, 3 = Presença Comercial no Exterior, 4 = Movimento Temporário de Pessoas Físicas
                        ->definirVinculoEntrePartes(VinculoEntrePartes::SemVinculo) // 0 = Sem vínculo, 1 = Controlada, 2 = Controladora, 3 = Coligada, 4 = Matriz, 5 = Filial ou sucursal, 6 = Outro vínculo
                        ->definirCodigoTributacaoNacional('1401') // Código de tributação nacional
                        ->definirDescricaoServico('Serviço de consultoria em tecnologia da informação')
                        ->definirValorServico($valorServico)
                        ->definirValorRecebido($valorRecebido)
                        ->definirTributacaoIssqn(TributacaoIssqn::OperacaoTributavel); // 1 = Operação tributável, 2 = Imunidade, 3 = Exportação de serviço, 4 = Não Incidência

                    // Campos opcionais
                    if ($dps->obterCodigoTributacaoMunicipal() === null) {
                        $dps->definirCodigoTributacaoMunicipal('1401');
                    }
                ?>

                <p><strong>Número DPS:</strong> <?= htmlspecialchars($dps->obterNumeroDps() ?? '')?></p>
                <p><strong>Série:</strong> <?= htmlspecialchars($dps->obterSerie() ?? '')?></p>
                <p><strong>Valor do Serviço:</strong> R$ <?= number_format($dps->obterValorServico() ?? 0, 2, ',', '.')?></p>

                <?php if ($dps->obterTributacaoIssqn() !== null) :?>
                <p><strong>Tributação ISSQN:</strong> <?= htmlspecialchars($dps->obterTributacaoIssqn()->valor() . ' - ' . $dps->obterTributacaoIssqn()->descricao())?></p>
                <?php endif;?>
                <div class="alert alert-success mb-0">✓ DPS criada com sucesso!</div>

                <?php } catch (InvalidArgumentException $e) {?>
                <div class="alert alert-danger mb-0">
                    <strong>Erro de Validação:</strong> <?= htmlspecialchars($e->getMessage())?>
                </div>
                <?php }?>
            </div>
        </div>

        <?php
        // ============================================
        // GERAR XML DA DPS
        // =========================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">5. Gerando XML da DPS</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    // Número da NFSe
                    $numeroNFSe = 5;

                    // Descrição do código da NBS (máximo 600 caracteres)
                    $descricaoNBS = 'Serviços de pesquisa e desenvolvimento em tecnologia da informação e comunicação (TIC)';

                    // Processo de emissão (padrão: AplicativoContribuinte = 1)
                    $processoEmissao = ProcessoEmissao::AplicativoContribuinte;

                    // Tipo de emissão NFS-e (padrão: EmissaoNormal = 1)
                    $tipoEmissao = TipoEmissaoNfse::EmissaoNormal;

                    // Ambiente gerador NFS-e (padrão: SefinNacionalNfse = 2)
                    $ambienteGerador = AmbienteGeradorNfse::SefinNacionalNfse;

                    // Tipo de benefício municipal (opcional)
                    $tipoBeneficioMunicipal = TipoBeneficioMunicipal::Isencao;

                    // Situação possível da NFS-e (padrão: NfseGerada = 100)
                    $situacaoPossivelNfse = SituacoesPossiveisNfse::NfseGerada;
                    ?>

                    <p><strong>Número NFSe:</strong> <?= htmlspecialchars((string) $numeroNFSe)?></p>
                    <p><strong>Processo de Emissão:</strong> <?= htmlspecialchars($processoEmissao->valor() . ' - ' . $processoEmissao->descricao())?></p>
                    <p><strong>Tipo de Emissão:</strong> <?= htmlspecialchars($tipoEmissao->valor() . ' - ' . $tipoEmissao->descricao())?></p>
                    <p><strong>Ambiente Gerador:</strong> <?= htmlspecialchars($ambienteGerador->valor() . ' - ' . $ambienteGerador->descricao())?></p>
                    <p><strong>Tipo de Benefício Municipal:</strong> <?= htmlspecialchars($tipoBeneficioMunicipal->valor() . ' - ' . $tipoBeneficioMunicipal->descricao())?></p>
                    <p><strong>Situação Possível da NFS-e:</strong> <?= htmlspecialchars($situacaoPossivelNfse->valor() . ' - ' . $situacaoPossivelNfse->descricao())?></p>

                    <?php
                    // Determina automaticamente o tipo de emitente
                    $tipoEmitenteDefinido = $dps->obterTipoEmitente();
                    if ($tipoEmitenteDefinido === null) :
                        $documentoEmitente = $emitente->obterDocumento()?->obterNumero();
                        $documentoPrestador = $prestador->obterDocumento()?->obterNumero();
                        $documentoTomador = $tomador->obterDocumento()?->obterNumero();

                        $tipoEmitenteAuto = null;
                        if ($documentoEmitente === $documentoPrestador) {
                            $tipoEmitenteAuto = TipoEmitente::Prestador;
                        } elseif ($documentoEmitente === $documentoTomador) {
                            $tipoEmitenteAuto = TipoEmitente::Tomador;
                        } else {
                            $tipoEmitenteAuto = TipoEmitente::Intermediario;
                        }
                    ?>
                        <p><strong>Tipo de Emitente (determinado automaticamente):</strong> <?= htmlspecialchars($tipoEmitenteAuto->valor() . ' - ' . $tipoEmitenteAuto->descricao())?></p>
                        <div class="alert alert-info mt-2">
                            <small>
                                <strong>Nota:</strong> O tipo de emitente foi determinado automaticamente pela comparação dos documentos.<br>
                                Se o documento do Emitente for igual ao do Prestador = Prestador (1),<br>
                                se for igual ao do Tomador = Tomador (2),<br>
                                caso contrário = Intermediário (3).
                            </small>
                        </div>
                    <?php else :?>
                        <p><strong>Tipo de Emitente (definido manualmente):</strong> <?= htmlspecialchars($tipoEmitenteDefinido->valor() . ' - ' . $tipoEmitenteDefinido->descricao())?></p>
                    <?php
                    endif;

                    $dpsXml = new DpsXml(
                        $dps,
                        $emitente,
                        $numeroNFSe,              // nNFSe
                        $descricaoNBS,            // xNBS
                        $processoEmissao,         // ProcessoEmissao
                        $tipoEmissao,             // TipoEmissaoNfse
                        $ambienteGerador,         // AmbienteGeradorNfse
                        $situacaoPossivelNfse     // SituacoesPossiveisNfse
                    );

                    // Definir tipo de benefício municipal (opcional)
                    $dpsXml->definirTipoBeneficioMunicipal($tipoBeneficioMunicipal);
                    $xmlString = $dpsXml->render();
                    ?>
                    <div class="alert alert-success">✓ XML gerado com sucesso!</div>
                    <h6>XML Gerado (antes da assinatura):</h6>
                    <pre><?= htmlspecialchars($xmlString)?></pre>

                    <?php
                } catch (Exception $e) {?>
                <div class="alert alert-danger mb-0">
                    <strong>Erro ao gerar XML:</strong> <?= htmlspecialchars($e->getMessage())?>
                </div>
                <?php
                }

                // ============================================
                // ASSINAR XML COM CERTIFICADO DIGITAL
                // ============================================
                ?>
                <h6 class="mt-4">6. Assinando XML com Certificado Digital</h6>

                <?php
                try {
                    // Usa a classe AssinadorXml para assinar o XML
                    $assinador = new AssinadorXml();
                    $xmlAssinado = $assinador->assinar($xmlString, $emitente, 'infNFSe', 'NFS');
                ?>
                    <div class="alert alert-success">✓ XML assinado com sucesso!</div>
                    <h6>XML Assinado:</h6>
                    <pre class="mb-0"><?= htmlspecialchars($xmlAssinado)?></pre>
                <?php
                } catch (Exception $e) {?>
                <div class="alert alert-warning mb-0">
                    <strong>Atenção:</strong> Não foi possível assinar o XML. <?= htmlspecialchars($e->getMessage())?>
                    <br><small>Isso é normal se o certificado não for válido ou não existir.</small>
                </div>
                <?php }?>
            </div>
        </div>

        <?php
        // ============================================
        // ENVIO DA DPS PARA A API
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">7. Envio da DPS para a API NFS-e Nacional</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    // Cria as instâncias dos serviços
                    $httpClient = new HttpClient();
                    $envioDpsService = new EnvioDpsService($assinador, $httpClient);

                    // Envia a DPS
                    // A URL é determinada automaticamente baseada no tipo de ambiente da DPS
                    // (1 = Produção, 2 = Homologação)
                    // O emitente é obtido automaticamente do DpsXml
                    $resposta = $envioDpsService->enviar($dpsXml);
                ?>
                    <h6>Resposta da API:</h6>
                    <pre><?= htmlspecialchars(json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))?></pre>

                    <?php if (isset($resposta['statusCode']) && $resposta['statusCode'] >= 200 && $resposta['statusCode'] < 300): ?>
                    <div class="alert alert-success mt-3">
                        <strong>✓ DPS enviada com sucesso!</strong><br>
                        <strong>Status:</strong> <?= htmlspecialchars((string) $resposta['statusCode'])?> - Requisição processada com sucesso
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning mt-3 mb-0">
                        <strong>Status:</strong> <?= htmlspecialchars((string) ($resposta['statusCode'] ?? 'Desconhecido'))?><br>
                        <strong>Resposta:</strong> Verifique os detalhes acima para identificar o problema.
                    </div>
                    <?php endif; ?>
                <?php
                } catch (Exception $e) {?>
                <div class="alert alert-warning mb-0">
                    <strong>Atenção:</strong> Não foi possível enviar a DPS. <?= htmlspecialchars($e->getMessage())?>
                    <br><small>Isso é normal se a URL da API não estiver configurada corretamente ou se o certificado não for válido.</small>
                </div>
                <?php }?>
            </div>
        </div>

        <?php
        // ============================================
        // EXEMPLO COM CPF (Tomador Pessoa Física)
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">8. Exemplo Alternativo: Tomador com CPF</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $tomadorPF = new Tomador(
                        nome: 'João da Silva',
                        email: new Email('joao@email.com'),
                        documento: DocumentoFactory::criar('10725920000120'),
                        endereco: new Endereco(
                            bairro: 'Centro',
                            cep: '88010000',
                            estado: 'SC',
                            cidade: 'Florianópolis',
                            rua: 'Rua Exemplo',
                            numero: '789',
                            codigoCidade: $codigoMunicipio
                        ),
                        telefone: new Telefone(55, 48, 88887777)
                    );
                    ?>
                    <p><strong>Nome:</strong> <?= htmlspecialchars($tomadorPF->obterNome())?></p>
                    <p><strong>CPF:</strong> <?= htmlspecialchars($tomadorPF->obterDocumento()?->obterFormatado() ?? '')?></p>
                    <div class="alert alert-success mb-0">✓ Exemplo de tomador pessoa física criado!</div>
                    <?php
                } catch (InvalidArgumentException $e) {?>
                <div class="alert alert-danger mb-0">
                    <strong>Erro de Validação:</strong> <?= htmlspecialchars($e->getMessage())?>
                </div>
                <?php }?>
            </div>
        </div>

        <?php
        // ============================================
        // EXEMPLO PROFISSIONAL AUTÔNOMO
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">9. Exemplo Profissional Autônomo</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    // Exemplo com Profissional Autônomo
                $prestadorPA = new Prestador(
                    nome: 'Profissional Autônomo',
                    documento: new Cpf('98765432100'),
                    regimeEspecialTributacao: RegimeEspecialTributacaoMunicipal::ProfissionalAutonomo
                );

                ?>
                <p><strong>Nome:</strong> <?= htmlspecialchars($tomadorPF->obterNome())?></p>
                <p><strong>CPF:</strong> <?= htmlspecialchars($tomadorPF->obterDocumento()?->obterFormatado() ?? '')?></p>
                <div class="alert alert-success mb-0">✓ Exemplo de prestador com regime Profissional Autônomo criado!</div>
                <?php
                } catch (InvalidArgumentException $e) {?>
                <div class="alert alert-danger mb-0">
                    <strong>Erro de Validação:</strong> <?= htmlspecialchars($e->getMessage())?>
                </div>
                <?php }?>
            </div>
        </div>

        <?php
        // ============================================
        // INFORMAÇÕES ADICIONAIS
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">10. Dados complementares</h5>
            </div>
            <div class="card-body">
                <h6>1. Exemplos de Regimes Especiais de Tributação Municipal</h6>
                <ul>
                    <?php foreach (RegimeEspecialTributacaoMunicipal::cases() as $regime) :?>
                    <li><?= htmlspecialchars($regime->valor() . ' - ' . $regime->descricao())?></li>
                    <?php endforeach;?>
                </ul>


                <h6>2. Exemplos de Processos de Emissão</h6>
                <ul>
                    <?php foreach (ProcessoEmissao::cases() as $processo) :?>
                    <li><?= htmlspecialchars($processo->valor() . ' - ' . $processo->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>3. Exemplos de Tipos de Emissão NFS-e</h6>
                <ul>
                    <?php foreach (TipoEmissaoNfse::cases() as $tipo) :?>
                    <li><?= htmlspecialchars($tipo->valor() . ' - ' . $tipo->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>4. Exemplos de Ambientes Geradores NFS-e</h6>
                <ul>
                    <?php foreach (AmbienteGeradorNfse::cases() as $ambiente) :?>
                    <li><?= htmlspecialchars($ambiente->valor() . ' - ' . $ambiente->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>5. Exemplos de Tipos de Benefício Municipal</h6>
                <ul>
                    <?php foreach (TipoBeneficioMunicipal::cases() as $tipo) :?>
                    <li><?= htmlspecialchars($tipo->valor() . ' - ' . $tipo->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>6. Exemplos de Tipos de Emitente</h6>
                <ul>
                    <?php foreach (TipoEmitente::cases() as $tipo) :?>
                    <li><?= htmlspecialchars($tipo->valor() . ' - ' . $tipo->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>7. Exemplos de Optante Simples Nacional</h6>
                <ul>
                    <?php foreach (OptanteSimplesNacional::cases() as $optante) :?>
                    <li><?= htmlspecialchars($optante->valor() . ' - ' . $optante->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>8. Exemplos de Regime de Tributação Simples Nacional</h6>
                <ul>
                    <?php foreach (RegimeTributacaoSimplesNacional::cases() as $regime) :?>
                    <li><?= htmlspecialchars($regime->valor() . ' - ' . $regime->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>9. Exemplos de Motivos de Não Informar NIF</h6>
                <ul>
                    <?php foreach (MotivoNaoInformarNif::cases() as $motivo) :?>
                    <li><?= htmlspecialchars($motivo->valor() . ' - ' . $motivo->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>10. Exemplos de Modos de Prestação</h6>
                <ul>
                    <?php foreach (ModoPrestacao::cases() as $modo) :?>
                    <li><?= htmlspecialchars($modo->valor() . ' - ' . $modo->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>11. Exemplos de Tributação ISSQN</h6>
                <ul>
                    <?php foreach (TributacaoIssqn::cases() as $tributacao) :?>
                    <li><?= htmlspecialchars($tributacao->valor() . ' - ' . $tributacao->descricao())?></li>
                    <?php endforeach;?>
                </ul>

                <h6>12. Exemplos de Situações Possíveis da NFS-e</h6>
                <ul>
                    <?php foreach (SituacoesPossiveisNfse::cases() as $situacao) :?>
                    <li><?= htmlspecialchars($situacao->valor() . ' - ' . $situacao->descricao())?></li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>

        <div class="card mt-3 mb-5">
            <div class="card-header">
                <h6>11. Informações Adicionais</h6>
            </div>
            <div class="card-body">
                <h6>Campos Obrigatórios da DPS:</h6>
                <ul>
                    <li>Emitente (nome, documento, endereço, telefone, e-mail e certificado)</li>
                    <li>Prestador (nome e documento)</li>
                    <li>Tomador (nome e documento)</li>
                    <li>Tipo de Ambiente (1 = Produção, 2 = Homologação)</li>
                    <li>Data e Hora de Emissão</li>
                    <li>Versão da Aplicação</li>
                    <li>Série</li>
                    <li>Número da DPS</li>
                    <li>Data de Competência</li>
                    <li>Tipo de Emitente</li>
                    <li>Código do Local de Emissão</li>
                    <li>Código do Local de Prestação</li>
                    <li>Código de Tributação Nacional</li>
                    <li>Descrição do Serviço</li>
                    <li>Valor do Serviço</li>
                    <li>Tributação ISSQN</li>
                    <li>Número da NFSe (nNFSe)</li>
                    <li>Descrição do código da NBS (xNBS - máximo 600 caracteres)</li>
                    <li>Processo de Emissão (enum ProcessoEmissao)</li>
                    <li>Tipo de Emissão NFS-e (enum TipoEmissaoNfse)</li>
                    <li>Ambiente Gerador NFS-e (enum AmbienteGeradorNfse)</li>
                </ul>

                <h6 class="mt-3">Validações Automáticas:</h6>
                <ul>
                    <li>CPF e CNPJ são validados automaticamente</li>
                    <li>Email é validado automaticamente</li>
                    <li>Telefone é validado automaticamente</li>
                    <li>Endereço com validação de estados brasileiros</li>
                    <li>Valores não podem ser negativos</li>
                </ul>

                <h6 class="mt-3">Próximos Passos:</h6>
                <ol class="mb-0">
                    <li>Assinar o XML com certificado digital ICP-Brasil</li>
                    <li>Enviar o XML assinado para a API da NFS-e Nacional</li>
                    <li>Processar a resposta e obter a chave de acesso da NFS-e</li>
                </ol>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
