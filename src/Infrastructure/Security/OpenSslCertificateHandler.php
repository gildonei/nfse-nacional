<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Security;

use NfseNacional\Application\Contract\Security\CertificateHandlerInterface;
use NfseNacional\Shared\Exception\CertificateException;
use OpenSSLAsymmetricKey;
use OpenSSLCertificate;

/**
 * Implementação do CertificateHandler usando OpenSSL
 */
class OpenSslCertificateHandler implements CertificateHandlerInterface
{
    private ?OpenSSLCertificate $certificate = null;
    private ?OpenSSLAsymmetricKey $privateKey = null;
    private string $certificatePem = '';
    private string $privateKeyPem = '';

    /**
     * @param string|null $certificatePath Caminho para o certificado (opcional)
     * @param string|null $password Senha do certificado (opcional)
     * @throws CertificateException
     */
    public function __construct(?string $certificatePath = null, ?string $password = null)
    {
        if ($certificatePath !== null && $password !== null) {
            $this->loadFromFile($certificatePath, $password);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadFromFile(string $path, string $password): void
    {
        if (!file_exists($path)) {
            throw new CertificateException("Arquivo de certificado não encontrado: {$path}");
        }

        if (!is_readable($path)) {
            throw new CertificateException("Arquivo de certificado não pode ser lido: {$path}");
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new CertificateException("Não foi possível ler o arquivo de certificado: {$path}");
        }

        $this->loadFromContent($content, $password);
    }

    /**
     * {@inheritdoc}
     */
    public function loadFromContent(string $content, string $password): void
    {
        $certificates = [];
        if (!openssl_pkcs12_read($content, $certificates, $password)) {
            throw new CertificateException(
                "Não foi possível ler o certificado PKCS#12. Verifique se a senha está correta e se o arquivo é válido."
            );
        }

        if (!isset($certificates['cert']) || !isset($certificates['pkey'])) {
            throw new CertificateException("Certificado inválido: certificado ou chave privada não encontrados");
        }

        $this->certificatePem = $certificates['cert'];
        $this->privateKeyPem = $certificates['pkey'];

        $certificate = openssl_x509_read($certificates['cert']);
        if ($certificate === false) {
            throw new CertificateException("Não foi possível processar o certificado");
        }
        $this->certificate = $certificate;

        $privateKey = openssl_pkey_get_private($certificates['pkey']);
        if ($privateKey === false) {
            throw new CertificateException("Não foi possível processar a chave privada");
        }
        $this->privateKey = $privateKey;

        $this->validateCertificate();
    }

    /**
     * Valida se o certificado é ICP-Brasil
     */
    private function validateCertificate(): void
    {
        $certData = openssl_x509_parse($this->certificate);
        if ($certData === false) {
            throw new CertificateException("Não foi possível validar o certificado");
        }

        $issuer = $certData['issuer']['CN'] ?? '';
        if (stripos($issuer, 'ICP-Brasil') === false && stripos($issuer, 'AC') === false) {
            throw new CertificateException(
                "Certificado não é emitido por uma Autoridade Certificadora ICP-Brasil"
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCertificate(): OpenSSLCertificate
    {
        if ($this->certificate === null) {
            throw new CertificateException("Certificado não foi carregado");
        }
        return $this->certificate;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrivateKey(): OpenSSLAsymmetricKey
    {
        if ($this->privateKey === null) {
            throw new CertificateException("Chave privada não foi carregada");
        }
        return $this->privateKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getCertificatePem(): string
    {
        return $this->certificatePem;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrivateKeyPem(): string
    {
        return $this->privateKeyPem;
    }

    /**
     * {@inheritdoc}
     */
    public function getInfo(): array
    {
        if ($this->certificate === null) {
            return [];
        }

        $certData = openssl_x509_parse($this->certificate);
        if ($certData === false) {
            return [];
        }

        return [
            'cnpj' => $this->getCnpj(),
            'subject' => $certData['subject'] ?? [],
            'issuer' => $certData['issuer'] ?? [],
            'valid_from' => $certData['validFrom_time_t'] ?? null,
            'valid_to' => $certData['validTo_time_t'] ?? null,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        if ($this->certificate === null) {
            return false;
        }

        $certData = openssl_x509_parse($this->certificate);
        if ($certData === false) {
            return false;
        }

        $validTo = $certData['validTo_time_t'] ?? 0;
        return time() < $validTo;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpirationDate(): \DateTimeInterface
    {
        if ($this->certificate === null) {
            throw new CertificateException("Certificado não foi carregado");
        }

        $certData = openssl_x509_parse($this->certificate);
        if ($certData === false) {
            throw new CertificateException("Não foi possível obter data de expiração");
        }

        $validTo = $certData['validTo_time_t'] ?? 0;
        return new \DateTime("@{$validTo}");
    }

    /**
     * {@inheritdoc}
     */
    public function getCnpj(): ?string
    {
        if ($this->certificate === null) {
            return null;
        }

        $certData = openssl_x509_parse($this->certificate);
        if ($certData === false) {
            return null;
        }

        $subject = $certData['subject'];
        $cnpj = $subject['serialNumber'] ?? null;

        if ($cnpj !== null) {
            $cnpj = preg_replace('/\D/', '', $cnpj);
        }

        return $cnpj;
    }
}

