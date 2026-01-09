<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject;

use InvalidArgumentException;

/**
 * Value Object para Certificado Digital
 *
 * Encapsula o certificado digital PKCS#12 e sua senha
 *
 * @package NfseNacional\Domain\ValueObject
 */
class Certificado
{
    /**
     * Conteúdo do certificado digital PKCS#12
     * @var string
     */
    private string $conteudo;

    /**
     * Senha do certificado digital
     * @var string
     */
    private string $senha;

    /**
     * Construtor
     *
     * @param string $certificado Caminho do arquivo ou conteúdo do certificado PKCS#12
     * @param string $senha Senha do certificado
     * @throws InvalidArgumentException
     */
    public function __construct(string $certificado, string $senha)
    {
        $certificado = trim($certificado);
        $senha = trim($senha);

        if (empty($certificado)) {
            throw new InvalidArgumentException('Certificado não pode estar vazio!');
        }

        if (empty($senha)) {
            throw new InvalidArgumentException('Senha do certificado não pode estar vazia!');
        }

        // Verifica se é um caminho de arquivo e se existe
        if (file_exists($certificado)) {
            $conteudo = file_get_contents($certificado);
            if ($conteudo === false) {
                throw new InvalidArgumentException('Não foi possível ler o arquivo do certificado!');
            }
            $this->conteudo = $conteudo;
        } else {
            // Assume que é o conteúdo do certificado
            $this->conteudo = $certificado;
        }

        $this->senha = $senha;
    }

    /**
     * Retorna o conteúdo do certificado
     *
     * @return string Conteúdo do certificado PKCS#12
     */
    public function obterConteudo(): string
    {
        return $this->conteudo;
    }

    /**
     * Retorna a senha do certificado
     *
     * @return string Senha do certificado
     */
    public function obterSenha(): string
    {
        return $this->senha;
    }

    /**
     * Valida se o certificado é válido
     *
     * @return bool True se válido
     * @throws InvalidArgumentException
     */
    public function validar(): bool
    {
        // Verifica se a extensão OpenSSL está disponível
        if (!extension_loaded('openssl')) {
            throw new InvalidArgumentException('Extensão OpenSSL não está disponível!');
        }

        // Tenta ler o certificado PKCS#12 para validar
        $certInfo = [];
        if (!openssl_pkcs12_read($this->conteudo, $certInfo, $this->senha)) {
            $error = openssl_error_string();
            throw new InvalidArgumentException('Certificado PKCS#12 inválido ou senha incorreta: ' . ($error ?: 'Erro desconhecido'));
        }

        return true;
    }

    /**
     * Retorna representação em string (apenas para debug, não expõe dados sensíveis)
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Certificado[***]';
    }
}
