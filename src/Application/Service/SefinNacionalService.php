<?php

declare(strict_types=1);

namespace NfseNacional\Application\Service;

use Exception;
use NfseNacional\Domain\Contract\AssinadorXmlInterface;
use NfseNacional\Domain\Contract\HttpClientInterface;
use NfseNacional\Domain\Entity\Emitente;
use NfseNacional\Domain\Xml\DpsXml;

/**
 * Serviço para comunicação com a API Sefin Nacional
 *
 * Implementa os métodos de DPS, NFS-e e Eventos conforme documentação Swagger
 *
 * @package NfseNacional\Application\Service
 */
class SefinNacionalService
{
    /**
     * Tipo de ambiente: Produção
     */
    public const AMBIENTE_PRODUCAO = 1;

    /**
     * Tipo de ambiente: Homologação
     */
    public const AMBIENTE_HOMOLOGACAO = 2;

    /**
     * URL SEFIN Homologação
     */
    private const URL_SEFIN_HOMOLOGACAO = 'https://sefin.producaorestrita.nfse.gov.br/SefinNacional';

    /**
     * URL SEFIN Produção
     */
    private const URL_SEFIN_PRODUCAO = 'https://sefin.nfse.gov.br/sefinnacional';

    private AssinadorXmlInterface $assinador;
    private HttpClientInterface $httpClient;
    private Emitente $emitente;

    /**
     * Tipo de ambiente (self::AMBIENTE_PRODUCAO ou self::AMBIENTE_HOMOLOGACAO)
     * @var int
     */
    private int $tipoAmbiente;

    /**
     * Construtor
     *
     * @param AssinadorXmlInterface $assinador Assinador de XML
     * @param Emitente $emitente Emitente com certificado para autenticação SSL/TLS
     * @param int $tipoAmbiente Tipo de ambiente (self::AMBIENTE_PRODUCAO ou self::AMBIENTE_HOMOLOGACAO). Padrão: AMBIENTE_PRODUCAO
     * @throws Exception
     */
    public function __construct(
        Emitente $emitente,
        AssinadorXmlInterface $assinador,
        int $tipoAmbiente = self::AMBIENTE_PRODUCAO
    ) {
        $this->assinador = $assinador;
        $this->emitente = $emitente;

        // Valida o emitente
        $emitente->validar();

        // Obtém certificado do emitente
        $certificado = $emitente->obterCertificado();

        if ($certificado === null) {
            throw new Exception('Certificado do emitente é obrigatório para autenticação SSL/TLS!');
        }

        // Cria o HttpClient com autenticação via certificado
        $this->httpClient = new \NfseNacional\Infrastructure\Http\HttpClient(
            [],
            $certificado->obterConteudo(),
            $certificado->obterSenha()
        );

        $this->definirTipoAmbiente($tipoAmbiente);
    }

    /**
     * Define o tipo de ambiente
     *
     * @param int $tipoAmbiente Tipo de ambiente (self::AMBIENTE_PRODUCAO ou self::AMBIENTE_HOMOLOGACAO)
     * @return self
     * @throws Exception
     */
    public function definirTipoAmbiente(int $tipoAmbiente): self
    {
        if ($tipoAmbiente !== self::AMBIENTE_PRODUCAO && $tipoAmbiente !== self::AMBIENTE_HOMOLOGACAO) {
            throw new Exception('Tipo de ambiente inválido! Use SefinNacionalService::AMBIENTE_PRODUCAO ou SefinNacionalService::AMBIENTE_HOMOLOGACAO.');
        }

        $this->tipoAmbiente = $tipoAmbiente;
        return $this;
    }

    /**
     * Obtém o tipo de ambiente
     *
     * @return int Tipo de ambiente (self::AMBIENTE_PRODUCAO ou self::AMBIENTE_HOMOLOGACAO)
     */
    public function obterTipoAmbiente(): int
    {
        return $this->tipoAmbiente;
    }

    // ============================================
    // MÉTODOS DE DPS
    // ============================================

    /**
     * Consulta a chave de acesso da NFS-e a partir do identificador do DPS
     *
     * GET /dps/{id}
     *
     * @param string $idDps Identificador do DPS
     * @return array Resposta da API com chave de acesso
     * @throws Exception
     */
    public function consultarDps(string $idDps): array
    {
        if (empty($idDps)) {
            throw new Exception('Identificador do DPS é obrigatório!');
        }

        $url = $this->obterUrlBase() . '/dps/' . urlencode($idDps);
        return $this->httpClient->get($url);
    }

    /**
     * Verifica se uma NFS-e foi emitida a partir do Id do DPS
     *
     * HEAD /dps/{id}
     *
     * @param string $idDps Identificador do DPS
     * @return bool True se a NFS-e foi emitida, false caso contrário
     * @throws Exception
     */
    public function verificarDps(string $idDps): bool
    {
        if (empty($idDps)) {
            throw new Exception('Identificador do DPS é obrigatório!');
        }

        $url = $this->obterUrlBase() . '/dps/' . urlencode($idDps);
        $resposta = $this->httpClient->get($url);

        // HEAD retorna 200 se encontrado, 404 se não encontrado
        return isset($resposta['statusCode']) && $resposta['statusCode'] === 200;
    }

