<?php

declare(strict_types=1);

namespace NfseNacional\Client;

use NfseNacional\Entity\XmlInterface;
use NfseNacional\Exceptions\ApiException;
use NfseNacional\Exceptions\CertificateException;
use NfseNacional\Models\Enums\TipoAmbiente;
use NfseNacional\Models\Enums\TipoManifestacao;
use NfseNacional\Models\LoteDistribuicaoNSUResponse;
use NfseNacional\Models\Nfse;
use NfseNacional\Models\PedidoRegistroEvento;
use NfseNacional\Models\ManifestacaoNfse;
use NfseNacional\Models\RascunhoDPS;
use NfseNacional\Security\CertificateHandler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Cliente para comunicação com a API NFS-e Nacional
 */
class NfseClient
{
    private Client $httpClient;
    private CertificateHandler $certificateHandler;
    private string $baseUrl;
    private TipoAmbiente $ambiente;

    /**
     * URLs base da API por ambiente
     */
    private const BASE_URLS = [
        'PRODUCAO' => 'https://api.nfse.gov.br', // Ajustar conforme URL real
        'HOMOLOGACAO' => 'https://api-homologacao.nfse.gov.br', // Ajustar conforme URL real
    ];

    /**
     * @param string $certificatePath Caminho para o arquivo do certificado (.pfx ou .p12)
     * @param string $certificatePassword Senha do certificado
     * @param TipoAmbiente $ambiente Ambiente (PRODUCAO ou HOMOLOGACAO)
     * @param string|null $baseUrl URL base customizada (opcional, sobrescreve a padrão)
     * @param array $httpOptions Opções adicionais para o cliente HTTP
     * @throws CertificateException
     */
    public function __construct(
        string $certificatePath,
        string $certificatePassword,
        TipoAmbiente $ambiente = TipoAmbiente::HOMOLOGACAO,
        ?string $baseUrl = null,
        array $httpOptions = []
    ) {
        $this->certificateHandler = new CertificateHandler($certificatePath, $certificatePassword);
        $this->ambiente = $ambiente;
        $this->baseUrl = $baseUrl ?? self::BASE_URLS[$ambiente->value];

        $defaultOptions = [
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
            'verify' => true,
            'http_errors' => false,
            'cert' => [
                $certificatePath,
                $certificatePassword,
            ],
        ];

        $this->httpClient = new Client(array_merge($defaultOptions, $httpOptions));
    }

    /**
     * Consulta documento fiscal por NSU
     *
     * @param int $nsu Número Sequencial Único
     * @param string|null $cnpjConsulta CNPJ para consulta (opcional)
     * @param bool $lote Se deve retornar em lote (default: true)
     * @return LoteDistribuicaoNSUResponse
     * @throws ApiException
     */
    public function consultarDFePorNSU(
        int $nsu,
        ?string $cnpjConsulta = null,
        bool $lote = true
    ): LoteDistribuicaoNSUResponse {
        $queryParams = ['lote' => $lote ? 'true' : 'false'];
        if ($cnpjConsulta !== null) {
            $queryParams['cnpjConsulta'] = $cnpjConsulta;
        }

        $response = $this->makeRequest('GET', "/DFe/{$nsu}", [
            'query' => $queryParams,
        ]);

        return $this->parseResponse($response);
    }

    /**
     * Consulta eventos de uma NFS-e por chave de acesso
     *
     * @param string $chaveAcesso Chave de acesso da NFS-e
     * @return LoteDistribuicaoNSUResponse
     * @throws ApiException
     */
    public function consultarEventosPorChaveAcesso(string $chaveAcesso): LoteDistribuicaoNSUResponse
    {
        $response = $this->makeRequest('GET', "/NFSe/{$chaveAcesso}/Eventos");

        return $this->parseResponse($response);
    }

