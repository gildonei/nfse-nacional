<?php

/**
 * Exemplo de Consulta de NFS-e pela Chave de Acesso
 *
 * Este exemplo demonstra como consultar uma NFS-e usando a chave de acesso
 * através da API Sefin Nacional.
 *
 * @package NfseNacional\Docs
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Exception;
use NfseNacional\Domain\Entity\Emitente;
use NfseNacional\Domain\ValueObject\Certificado;
use NfseNacional\Domain\ValueObject\Cnpj;
use NfseNacional\Domain\ValueObject\Email;
use NfseNacional\Domain\ValueObject\Endereco;
use NfseNacional\Domain\ValueObject\Telefone;
use NfseNacional\Application\Service\SefinNacionalService;
use NfseNacional\Infrastructure\Security\AssinadorXml;
use DOMDocument;

// ============================================
// CONFIGURAÇÕES BÁSICAS
// ============================================
$chaveAcesso = '42054072241039410000123000000000000126019973355918';
$tipoAmbiente = SefinNacionalService::AMBIENTE_HOMOLOGACAO; // ou AMBIENTE_PRODUCAO
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consulta de NFS-e - NFS-e Nacional</title>
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
        .alert {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <h5>Exemplo de Consulta de NFS-e pela Chave de Acesso</h5>
            </div>
        </div>

        <?php
        // ============================================
        // CRIAR CERTIFICADO
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">1. Criando Certificado</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $caminhoCertificado = '/var/www/nfsenacional/docs/certificado.pfx'; // Substitua pelo caminho real
                    $senhaCertificado = '12345678'; // Substitua pela senha real
                    $certificado = new Certificado($caminhoCertificado, $senhaCertificado);
                ?>
                    <p><strong>Certificado:</strong> <?= htmlspecialchars($caminhoCertificado) ?></p>
                    <div class="alert alert-success">✓ Certificado criado com sucesso!</div>
                    <?php if ($certificado->validar()) : ?>
                        <div class="alert alert-success mb-0">✓ Certificado válido!</div>
                    <?php else : ?>
                        <div class="alert alert-danger mb-0">✗ Certificado inválido!</div>
                    <?php endif; ?>
                <?php
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Erro ao criar certificado: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    exit;
                }
                ?>
            </div>
        </div>

        <?php
        // ============================================
        // CRIAR EMITENTE
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">2. Criando Emitente</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $emitente = new Emitente(
                        nome: 'Empresa Emitente LTDA',
                        documento: new Cnpj('50600661000126'), // Substitua pelo CNPJ real
                        endereco: new Endereco(
                            logradouro: 'Rua Exemplo',
                            numero: '123',
                            bairro: 'Centro',
                            codigoCidade: 4205407, // Florianópolis/SC
                            estado: 'SC',
                            cep: '88010000'
                        ),
                        telefone: new Telefone(
                            codigoPais: 55,
                            codigoArea: 48,
                            numero: 33334444
                        ),
                        email: new Email('emitente@empresa.com.br'),
                        certificado: $certificado
                    );
                ?>
                    <p><strong>Nome:</strong> <?= htmlspecialchars($emitente->obterNome()) ?></p>
                    <p><strong>CNPJ:</strong> <?= htmlspecialchars($emitente->obterDocumento()->obterFormatado()) ?></p>
                    <p><strong>E-mail:</strong> <?= htmlspecialchars($emitente->obterEmail()->obterEndereco()) ?></p>
                    <div class="alert alert-success mb-0">✓ Emitente criado com sucesso!</div>
                <?php
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Erro ao criar emitente: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    exit;
                }
                ?>
            </div>
        </div>

        <?php
        // ============================================
        // CRIAR SERVIÇO SEFIN NACIONAL
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">3. Criando Serviço Sefin Nacional</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $assinador = new AssinadorXml();
                    $sefinService = new SefinNacionalService(
                        emitente: $emitente,
                        assinador: $assinador,
                        tipoAmbiente: $tipoAmbiente
                    );
                ?>
                    <p><strong>Ambiente:</strong> <?= $tipoAmbiente === SefinNacionalService::AMBIENTE_PRODUCAO ? 'Produção' : 'Homologação' ?></p>
                    <p><strong>URL Base:</strong> <?= htmlspecialchars($sefinService->obterUrlBase()) ?></p>
                    <div class="alert alert-success mb-0">✓ Serviço criado com sucesso!</div>
                <?php
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Erro ao criar serviço: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    exit;
                }
                ?>
            </div>
        </div>

        <?php
        // ============================================
        // CONSULTAR NFS-e
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">4. Consultando NFS-e</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    echo '<p><strong>Chave de Acesso:</strong> <code>' . htmlspecialchars($chaveAcesso) . '</code></p>';
                    echo '<p><strong>Tamanho da Chave:</strong> ' . strlen($chaveAcesso) . ' caracteres</p>';

                    // Validação da chave de acesso
                    if (strlen($chaveAcesso) !== 50) {
                        throw new Exception('A chave de acesso deve conter exatamente 50 caracteres!');
                    }

                    if (!ctype_digit($chaveAcesso)) {
                        throw new Exception('A chave de acesso deve conter apenas números!');
                    }

                    echo '<div class="alert alert-info">Consultando NFS-e na API...</div>';

                    // Consulta a NFS-e
                    $resposta = $sefinService->consultarNfse($chaveAcesso);

                    echo '<div class="alert alert-success">✓ Consulta realizada com sucesso!</div>';

                    // Verifica o statusCode da resposta
                    $statusCode = $resposta['statusCode'] ?? $resposta['status'] ?? null;
                    echo '<p><strong>Status Code:</strong> ' . htmlspecialchars((string)$statusCode) . '</p>';

                    // Se statusCode for 200, extrai e decodifica o XML da NFS-e
                    if ($statusCode === 200) {
                        // Obtém o body da resposta
                        $body = $resposta['body'] ?? null;

                        // Verifica se o body contém nfseXmlGZipB64 (quando body é array/objeto)
                        if (is_array($body) && isset($body['nfseXmlGZipB64'])) {
                            $nfseXmlGZipB64 = $body['nfseXmlGZipB64'];

                            // Exibe informações da resposta
                            echo '<div class="card mb-3">';
                            echo '<div class="card-header bg-info text-white"><strong>Informações da Resposta</strong></div>';
                            echo '<div class="card-body">';
                            echo '<p><strong>Tipo Ambiente:</strong> ' . htmlspecialchars((string)($body['tipoAmbiente'] ?? 'N/A')) . '</p>';
                            echo '<p><strong>Versão Aplicativo:</strong> ' . htmlspecialchars($body['versaoAplicativo'] ?? 'N/A') . '</p>';
                            echo '<p><strong>Data/Hora Processamento:</strong> ' . htmlspecialchars($body['dataHoraProcessamento'] ?? 'N/A') . '</p>';
                            echo '<p><strong>Chave de Acesso:</strong> <code>' . htmlspecialchars($body['chaveAcesso'] ?? 'N/A') . '</code></p>';
                            echo '</div>';
                            echo '</div>';

                            // Decodifica Base64 e descomprime GZip
                            $xmlDecodificado = base64_decode($nfseXmlGZipB64, true);
                            if ($xmlDecodificado !== false) {
                                $xmlNfse = @gzdecode($xmlDecodificado);
                                if ($xmlNfse !== false) {
                                    // Formata o XML para exibição
                                    $dom = new DOMDocument('1.0', 'UTF-8');
                                    $dom->preserveWhiteSpace = false;
                                    $dom->formatOutput = true;
                                    if (@$dom->loadXML($xmlNfse)) {
                                        $xmlFormatado = $dom->saveXML();
                                    } else {
                                        $xmlFormatado = $xmlNfse;
                                    }

                                    echo '<div class="card mb-3">';
                                    echo '<div class="card-header bg-success text-white"><strong>XML da NFS-e</strong></div>';
                                    echo '<div class="card-body">';
                                    echo '<pre style="max-height: 600px; overflow-y: auto;">' . htmlspecialchars($xmlFormatado) . '</pre>';
                                    echo '</div>';
                                    echo '</div>';
                                } else {
                                    echo '<div class="alert alert-warning">Não foi possível descomprimir o XML (GZip).</div>';
                                }
                            } else {
                                echo '<div class="alert alert-warning">Não foi possível decodificar o XML (Base64).</div>';
                            }
                        } else {
                            // Caso o body seja uma string (formato antigo)
                            echo '<h6>Resposta da API (Body):</h6>';
                            echo '<pre>' . htmlspecialchars(json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . '</pre>';
                        }
                    } else {
                        // Se não for 200, exibe a resposta completa para debug
                        echo '<div class="alert alert-warning">Status Code diferente de 200. Verifique a resposta abaixo:</div>';
                        echo '<h6>Resposta Completa da API:</h6>';
                        echo '<pre>' . htmlspecialchars(json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . '</pre>';
                    }

                    // Exibe a resposta raw (JSON original) se disponível
                    if (isset($resposta['raw'])) {
                        echo '<details class="mt-3">';
                        echo '<summary><strong>Resposta Raw (JSON original)</strong></summary>';
                        echo '<pre class="mt-2">' . htmlspecialchars($resposta['raw']) . '</pre>';
                        echo '</details>';
                    }

                    // Exibe informações do cabeçalho HTTP
                    if (isset($resposta['headers'])) {
                        echo '<details class="mt-3">';
                        echo '<summary><strong>Cabeçalhos HTTP</strong></summary>';
                        echo '<pre class="mt-2">' . htmlspecialchars(json_encode($resposta['headers'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . '</pre>';
                        echo '</details>';
                    }

                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">';
                    echo '<strong>Erro ao consultar NFS-e:</strong><br>';
                    echo htmlspecialchars($e->getMessage());
                    echo '</div>';

                    if (isset($e->getTrace()[0])) {
                        echo '<details class="mt-2">';
                        echo '<summary>Detalhes do Erro</summary>';
                        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
                        echo '</details>';
                    }
                }
                ?>
            </div>
        </div>

        <?php
        // ============================================
        // INFORMAÇÕES ADICIONAIS
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">5. Informações sobre a Chave de Acesso</h5>
            </div>
            <div class="card-body">
                <p>A chave de acesso da NFS-e é composta por 50 dígitos numéricos:</p>
                <ul>
                    <li><strong>Código do Município (7 dígitos):</strong> <?= htmlspecialchars(substr($chaveAcesso, 0, 7)) ?></li>
                    <li><strong>Ambiente Gerador (1 dígito):</strong> <?= htmlspecialchars(substr($chaveAcesso, 7, 1)) ?></li>
                    <li><strong>Tipo de Inscrição Federal (1 dígito):</strong> <?= htmlspecialchars(substr($chaveAcesso, 8, 1)) ?></li>
                    <li><strong>Inscrição Federal (14 dígitos):</strong> <?= htmlspecialchars(substr($chaveAcesso, 9, 14)) ?></li>
                    <li><strong>Número da NFS-e (13 dígitos):</strong> <?= htmlspecialchars(substr($chaveAcesso, 23, 13)) ?></li>
                    <li><strong>Ano/Mês de Emissão (4 dígitos):</strong> <?= htmlspecialchars(substr($chaveAcesso, 36, 4)) ?></li>
                    <li><strong>Valor do nNFSe (9 dígitos):</strong> <?= htmlspecialchars(substr($chaveAcesso, 40, 9)) ?></li>
                    <li><strong>Dígito Verificador (1 dígito):</strong> <?= htmlspecialchars(substr($chaveAcesso, 49, 1)) ?></li>
                </ul>
            </div>
        </div>

        <?php
        // ============================================
        // EXEMPLOS DE OUTRAS CONSULTAS
        // ============================================
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">6. Outros Métodos de Consulta Disponíveis</h5>
            </div>
            <div class="card-body">
                <h6>Consultar DPS pelo ID:</h6>
                <pre class="bg-light p-2">$idDps = 'DPS4205407141234567800019010000000000001';
$resposta = $sefinService->consultarDps($idDps);</pre>

                <h6 class="mt-3">Verificar se NFS-e foi emitida:</h6>
                <pre class="bg-light p-2">$idDps = 'DPS4205407141234567800019010000000000001';
$resposta = $sefinService->verificarDps($idDps);</pre>

                <h6 class="mt-3">Consultar Evento da NFS-e:</h6>
                <pre class="bg-light p-2">$chaveAcesso = '42054072241039410000123000000000046726017861547792';
$tipoEvento = 1; // Tipo do evento
$numSeqEvento = 1; // Número sequencial do evento
$resposta = $sefinService->consultarEvento($chaveAcesso, $tipoEvento, $numSeqEvento);</pre>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