    // ============================================
    // MÉTODOS DE NFS-e
    // ============================================

    /**
     * Recepciona a DPS e gera a NFS-e de forma síncrona
     *
     * POST /nfse
     *
     * @param DpsXml $dpsXml XML da DPS gerado
     * @return array Resposta da API com a NFS-e gerada
     * @throws Exception
     */
    public function enviarDps(DpsXml $dpsXml): array
    {
        // Obtém o XML gerado
        $xmlString = $dpsXml->render();

        // Obtém o emitente do DpsXml
        $emitente = $dpsXml->obterEmitente();

        if ($emitente === null) {
            throw new Exception('Emitente com certificado é obrigatório no DpsXml para assinar e enviar a DPS!');
        }

        // Valida o emitente
        $emitente->validar();

        // Assina o XML
        $xmlAssinado = $this->assinador->assinar($xmlString, $emitente, 'infDPS', 'DPS');

        // Remove a declaração XML se existir (a API não deve receber com declaração)
        $xmlAssinado = preg_replace('/<\?xml[^>]*\?>\s*/', '', $xmlAssinado);
        $xmlAssinado = trim($xmlAssinado);

        // Garante que o XML está em utf-8
        if (!mb_check_encoding($xmlAssinado, 'utf-8')) {
            $xmlAssinado = mb_convert_encoding($xmlAssinado, 'utf-8', 'auto');
        }

        $xmlAssinado = '<?xml version="1.0" encoding="utf-8"?>' . $xmlAssinado;

        // Comprime o XML em GZip (usando nível 9 para máxima compressão)
        $xmlGzip = gzencode($xmlAssinado, 9);
        if ($xmlGzip === false) {
            throw new Exception('Erro ao comprimir XML em GZip!');
        }

        // Codifica em Base64
        $xmlBase64 = base64_encode($xmlGzip);

        // Prepara os dados para envio
        $dados = [
            'dpsXmlGZipB64' => $xmlBase64,
        ];

        // Usa o ambiente configurado no serviço
        $url = $this->obterUrlBase() . '/nfse';

        // Envia para a API
        return $this->httpClient->post($url, $dados);
    }

    /**
     * Consulta a NFS-e a partir da chave de acesso
     *
     * GET /nfse/{chaveAcesso}
     *
     * @param string $chaveAcesso Chave de acesso da NFS-e (50 caracteres)
     * @return array Resposta da API com a NFS-e
     * @throws Exception
     */
    public function consultarNfse(string $chaveAcesso): array
    {
        if (empty($chaveAcesso)) {
            throw new Exception('Chave de acesso é obrigatória!');
        }

        // Valida se a chave de acesso tem 50 caracteres (apenas números)
        if (strlen($chaveAcesso) !== 50 || !ctype_digit($chaveAcesso)) {
            throw new Exception('A chave de acesso deve conter exatamente 50 números!');
        }

        $url = $this->obterUrlBase() . '/nfse/' . urlencode($chaveAcesso);
        return $this->httpClient->get($url);
    }

    /**
     * Recepciona NFS-e gerada de acordo com decisão judicial
     *
     * POST /decisao-judicial/nfse
     *
     * @param string $xmlNfseAssinado XML da NFS-e já assinado
     * @return array Resposta da API
     * @throws Exception
     */
    public function enviarNfseDecisaoJudicial(string $xmlNfseAssinado): array
    {
        if (empty($xmlNfseAssinado)) {
            throw new Exception('XML da NFS-e é obrigatório!');
        }

        // Remove a declaração XML se existir (a API não deve receber com declaração)
        $xmlNfseAssinado = preg_replace('/<\?xml[^>]*\?>\s*/', '', $xmlNfseAssinado);
        $xmlNfseAssinado = trim($xmlNfseAssinado);

        // Garante que o XML está em utf-8
        if (!mb_check_encoding($xmlNfseAssinado, 'utf-8')) {
            $xmlNfseAssinado = mb_convert_encoding($xmlNfseAssinado, 'utf-8', 'auto');
        }

        // Comprime o XML em GZip (usando nível 9 para máxima compressão)
        $xmlGzip = gzencode($xmlNfseAssinado, 9);
        if ($xmlGzip === false) {
            throw new Exception('Erro ao comprimir XML em GZip!');
        }

        // Codifica em Base64
        $xmlBase64 = base64_encode($xmlGzip);

        // Prepara os dados para envio
        $dados = [
            'xmlGZipB64' => $xmlBase64,
        ];

        $url = $this->obterUrlBase() . '/decisao-judicial/nfse';
        return $this->httpClient->post($url, $dados);
    }

    // ============================================
    // MÉTODOS DE EVENTOS
    // ============================================

