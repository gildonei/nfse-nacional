<?php

declare(strict_types=1);

namespace NfseNacional\Entity;

use DOMDocument;
use NfseNacional\Exceptions\ValidationException;
use NfseNacional\Utils\CompressionHandler;
use NfseNacional\Utils\XmlHandler;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

/**
 * Classe abstrata base para entidades XML
 */
abstract class AbstractXml implements XmlInterface
{
    protected ?DOMDocument $xmlDocument = null;

    /**
     * Converte a entidade para XML (DOMDocument)
     *
     * @return DOMDocument
     */
    abstract public function toXml(): DOMDocument;

    /**
     * Converte a entidade para string XML
     *
     * @param bool $formatOutput Se deve formatar a saída
     * @return string
     */
    public function toXmlString(bool $formatOutput = false): string
    {
        $doc = $this->toXml();
        return XmlHandler::toString($doc, $formatOutput);
    }

    /**
     * Valida a entidade
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate(): bool
    {
        $xml = $this->toXmlString();

        if (!XmlHandler::isValid($xml)) {
            throw new ValidationException("XML da entidade é inválido");
        }

        return true;
    }

    /**
     * Assina o XML com certificado digital usando XMLDSIG
     *
     * @param \OpenSSLCertificate $certificate
     * @param \OpenSSLAsymmetricKey $privateKey
     * @return string XML assinado
     */
    public function sign(\OpenSSLCertificate $certificate, \OpenSSLAsymmetricKey $privateKey): string
    {
        $doc = $this->toXml();
        $rootNode = $doc->documentElement;

        if ($rootNode === null) {
            throw new ValidationException("XML não possui elemento raiz");
        }

        // Cria o objeto de assinatura
        $objDSig = new XMLSecurityDSig();
        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
        $objDSig->addReferenceList(
            [$rootNode],
            XMLSecurityDSig::SHA256,
            ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
            ['id_name' => 'Id', 'overwrite' => false]
        );

        // Cria a chave de segurança
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);

        // Exporta a chave privada para formato PEM
        $pemKey = '';
        openssl_pkey_export($privateKey, $pemKey);
        $objKey->loadKey($pemKey, false);

        // Adiciona a chave ao documento
        $objDSig->sign($objKey);

        // Adiciona o certificado
        $certData = '';
        openssl_x509_export($certificate, $certData);
        $objDSig->add509Cert($certData, true);

        return $doc->saveXML();
    }

    /**
     * Converte para XML assinado e comprimido (GZip + base64)
     *
     * @param \OpenSSLCertificate|null $certificate
     * @param \OpenSSLAsymmetricKey|null $privateKey
     * @return string XML comprimido e codificado em base64
     */
    public function toSignedAndCompressed(?\OpenSSLCertificate $certificate = null, ?\OpenSSLAsymmetricKey $privateKey = null): string
    {
        $xmlString = $this->toXmlString();

        // Se certificado e chave foram fornecidos, assina o XML
        if ($certificate !== null && $privateKey !== null) {
            $xmlString = $this->sign($certificate, $privateKey);
        }

        // Comprime e codifica em base64
        return CompressionHandler::compressAndEncode($xmlString);
    }
}

