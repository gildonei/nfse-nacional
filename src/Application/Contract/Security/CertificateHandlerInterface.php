<?php

declare(strict_types=1);

namespace NfseNacional\Application\Contract\Security;

/**
 * Interface para manipulação de certificados digitais
 */
interface CertificateHandlerInterface
{
    /**
     * Carrega um certificado a partir de um arquivo
     *
     * @param string $path Caminho para o arquivo do certificado (.pfx ou .p12)
     * @param string $password Senha do certificado
     */
    public function loadFromFile(string $path, string $password): void;

    /**
     * Carrega um certificado a partir de conteúdo em memória
     *
     * @param string $content Conteúdo do certificado
     * @param string $password Senha do certificado
     */
    public function loadFromContent(string $content, string $password): void;

    /**
     * Retorna o certificado público
     */
    public function getCertificate(): \OpenSSLCertificate;

    /**
     * Retorna a chave privada
     */
    public function getPrivateKey(): \OpenSSLAsymmetricKey;

    /**
     * Retorna o certificado em formato PEM
     */
    public function getCertificatePem(): string;

    /**
     * Retorna a chave privada em formato PEM
     */
    public function getPrivateKeyPem(): string;

    /**
     * Retorna informações do certificado
     *
     * @return array<string, mixed>
     */
    public function getInfo(): array;

    /**
     * Verifica se o certificado está válido (não expirado)
     */
    public function isValid(): bool;

    /**
     * Retorna a data de expiração do certificado
     */
    public function getExpirationDate(): \DateTimeInterface;

    /**
     * Retorna o CNPJ do certificado
     */
    public function getCnpj(): ?string;
}