    /**
     * Registra um evento na NFS-e
     *
     * POST /nfse/{chaveAcesso}/eventos
     *
     * @param string $chaveAcesso Chave de acesso da NFS-e (50 caracteres)
     * @param string $xmlEventoAssinado XML do pedido de registro de evento já assinado
     * @return array Resposta da API com o evento registrado
     * @throws Exception
     */
    public function registrarEvento(string $chaveAcesso, string $xmlEventoAssinado): array
    {
        if (empty($chaveAcesso)) {
            throw new Exception('Chave de acesso é obrigatória!');
        }

        if (empty($xmlEventoAssinado)) {
            throw new Exception('XML do evento é obrigatório!');
        }

        // Valida se a chave de acesso tem 50 caracteres
        if (strlen($chaveAcesso) !== 50 || !ctype_digit($chaveAcesso)) {
            throw new Exception('A chave de acesso deve conter exatamente 50 números!');
        }

        // Remove a declaração XML se existir (a API não deve receber com declaração)
        $xmlEventoAssinado = preg_replace('/<\?xml[^>]*\?>\s*/', '', $xmlEventoAssinado);
        $xmlEventoAssinado = trim($xmlEventoAssinado);

        // Garante que o XML está em utf-8
        if (!mb_check_encoding($xmlEventoAssinado, 'utf-8')) {
            $xmlEventoAssinado = mb_convert_encoding($xmlEventoAssinado, 'utf-8', 'auto');
        }

        // Comprime o XML em GZip (usando nível 9 para máxima compressão)
        $xmlGzip = gzencode($xmlEventoAssinado, 9);
        if ($xmlGzip === false) {
            throw new Exception('Erro ao comprimir XML em GZip!');
        }

        // Codifica em Base64
        $xmlBase64 = base64_encode($xmlGzip);

        // Prepara os dados para envio
        $dados = [
            'pedidoRegistroEventoXmlGZipB64' => $xmlBase64,
        ];

        $url = $this->obterUrlBase() . '/nfse/' . urlencode($chaveAcesso) . '/eventos';
        return $this->httpClient->post($url, $dados);
    }

    /**
     * Consulta um evento específico da NFS-e
     *
     * GET /nfse/{chaveAcesso}/eventos/{tipoEvento}/{numSeqEvento}
     *
     * @param string $chaveAcesso Chave de acesso da NFS-e (50 caracteres)
     * @param int $tipoEvento Tipo de evento (enum conforme swagger)
     * @param int $numSeqEvento Número sequencial do evento
     * @return array Resposta da API com o evento
     * @throws Exception
     */
    public function consultarEvento(string $chaveAcesso, int $tipoEvento, int $numSeqEvento): array
    {
        if (empty($chaveAcesso)) {
            throw new Exception('Chave de acesso é obrigatória!');
        }

        // Valida se a chave de acesso tem 50 caracteres
        if (strlen($chaveAcesso) !== 50 || !ctype_digit($chaveAcesso)) {
            throw new Exception('A chave de acesso deve conter exatamente 50 números!');
        }

        // Valida tipos de evento permitidos conforme swagger
        $tiposEventoPermitidos = [
            101101, 101103, 105102, 105104, 105105,
            202201, 202205, 203202, 203206, 204203, 204207, 205204, 205208,
            305101, 305102, 305103,
            907201, 907209
        ];

        if (!in_array($tipoEvento, $tiposEventoPermitidos, true)) {
            throw new Exception('Tipo de evento inválido! Tipos permitidos: ' . implode(', ', $tiposEventoPermitidos));
        }

        if ($numSeqEvento <= 0) {
            throw new Exception('Número sequencial do evento deve ser maior que zero!');
        }

        $url = $this->obterUrlBase() . '/nfse/' . urlencode($chaveAcesso) . '/eventos/' . $tipoEvento . '/' . $numSeqEvento;
        return $this->httpClient->get($url);
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Obtém a URL base da API baseada no ambiente configurado no serviço
     *
     * @return string URL base da API
     */
    private function obterUrlBase(): string
    {
        // Usa o ambiente configurado no serviço
        return ($this->tipoAmbiente === self::AMBIENTE_PRODUCAO)
            ? self::URL_SEFIN_PRODUCAO
            : self::URL_SEFIN_HOMOLOGACAO;
    }

    /**
     * Decodifica uma resposta XML comprimida em GZip e codificada em Base64
     *
     * @param string $xmlGZipB64 XML comprimido em GZip e codificado em Base64
     * @return string XML decodificado
     * @throws Exception
     */
    public static function decodificarXmlGZipB64(string $xmlGZipB64): string
    {
        // Decodifica Base64
        $xmlGzip = base64_decode($xmlGZipB64, true);
        if ($xmlGzip === false) {
            throw new Exception('Erro ao decodificar Base64!');
        }

        // Descomprime GZip
        $xml = gzdecode($xmlGzip);
        if ($xml === false) {
            throw new Exception('Erro ao descomprimir GZip!');
        }

        return $xml;
    }
}
