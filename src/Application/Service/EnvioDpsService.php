<?php

declare(strict_types=1);

namespace NfseNacional\Application\Service;

use Exception;
use NfseNacional\Domain\Contract\AssinadorXmlInterface;
use NfseNacional\Domain\Contract\EnvioDpsInterface;
use NfseNacional\Domain\Contract\HttpClientInterface;
use NfseNacional\Domain\Xml\DpsXml;

/**
 * Serviço de envio de DPS
 *
 * @package NfseNacional\Application\Service
 */
class EnvioDpsService implements EnvioDpsInterface
{
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

    /**
     * Construtor
     *
     * @param AssinadorXmlInterface $assinador Assinador de XML
     * @param HttpClientInterface $httpClient Cliente HTTP
     */
    public function __construct(
        AssinadorXmlInterface $assinador,
        HttpClientInterface $httpClient
    ) {
        $this->assinador = $assinador;
        $this->httpClient = $httpClient;
    }

    /**
     * Envia uma DPS para a API NFS-e Nacional
     *
     * @param DpsXml $dpsXml XML da DPS gerado
     * @return array Resposta da API
     * @throws Exception
     */
    public function enviar(DpsXml $dpsXml): array
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

        // Remove a declaração XML se existir (será adicionada novamente)
        $xmlAssinado = preg_replace('/<\?xml[^>]*\?>/', '', $xmlAssinado);
        $xmlAssinado = trim($xmlAssinado);

        // Adiciona a declaração XML
        $xmlAssinado = '<?xml version="1.0" encoding="UTF-8"?>' . $xmlAssinado;

        // Comprime o XML em GZip
        $xmlGzip = gzencode($xmlAssinado);
        if ($xmlGzip === false) {
            throw new Exception('Erro ao comprimir XML em GZip!');
        }

        // Codifica em Base64
        $xmlBase64 = base64_encode($xmlGzip);

        // Prepara os dados para envio
        $dados = [
            'dpsXmlGZipB64' => $xmlBase64,
        ];

        // Determina a URL baseada no ambiente
        $url = $this->obterUrlApi($dpsXml);

        // Envia para a API
        return $this->httpClient->post($url, $dados);
    }

    /**
     * Obtém a URL da API baseada no ambiente da DPS
     *
     * @param DpsXml $dpsXml XML da DPS
     * @return string URL completa da API
     * @throws Exception
     */
    private function obterUrlApi(DpsXml $dpsXml): string
    {
        // Obtém o tipo de ambiente do DPS
        // 1 = Produção, 2 = Homologação
        $tipoAmbiente = $dpsXml->obterDps()->obterTipoAmbiente();

        if ($tipoAmbiente === null) {
            throw new Exception('Tipo de ambiente não definido na DPS!');
        }

        // Determina a URL baseada no ambiente
        // tpamb === 1 = Produção, caso contrário = Homologação
        $urlBase = ($tipoAmbiente === 1)
            ? self::URL_SEFIN_PRODUCAO
            : self::URL_SEFIN_HOMOLOGACAO;

        // Monta a URL completa com a operação
        return rtrim($urlBase, '/') . '/nfse';
    }
}
