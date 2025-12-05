<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Contract;

/**
 * Interface para entidades que podem ser assinadas digitalmente
 */
interface SignableInterface
{
    /**
     * Assina o XML com certificado digital
     *
     * @param \OpenSSLCertificate $certificate Certificado digital
     * @param \OpenSSLAsymmetricKey $privateKey Chave privada
     * @return string XML assinado
     */
    public function sign(\OpenSSLCertificate $certificate, \OpenSSLAsymmetricKey $privateKey): string;

    /**
     * Retorna o XML assinado e comprimido em base64
     *
     * @param \OpenSSLCertificate|null $certificate Certificado digital
     * @param \OpenSSLAsymmetricKey|null $privateKey Chave privada
     * @return string XML assinado e comprimido em base64
     */
    public function toSignedAndCompressed(?\OpenSSLCertificate $certificate = null, ?\OpenSSLAsymmetricKey $privateKey = null): string;
}

