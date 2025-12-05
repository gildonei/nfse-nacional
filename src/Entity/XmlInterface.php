<?php

declare(strict_types=1);

namespace NfseNacional\Entity;

use DOMDocument;

/**
 * Interface para entidades XML que podem ser convertidas para XML assinado e comprimido
 */
interface XmlInterface
{
    /**
     * Converte a entidade para XML (DOMDocument)
     *
     * @return DOMDocument
     */
    public function toXml(): DOMDocument;

    /**
     * Converte a entidade para string XML
     *
     * @param bool $formatOutput Se deve formatar a saída
     * @return string
     */
    public function toXmlString(bool $formatOutput = false): string;

    /**
     * Valida a entidade
     *
     * @return bool
     * @throws \NfseNacional\Exceptions\ValidationException
     */
    public function validate(): bool;

    /**
     * Assina o XML com certificado digital
     *
     * @param \OpenSSLCertificate $certificate
     * @param \OpenSSLAsymmetricKey $privateKey
     * @return string XML assinado
     */
    public function sign(\OpenSSLCertificate $certificate, \OpenSSLAsymmetricKey $privateKey): string;

    /**
     * Converte para XML assinado e comprimido (GZip + base64)
     * Pronto para envio à API
     *
     * @param \OpenSSLCertificate|null $certificate
     * @param \OpenSSLAsymmetricKey|null $privateKey
     * @return string XML comprimido e codificado em base64
     */
    public function toSignedAndCompressed(?\OpenSSLCertificate $certificate = null, ?\OpenSSLAsymmetricKey $privateKey = null): string;
}

