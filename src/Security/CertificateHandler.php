<?php

declare(strict_types=1);

namespace NfseNacional\Security;

use NfseNacional\Exceptions\CertificateException;
use OpenSSLAsymmetricKey;
use OpenSSLCertificate;

/**
 * Classe para gerenciamento de certificado digital ICP-Brasil
 */
class CertificateHandler
{
    private OpenSSLCertificate $certificate;
    private OpenSSLAsymmetricKey $privateKey;
    private string $password;

    /**
     * @param string $certificatePath Caminho para o arquivo do certificado (.pfx ou .p12)
     * @param string $password Senha do certificado
     * @throws CertificateException
     */
    public function __construct(string $certificatePath, string $password)
    {
        $this->password = $password;
        $this->loadCertificate($certificatePath);
    }

    /**
     * Carrega o certificado digital
     *
     * @param string $certificatePath
     * @throws CertificateException
     */
    private function loadCertificate(string $certificatePath): void
    {
        if (!file_exists($certificatePath)) {
            throw new CertificateException("Arquivo de certificado não encontrado: {$certificatePath}");
        }

        if (!is_readable($certificatePath)) {
            throw new CertificateException("Arquivo de certificado não pode ser lido: {$certificatePath}");
        }

        $certificateContent = file_get_contents($certificatePath);
        if ($certificateContent === false) {
            throw new CertificateException("Não foi possível ler o arquivo de certificado: {$certificatePath}");
        }

        $certificates = [];
        if (!openssl_pkcs12_read($certificateContent, $certificates, $this->password)) {
            throw new CertificateException(
                "Não foi possível ler o certificado PKCS#12. Verifique se a senha está correta e se o arquivo é válido."
            );
        }

        if (!isset($certificates['cert']) || !isset($certificates['pkey'])) {
            throw new CertificateException("Certificado inválido: certificado ou chave privada não encontrados");
        }

        $this->certificate = openssl_x509_read($certificates['cert']);
        if ($this->certificate === false) {
            throw new CertificateException("Não foi possível processar o certificado");
        }

        $this->privateKey = openssl_pkey_get_private($certificates['pkey'], $this->password);
        if ($this->privateKey === false) {
            throw new CertificateException("Não foi possível processar a chave privada");
        }

        $this->validateCertificate();
    }

    /**
     * Valida se o certificado é ICP-Brasil
     *
     * @throws CertificateException
     */
    private function validateCertificate(): void
    {
        $certData = openssl_x509_parse($this->certificate);
        if ($certData === false) {
            throw new CertificateException("Não foi possível validar o certificado");
        }

        // Verifica se o certificado contém a cadeia ICP-Brasil
        $issuer = $certData['issuer']['CN'] ?? '';
        if (stripos($issuer, 'ICP-Brasil') === false && stripos($issuer, 'AC') === false) {
            throw new CertificateException(
                "Certificado não é emitido por uma Autoridade Certificadora ICP-Brasil"
            );
        }
    }

    /**
     * Retorna o certificado OpenSSL
     */
    public function getCertificate(): OpenSSLCertificate
    {
        return $this->certificate;
    }

    /**
     * Retorna a chave privada OpenSSL
     */
    public function getPrivateKey(): OpenSSLAsymmetricKey
    {
        return $this->privateKey;
    }

    /**
     * Retorna o CNPJ do certificado
     *
     * @return string|null
     */
    public function getCnpj(): ?string
    {
        $certData = openssl_x509_parse($this->certificate);
        if ($certData === false) {
            return null;
        }

        $subject = $certData['subject'];
        $cnpj = $subject['serialNumber'] ?? null;

        // Remove caracteres não numéricos
        if ($cnpj !== null) {
            $cnpj = preg_replace('/\D/', '', $cnpj);
        }

        return $cnpj;
    }

    /**
     * Retorna informações do certificado
     *
     * @return array
     */
    public function getCertificateInfo(): array
    {
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
     * Verifica se o certificado está válido
     *
     * @return bool
     */
    public function isValid(): bool
    {
        $certData = openssl_x509_parse($this->certificate);
        if ($certData === false) {
            return false;
        }

        $validTo = $certData['validTo_time_t'] ?? 0;
        return time() < $validTo;
    }
}

