<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Security;

use DOMDocument;
use DOMXPath;
use Exception;
use NfseNacional\Domain\Contract\AssinadorXmlInterface;
use NfseNacional\Domain\Entity\Emitente;

/**
 * Implementação de assinatura XML usando OpenSSL
 *
 * @package NfseNacional\Infrastructure\Security
 */
class AssinadorXml implements AssinadorXmlInterface
{
    private const DPS_NAMESPACE = 'http://www.sped.fazenda.gov.br/nfse';
    private const SIGNATURE_NAMESPACE = 'http://www.w3.org/2000/09/xmldsig#';

    /**
     * Assina um XML usando o certificado do emitente
     *
     * @param string $xmlString XML a ser assinado
     * @param Emitente $emitente Emitente com certificado digital
     * @param string $elementoId ID do elemento a ser assinado (ex: 'infDPS')
     * @param string $prefixoId Prefixo do ID (ex: 'DPS')
     * @return string XML assinado
     * @throws Exception
     */
    public function assinar(string $xmlString, Emitente $emitente, string $elementoId = 'infDPS', string $prefixoId = 'DPS'): string
    {
        // Verifica se a extensão OpenSSL está disponível
        if (!extension_loaded('openssl')) {
            throw new Exception('Extensão OpenSSL não está disponível!');
        }

        $certificado = $emitente->obterCertificado();
        $senhaCertificado = $emitente->obterSenhaCertificado();

        if (empty($certificado) || empty($senhaCertificado)) {
            throw new Exception('Certificado ou senha não fornecidos!');
        }

        // Carrega o XML
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;

        if (!$dom->loadXML($xmlString)) {
            throw new Exception('Erro ao carregar XML!');
        }

        // Carrega o certificado
        $certData = $certificado;
        if (file_exists($certificado)) {
            $certData = file_get_contents($certificado);
            if ($certData === false) {
                throw new Exception('Não foi possível ler o arquivo do certificado!');
            }
        }

        // Tenta abrir o certificado PKCS#12
        $certInfo = [];
        if (!openssl_pkcs12_read($certData, $certInfo, $senhaCertificado)) {
            $error = openssl_error_string();
            throw new Exception('Erro ao ler certificado PKCS#12: ' . ($error ?: 'Erro desconhecido'));
        }

        $cert = $certInfo['cert'];
        $key = $certInfo['pkey'];

        // Obtém o ID do elemento a ser assinado
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('ns', self::DPS_NAMESPACE);
        $xpath->registerNamespace('ds', self::SIGNATURE_NAMESPACE);

        $elementoNodes = $xpath->query("//ns:{$elementoId}");
        if ($elementoNodes->length === 0) {
            // Tenta sem namespace
            $elementoNodes = $xpath->query("//*[local-name()='{$elementoId}']");
            if ($elementoNodes->length === 0) {
                throw new Exception("Elemento {$elementoId} não encontrado no XML!");
            }
        }

        $elemento = $elementoNodes->item(0);
        $idElemento = $elemento->getAttribute('Id');

        if (empty($idElemento)) {
            throw new Exception("ID do {$elementoId} não encontrado!");
        }

        // Encontra o elemento Signature
        $signatureNodes = $xpath->query('//ds:Signature');
        if ($signatureNodes->length === 0) {
            // Tenta sem namespace
            $signatureNodes = $xpath->query("//*[local-name()='Signature']");
            if ($signatureNodes->length === 0) {
                throw new Exception('Elemento Signature não encontrado no XML!');
            }
        }

        $signature = $signatureNodes->item(0);

        // Canonicaliza o elemento a ser assinado
        $canonical = $elemento->C14N(true, false);

        // Calcula o digest SHA1
        $digest = base64_encode(sha1($canonical, true));

        // Atualiza o DigestValue
        $digestValueNodes = $xpath->query('.//ds:DigestValue', $signature);
        if ($digestValueNodes->length === 0) {
            $digestValueNodes = $xpath->query(".//*[local-name()='DigestValue']", $signature);
        }
        if ($digestValueNodes->length > 0) {
            $digestValueNodes->item(0)->nodeValue = $digest;
        }

        // Canonicaliza o SignedInfo
        $signedInfoNodes = $xpath->query('.//ds:SignedInfo', $signature);
        if ($signedInfoNodes->length === 0) {
            $signedInfoNodes = $xpath->query(".//*[local-name()='SignedInfo']", $signature);
        }
        if ($signedInfoNodes->length === 0) {
            throw new Exception('Elemento SignedInfo não encontrado!');
        }

        $signedInfo = $signedInfoNodes->item(0);
        $signedInfoCanonical = $signedInfo->C14N(true, false);

        // Assina o SignedInfo
        $signatureValue = '';
        if (!openssl_sign($signedInfoCanonical, $signatureValue, $key, OPENSSL_ALGO_SHA1)) {
            $error = openssl_error_string();
            throw new Exception('Erro ao assinar XML: ' . ($error ?: 'Erro desconhecido'));
        }

        $signatureValueBase64 = base64_encode($signatureValue);

        // Atualiza o SignatureValue
        $signatureValueNodes = $xpath->query('.//ds:SignatureValue', $signature);
        if ($signatureValueNodes->length === 0) {
            $signatureValueNodes = $xpath->query(".//*[local-name()='SignatureValue']", $signature);
        }
        if ($signatureValueNodes->length > 0) {
            $signatureValueNodes->item(0)->nodeValue = $signatureValueBase64;
        }

        // Extrai o certificado X509
        $certResource = openssl_x509_read($cert);
        if (!$certResource) {
            throw new Exception('Erro ao ler certificado X509!');
        }

        openssl_x509_export($certResource, $certPem);
        $certPem = str_replace(['-----BEGIN CERTIFICATE-----', '-----END CERTIFICATE-----', "\n", "\r"], '', $certPem);
        $certPem = trim($certPem);

        // Atualiza o X509Certificate
        $x509CertNodes = $xpath->query('.//ds:X509Certificate', $signature);
        if ($x509CertNodes->length === 0) {
            $x509CertNodes = $xpath->query(".//*[local-name()='X509Certificate']", $signature);
        }
        if ($x509CertNodes->length > 0) {
            $x509CertNodes->item(0)->nodeValue = $certPem;
        }

        // Retorna o XML assinado
        return $dom->saveXML();
    }
}