    /**
     * Realiza uma requisição HTTP
     *
     * @param string $method Método HTTP
     * @param string $uri URI do endpoint
     * @param array $options Opções adicionais
     * @return ResponseInterface
     * @throws ApiException
     */
    private function makeRequest(string $method, string $uri, array $options = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->request($method, $uri, $options);
            $this->handleResponseErrors($response);
            return $response;
        } catch (GuzzleException $e) {
            throw new ApiException(
                "Erro na comunicação com a API: " . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Trata erros na resposta HTTP
     *
     * @param ResponseInterface $response
     * @throws ApiException
     */
    private function handleResponseErrors(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            $body = (string) $response->getBody();
            $message = "Erro HTTP {$statusCode}";

            try {
                $data = json_decode($body, true);
                if (isset($data['Erros']) && is_array($data['Erros'])) {
                    $errorMessages = array_map(
                        fn($erro) => $erro['Descricao'] ?? $erro['Mensagem'] ?? 'Erro desconhecido',
                        $data['Erros']
                    );
                    $message .= ': ' . implode(', ', $errorMessages);
                } elseif (isset($data['Descricao'])) {
                    $message .= ': ' . $data['Descricao'];
                }
            } catch (\Exception $e) {
                // Se não conseguir parsear JSON, usa a mensagem padrão
            }

            $exception = new ApiException($message);
            $exception->setStatusCode($statusCode);
            throw $exception;
        }
    }

    /**
     * Parseia a resposta da API
     *
     * @param ResponseInterface $response
     * @return LoteDistribuicaoNSUResponse
     * @throws ApiException
     */
    private function parseResponse(ResponseInterface $response): LoteDistribuicaoNSUResponse
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $body = (string) $response->getBody();

        // Tenta parsear como JSON primeiro
        if (strpos($contentType, 'application/json') !== false || strpos($contentType, 'text/json') !== false) {
            $data = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException("Erro ao parsear resposta JSON: " . json_last_error_msg());
            }
        } elseif (strpos($contentType, 'application/xml') !== false || strpos($contentType, 'text/xml') !== false) {
            // Se for XML, converte para array (implementação básica)
            // Em produção, pode ser necessário usar um parser XML mais robusto
            $data = $this->xmlToArray($body);
        } else {
            // Tenta JSON mesmo sem o content-type correto
            $data = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException("Formato de resposta não suportado: {$contentType}");
            }
        }

        try {
            return LoteDistribuicaoNSUResponse::fromArray($data);
        } catch (\Exception $e) {
            throw new ApiException(
                "Erro ao criar modelo de resposta: " . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Converte XML simples para array (implementação básica)
     *
     * @param string $xml
     * @return array
     */
    private function xmlToArray(string $xml): array
    {
        // Implementação básica - pode ser melhorada com um parser XML mais robusto
        $data = [];
        $xml = simplexml_load_string($xml);
        if ($xml !== false) {
            $json = json_encode($xml);
            $data = json_decode($json, true);
        }
        return $data;
    }

    /**
     * Parseia resposta JSON genérica
     *
     * @param ResponseInterface $response
     * @return array
     * @throws ApiException
     */
    private function parseJsonResponse(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException("Erro ao parsear resposta JSON: " . json_last_error_msg());
        }

        return $data;
    }

    // ========== EMISSÃO DE NFS-e ==========

    /**
     * Envia DPS para emissão de NFS-e
     *
     * @param XmlInterface $dpsEntity Entidade DPS
     * @return Nfse
     * @throws ApiException
     */
    public function emitirNfse(XmlInterface $dpsEntity): Nfse
    {
        // Valida a entidade
        $dpsEntity->validate();

        // Converte para XML assinado e comprimido usando o certificado do cliente
        $certificate = $this->certificateHandler->getCertificate();
        $privateKey = $this->certificateHandler->getPrivateKey();
        $dpsXml = $dpsEntity->toSignedAndCompressed($certificate, $privateKey);

        $response = $this->makeRequest('POST', '/DPS', [
            'json' => ['DpsXml' => $dpsXml],
        ]);

        $data = $this->parseJsonResponse($response);
        return Nfse::fromArray($data);
    }

    /**
     * Envia DPS para emissão de NFS-e (método legado - aceita string)
     *
     * @param string $dpsXml XML da DPS assinado e comprimido (GZip + base64)
     * @return Nfse
     * @throws ApiException
     * @deprecated Use emitirNfse(XmlInterface) ao invés deste método
     */
    public function emitirNfseFromString(string $dpsXml): Nfse
    {
        $response = $this->makeRequest('POST', '/DPS', [
            'json' => ['DpsXml' => $dpsXml],
        ]);

        $data = $this->parseJsonResponse($response);
        return Nfse::fromArray($data);
    }

    /**
     * Envia lote de DPS para processamento
     *
     * @param array $dpsEntities Array de entidades DPS (XmlInterface)
     * @return array Array de Nfse processadas
     * @throws ApiException
     */
    public function emitirLoteNfse(array $dpsEntities): array
    {
        $certificate = $this->certificateHandler->getCertificate();
        $privateKey = $this->certificateHandler->getPrivateKey();
        $dpsList = [];

        foreach ($dpsEntities as $dpsEntity) {
            if (!$dpsEntity instanceof XmlInterface) {
                throw new ApiException("Todos os itens do lote devem ser instâncias de XmlInterface");
            }

            // Valida cada entidade
            $dpsEntity->validate();

            // Converte para XML assinado e comprimido
            $dpsList[] = $dpsEntity->toSignedAndCompressed($certificate, $privateKey);
        }

        $response = $this->makeRequest('POST', '/DPS/Lote', [
            'json' => ['DpsList' => $dpsList],
        ]);

        $data = $this->parseJsonResponse($response);
        $nfseList = [];

        if (isset($data['NfseList']) && is_array($data['NfseList'])) {
            foreach ($data['NfseList'] as $nfseData) {
                $nfseList[] = Nfse::fromArray($nfseData);
            }
        }

        return $nfseList;
    }

    /**
     * Envia lote de DPS para processamento (método legado - aceita array de strings)
     *
     * @param array $dpsList Array de XMLs de DPS (GZip + base64)
     * @return array Array de Nfse processadas
     * @throws ApiException
     * @deprecated Use emitirLoteNfse(array<XmlInterface>) ao invés deste método
     */
    public function emitirLoteNfseFromStrings(array $dpsList): array
    {
        $response = $this->makeRequest('POST', '/DPS/Lote', [
            'json' => ['DpsList' => $dpsList],
        ]);

        $data = $this->parseJsonResponse($response);
        $nfseList = [];

        if (isset($data['NfseList']) && is_array($data['NfseList'])) {
            foreach ($data['NfseList'] as $nfseData) {
                $nfseList[] = Nfse::fromArray($nfseData);
            }
        }

        return $nfseList;
    }

    // ========== CONSULTA DE NFS-e EMITIDAS ==========

    /**
     * Consulta NFS-e emitidas pelo contribuinte
     *
     * @param \DateTimeInterface $dataInicio
     * @param \DateTimeInterface $dataFim
     * @param string|null $numeroNfse Número da NFS-e (opcional)
     * @param string|null $serie Série da NFS-e (opcional)
     * @return array Array de Nfse
     * @throws ApiException
     */
    public function consultarNfseEmitidas(
        \DateTimeInterface $dataInicio,
        \DateTimeInterface $dataFim,
        ?string $numeroNfse = null,
        ?string $serie = null
    ): array {
        $queryParams = [
            'dataInicio' => $dataInicio->format('Y-m-d'),
            'dataFim' => $dataFim->format('Y-m-d'),
        ];

        if ($numeroNfse !== null) {
            $queryParams['numeroNfse'] = $numeroNfse;
        }

        if ($serie !== null) {
            $queryParams['serie'] = $serie;
        }

        $response = $this->makeRequest('GET', '/NFSe/Emitidas', [
            'query' => $queryParams,
        ]);

        $data = $this->parseJsonResponse($response);
        $nfseList = [];

        if (isset($data['NfseList']) && is_array($data['NfseList'])) {
            foreach ($data['NfseList'] as $nfseData) {
                $nfseList[] = Nfse::fromArray($nfseData);
            }
        }

        return $nfseList;
    }

    /**
     * Consulta NFS-e específica por chave de acesso
     *
     * @param string $chaveAcesso
     * @return Nfse
     * @throws ApiException
     */
    public function consultarNfsePorChaveAcesso(string $chaveAcesso): Nfse
    {
        $response = $this->makeRequest('GET', "/NFSe/{$chaveAcesso}");
        $data = $this->parseJsonResponse($response);
        return Nfse::fromArray($data);
    }

    /**
     * Download do XML da NFS-e
     *
     * @param string $chaveAcesso
     * @return string XML da NFS-e
     * @throws ApiException
     */
    public function downloadXmlNfse(string $chaveAcesso): string
    {
        $response = $this->makeRequest('GET', "/NFSe/{$chaveAcesso}/XML", [
            'headers' => ['Accept' => 'application/xml'],
        ]);

        return (string) $response->getBody();
    }

    /**
     * Download da DANFSE (PDF)
     *
     * @param string $chaveAcesso
     * @return string Conteúdo do PDF em base64 ou binário
     * @throws ApiException
     */
    public function downloadDanfse(string $chaveAcesso): string
    {
        $response = $this->makeRequest('GET', "/NFSe/{$chaveAcesso}/DANFSE", [
            'headers' => ['Accept' => 'application/pdf'],
        ]);

        return (string) $response->getBody();
    }

    // ========== CONSULTA DE NFS-e RECEBIDAS ==========

    /**
     * Consulta NFS-e recebidas pelo contribuinte
     *
     * @param \DateTimeInterface $dataInicio
     * @param \DateTimeInterface $dataFim
     * @param string|null $cnpjPrestador CNPJ do prestador (opcional)
     * @return array Array de Nfse
     * @throws ApiException
     */
    public function consultarNfseRecebidas(
        \DateTimeInterface $dataInicio,
        \DateTimeInterface $dataFim,
        ?string $cnpjPrestador = null
    ): array {
        $queryParams = [
            'dataInicio' => $dataInicio->format('Y-m-d'),
            'dataFim' => $dataFim->format('Y-m-d'),
        ];

        if ($cnpjPrestador !== null) {
            $queryParams['cnpjPrestador'] = $cnpjPrestador;
        }

        $response = $this->makeRequest('GET', '/NFSe/Recebidas', [
            'query' => $queryParams,
        ]);

        $data = $this->parseJsonResponse($response);
        $nfseList = [];

        if (isset($data['NfseList']) && is_array($data['NfseList'])) {
            foreach ($data['NfseList'] as $nfseData) {
                $nfseList[] = Nfse::fromArray($nfseData);
            }
        }

        return $nfseList;
    }

    /**
     * Consulta NFS-e recebida específica
     *
     * @param string $chaveAcesso
     * @return Nfse
     * @throws ApiException
     */
    public function consultarNfseRecebidaPorChaveAcesso(string $chaveAcesso): Nfse
    {
        $response = $this->makeRequest('GET', "/NFSe/Recebidas/{$chaveAcesso}");
        $data = $this->parseJsonResponse($response);
        return Nfse::fromArray($data);
    }

    /**
     * Download do XML da NFS-e recebida
     *
     * @param string $chaveAcesso
     * @return string XML da NFS-e
     * @throws ApiException
     */
    public function downloadXmlNfseRecebida(string $chaveAcesso): string
    {
        $response = $this->makeRequest('GET', "/NFSe/Recebidas/{$chaveAcesso}/XML", [
            'headers' => ['Accept' => 'application/xml'],
        ]);

        return (string) $response->getBody();
    }

    /**
     * Download da DANFSE recebida (PDF)
     *
     * @param string $chaveAcesso
     * @return string Conteúdo do PDF
     * @throws ApiException
     */
    public function downloadDanfseRecebida(string $chaveAcesso): string
    {
        $response = $this->makeRequest('GET', "/NFSe/Recebidas/{$chaveAcesso}/DANFSE", [
            'headers' => ['Accept' => 'application/pdf'],
        ]);

        return (string) $response->getBody();
    }

    // ========== CANCELAMENTO ==========

    /**
     * Cancela uma NFS-e
     *
     * @param string $chaveAcesso
     * @param string $justificativa
     * @return bool
     * @throws ApiException
     */
    public function cancelarNfse(string $chaveAcesso, string $justificativa): bool
    {
        $response = $this->makeRequest('POST', "/NFSe/{$chaveAcesso}/Cancelar", [
            'json' => ['Justificativa' => $justificativa],
        ]);

        $data = $this->parseJsonResponse($response);
        return isset($data['Sucesso']) && $data['Sucesso'] === true;
    }

    /**
     * Solicita cancelamento por análise fiscal
     *
     * @param string $chaveAcesso
     * @param string $justificativa
     * @param array|null $documentosComprobatorios Array de documentos em base64
     * @return string Protocolo da solicitação
     * @throws ApiException
     */
    public function solicitarCancelamentoAnaliseFiscal(
        string $chaveAcesso,
        string $justificativa,
        ?array $documentosComprobatorios = null
    ): string {
        $body = ['Justificativa' => $justificativa];

        if ($documentosComprobatorios !== null) {
            $body['DocumentosComprobatorios'] = $documentosComprobatorios;
        }

        $response = $this->makeRequest('POST', "/NFSe/{$chaveAcesso}/SolicitarCancelamentoAnaliseFiscal", [
            'json' => $body,
        ]);

        $data = $this->parseJsonResponse($response);
        return $data['Protocolo'] ?? '';
    }

    // ========== SUBSTITUIÇÃO ==========

    /**
     * Substitui uma NFS-e
     *
     * @param string $chaveAcesso Chave de acesso da NFS-e a ser substituída
     * @param XmlInterface $dpsSubstituicao Entidade DPS de substituição
     * @return Nfse Nova NFS-e gerada
     * @throws ApiException
     */
    public function substituirNfse(string $chaveAcesso, XmlInterface $dpsSubstituicao): Nfse
    {
        // Valida a entidade
        $dpsSubstituicao->validate();

        // Converte para XML assinado e comprimido
        $certificate = $this->certificateHandler->getCertificate();
        $privateKey = $this->certificateHandler->getPrivateKey();
        $dpsSubstituicaoXml = $dpsSubstituicao->toSignedAndCompressed($certificate, $privateKey);

        $response = $this->makeRequest('POST', "/NFSe/{$chaveAcesso}/Substituir", [
            'json' => ['DpsSubstituicaoXml' => $dpsSubstituicaoXml],
        ]);

        $data = $this->parseJsonResponse($response);
        return Nfse::fromArray($data);
    }

    // ========== MANIFESTAÇÃO ==========

    /**
     * Manifesta uma NFS-e recebida
     *
     * @param string $chaveAcesso
     * @param TipoManifestacao $tipoManifestacao
     * @param string|null $justificativa Justificativa (obrigatória para rejeição)
     * @return bool
     * @throws ApiException
     */
    public function manifestarNfse(
        string $chaveAcesso,
        TipoManifestacao $tipoManifestacao,
        ?string $justificativa = null
    ): bool {
        $body = ['TipoManifestacao' => $tipoManifestacao->value];

        if ($justificativa !== null) {
            $body['Justificativa'] = $justificativa;
        }

        $response = $this->makeRequest('POST', "/NFSe/Recebidas/{$chaveAcesso}/Manifestar", [
            'json' => $body,
        ]);

        $data = $this->parseJsonResponse($response);
        return isset($data['Sucesso']) && $data['Sucesso'] === true;
    }

    // ========== RASCUNHOS ==========

    /**
     * Salva um rascunho de DPS
     *
     * @param string $nome Nome do rascunho
     * @param array $dadosDPS Dados da DPS (sem assinatura)
     * @return RascunhoDPS
     * @throws ApiException
     */
    public function salvarRascunhoDPS(string $nome, array $dadosDPS): RascunhoDPS
    {
        $response = $this->makeRequest('POST', '/DPS/Rascunho', [
            'json' => [
                'Nome' => $nome,
                'DadosDPS' => $dadosDPS,
            ],
        ]);

        $data = $this->parseJsonResponse($response);
        return RascunhoDPS::fromArray($data);
    }

    /**
     * Lista rascunhos salvos
     *
     * @return array Array de RascunhoDPS
     * @throws ApiException
     */
    public function listarRascunhosDPS(): array
    {
        $response = $this->makeRequest('GET', '/DPS/Rascunho');
        $data = $this->parseJsonResponse($response);

        $rascunhos = [];
        if (isset($data['Rascunhos']) && is_array($data['Rascunhos'])) {
            foreach ($data['Rascunhos'] as $rascunhoData) {
                $rascunhos[] = RascunhoDPS::fromArray($rascunhoData);
            }
        }

        return $rascunhos;
    }

    /**
     * Obtém um rascunho específico
     *
     * @param string $id ID do rascunho
     * @return RascunhoDPS
     * @throws ApiException
     */
    public function obterRascunhoDPS(string $id): RascunhoDPS
    {
        $response = $this->makeRequest('GET', "/DPS/Rascunho/{$id}");
        $data = $this->parseJsonResponse($response);
        return RascunhoDPS::fromArray($data);
    }

    /**
     * Atualiza um rascunho
     *
     * @param string $id ID do rascunho
     * @param string|null $nome Novo nome (opcional)
     * @param array|null $dadosDPS Novos dados da DPS (opcional)
     * @return RascunhoDPS
     * @throws ApiException
     */
    public function atualizarRascunhoDPS(string $id, ?string $nome = null, ?array $dadosDPS = null): RascunhoDPS
    {
        $body = [];
        if ($nome !== null) {
            $body['Nome'] = $nome;
        }
        if ($dadosDPS !== null) {
            $body['DadosDPS'] = $dadosDPS;
        }

        $response = $this->makeRequest('PUT', "/DPS/Rascunho/{$id}", [
            'json' => $body,
        ]);

        $data = $this->parseJsonResponse($response);
        return RascunhoDPS::fromArray($data);
    }

    /**
     * Exclui um rascunho
     *
     * @param string $id ID do rascunho
     * @return bool
     * @throws ApiException
     */
    public function excluirRascunhoDPS(string $id): bool
    {
        $response = $this->makeRequest('DELETE', "/DPS/Rascunho/{$id}");
        $data = $this->parseJsonResponse($response);
        return isset($data['Sucesso']) && $data['Sucesso'] === true;
    }

    // ========== CONSULTA PÚBLICA ==========

    /**
     * Consulta pública por chave de acesso (não requer autenticação)
     *
     * @param string $chaveAcesso
     * @return Nfse Dados públicos da NFS-e
     * @throws ApiException
     */
    public function consultaPublicaPorChaveAcesso(string $chaveAcesso): Nfse
    {
        // Para consulta pública, pode ser necessário criar um cliente sem certificado
        // Por enquanto, usa o mesmo cliente
        $response = $this->makeRequest('GET', "/NFSe/Publica/ChaveAcesso/{$chaveAcesso}");
        $data = $this->parseJsonResponse($response);
        return Nfse::fromArray($data);
    }

    /**
     * Consulta pública por dados da DPS (não requer autenticação)
     *
     * @param string $cnpjPrestador
     * @param string $numero
     * @param string $serie
     * @param \DateTimeInterface $dataEmissao
     * @return Nfse
     * @throws ApiException
     */
    public function consultaPublicaPorDPS(
        string $cnpjPrestador,
        string $numero,
        string $serie,
        \DateTimeInterface $dataEmissao
    ): Nfse {
        $queryParams = [
            'cnpjPrestador' => $cnpjPrestador,
            'numero' => $numero,
            'serie' => $serie,
            'dataEmissao' => $dataEmissao->format('Y-m-d'),
        ];

        $response = $this->makeRequest('GET', '/NFSe/Publica/DPS', [
            'query' => $queryParams,
        ]);

        $data = $this->parseJsonResponse($response);
        return Nfse::fromArray($data);
    }

    // ========== DECISÃO ADMINISTRATIVA/JUDICIAL ==========

    /**
     * Inclui NFS-e por decisão administrativa/judicial
     *
     * @param array $dadosNfse Dados da NFS-e
     * @param string $documentoDecisao Documento da decisão (base64)
     * @return Nfse
     * @throws ApiException
     */
    public function incluirNfseDecisaoAdministrativa(array $dadosNfse, string $documentoDecisao): Nfse
    {
        $response = $this->makeRequest('POST', '/NFSe/DecisaoAdministrativa', [
            'json' => [
                'DadosNfse' => $dadosNfse,
                'DocumentoDecisao' => $documentoDecisao,
            ],
        ]);

        $data = $this->parseJsonResponse($response);
        return Nfse::fromArray($data);
    }

    /**
     * Consulta NFS-e emitidas por decisão administrativa/judicial
     *
     * @param \DateTimeInterface|null $dataInicio
     * @param \DateTimeInterface|null $dataFim
     * @return array Array de Nfse
     * @throws ApiException
     */
    public function consultarNfseDecisaoAdministrativa(
        ?\DateTimeInterface $dataInicio = null,
        ?\DateTimeInterface $dataFim = null
    ): array {
        $queryParams = [];

        if ($dataInicio !== null) {
            $queryParams['dataInicio'] = $dataInicio->format('Y-m-d');
        }

        if ($dataFim !== null) {
            $queryParams['dataFim'] = $dataFim->format('Y-m-d');
        }

        $response = $this->makeRequest('GET', '/NFSe/DecisaoAdministrativa', [
            'query' => $queryParams,
        ]);

        $data = $this->parseJsonResponse($response);
        $nfseList = [];

        if (isset($data['NfseList']) && is_array($data['NfseList'])) {
            foreach ($data['NfseList'] as $nfseData) {
                $nfseList[] = Nfse::fromArray($nfseData);
            }
        }

        return $nfseList;
    }

    /**
     * Cancela NFS-e emitida por decisão administrativa/judicial
     *
     * @param string $chaveAcesso
     * @param string $justificativa
     * @return bool
     * @throws ApiException
     */
    public function cancelarNfseDecisaoAdministrativa(string $chaveAcesso, string $justificativa): bool
    {
        $response = $this->makeRequest('POST', "/NFSe/DecisaoAdministrativa/{$chaveAcesso}/Cancelar", [
            'json' => ['Justificativa' => $justificativa],
        ]);

        $data = $this->parseJsonResponse($response);
        return isset($data['Sucesso']) && $data['Sucesso'] === true;
    }

    /**
     * Substitui NFS-e emitida por decisão administrativa/judicial
     *
     * @param string $chaveAcesso
     * @param XmlInterface $dpsSubstituicao Entidade DPS de substituição
     * @return Nfse
     * @throws ApiException
     */
    public function substituirNfseDecisaoAdministrativa(string $chaveAcesso, XmlInterface $dpsSubstituicao): Nfse
    {
        // Valida a entidade
        $dpsSubstituicao->validate();

        // Converte para XML assinado e comprimido
        $certificate = $this->certificateHandler->getCertificate();
        $privateKey = $this->certificateHandler->getPrivateKey();
        $dpsSubstituicaoXml = $dpsSubstituicao->toSignedAndCompressed($certificate, $privateKey);

        $response = $this->makeRequest('POST', "/NFSe/DecisaoAdministrativa/{$chaveAcesso}/Substituir", [
            'json' => ['DpsSubstituicaoXml' => $dpsSubstituicaoXml],
        ]);

        $data = $this->parseJsonResponse($response);
        return Nfse::fromArray($data);
    }

    /**
     * Retorna o handler de certificado
     */
    public function getCertificateHandler(): CertificateHandler
    {
        return $this->certificateHandler;
    }

    /**
     * Retorna o ambiente atual
     */
    public function getAmbiente(): TipoAmbiente
    {
        return $this->ambiente;
    }

    /**
     * Retorna a URL base
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}

