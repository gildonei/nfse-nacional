<?php

declare(strict_types=1);

namespace NfseNacional\Application\Contract\Xml;

use DOMDocument;

/**
 * Interface para assinatura digital de XML
 */
interface XmlSignerInterface
{
    /**
     * Assina um XML com certificado digital
     *
     * @param DOMDocument $doc Documento XML a ser assinado
     * @param \OpenSSLCertificate $certificate Certificado digital
     * @param \OpenSSLAsymmetricKey $privateKey Chave privada
     * @param string|null $referenceId ID do elemento a ser assinado (opcional)
     * @return string XML assinado
     */
    public function sign(
        DOMDocument $doc,
        \OpenSSLCertificate $certificate,
        \OpenSSLAsymmetricKey $privateKey,
        ?string $referenceId = null
    ): string;

    /**
     * Verifica a assinatura de um XML
     *
     * @param string $signedXml XML assinado
     * @return bool True se a assinatura for válida
     */
    public function verify(string $signedXml): bool;
}

