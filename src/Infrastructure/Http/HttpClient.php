<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use NfseNacional\Domain\Contract\HttpClientInterface;

/**
 * Implementação de cliente HTTP usando Guzzle
 *
 * @package NfseNacional\Infrastructure\Http
 */
class HttpClient implements HttpClientInterface
{
    private Client $client;
    private ?string $certificadoTemp = null;
    private ?string $chavePrivadaTemp = null;

    /**
     * Construtor
     *
     * @param array $config Configurações do cliente Guzzle (opcional)
     * @param string|null $certificadoP12 Conteúdo do certificado PKCS#12 para autenticação SSL/TLS (opcional)
     * @param string|null $senhaCertificado Senha do certificado PKCS#12 (opcional)
     */
    public function __construct(array $config = [], ?string $certificadoP12 = null, ?string $senhaCertificado = null)
    {
        $defaultConfig = [
            'timeout' => 30,
            'verify' => true,
            'http_errors' => false,
        ];

        // Se certificado foi fornecido, configura autenticação SSL/TLS
        if ($certificadoP12 !== null && $senhaCertificado !== null) {
            $this->configurarCertificadoSSL($certificadoP12, $senhaCertificado, $defaultConfig);
        }

        $this->client = new Client(array_merge($defaultConfig, $config));
    }

    /**
     * Configura autenticação SSL/TLS usando certificado PKCS#12
     *
     * @param string $certificadoP12 Conteúdo do certificado PKCS#12
     * @param string $senhaCertificado Senha do certificado
     * @param array &$config Array de configuração do Guzzle (modificado por referência)
     * @throws Exception
     */
    private function configurarCertificadoSSL(string $certificadoP12, string $senhaCertificado, array &$config): void
    {
        // Verifica se a extensão OpenSSL está disponível
        if (!extension_loaded('openssl')) {
            throw new Exception('Extensão OpenSSL não está disponível para autenticação SSL/TLS!');
        }

        // Tenta ler o certificado PKCS#12
        $certInfo = [];
        if (!openssl_pkcs12_read($certificadoP12, $certInfo, $senhaCertificado)) {
            $error = openssl_error_string();
            throw new Exception('Erro ao ler certificado PKCS#12 para autenticação SSL/TLS: ' . ($error ?: 'Erro desconhecido'));
        }

        // Cria arquivos temporários para certificado e chave privada
        $this->certificadoTemp = tempnam(sys_get_temp_dir(), 'cert_');
        $this->chavePrivadaTemp = tempnam(sys_get_temp_dir(), 'key_');

        if ($this->certificadoTemp === false || $this->chavePrivadaTemp === false) {
            throw new Exception('Erro ao criar arquivos temporários para certificado SSL/TLS!');
        }

        // Escreve o certificado e a chave privada nos arquivos temporários
        if (file_put_contents($this->certificadoTemp, $certInfo['cert']) === false) {
            throw new Exception('Erro ao escrever certificado em arquivo temporário!');
        }

        if (file_put_contents($this->chavePrivadaTemp, $certInfo['pkey']) === false) {
            throw new Exception('Erro ao escrever chave privada em arquivo temporário!');
        }

        // Configura o Guzzle para usar o certificado e a chave privada
        $config['cert'] = $this->certificadoTemp;
        $config['ssl_key'] = $this->chavePrivadaTemp;
    }

    /**
     * Destrutor - limpa arquivos temporários
     */
    public function __destruct()
    {
        // Remove arquivos temporários se existirem
        if ($this->certificadoTemp !== null && file_exists($this->certificadoTemp)) {
            @unlink($this->certificadoTemp);
        }

        if ($this->chavePrivadaTemp !== null && file_exists($this->chavePrivadaTemp)) {
            @unlink($this->chavePrivadaTemp);
        }
    }

    /**
     * Realiza uma requisição POST
     *
     * @param string $url URL da requisição
     * @param array $dados Dados a serem enviados
     * @param array $headers Headers adicionais (opcional)
     * @return array Resposta da requisição
     * @throws Exception
     */
    public function post(string $url, array $dados, array $headers = []): array
    {
        try {
            $defaultHeaders = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];

            $response = $this->client->post($url, [
                'headers' => array_merge($defaultHeaders, $headers),
                'json' => $dados,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            $resultado = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'statusCode' => $statusCode,
                    'body' => $body,
                    'raw' => $body,
                ];
            }

            return [
                'statusCode' => $statusCode,
                'body' => $resultado,
                'raw' => $body,
            ];
        } catch (GuzzleException $e) {
            throw new Exception('Erro na requisição HTTP: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Realiza uma requisição GET
     *
     * @param string $url URL da requisição
     * @param array $headers Headers adicionais (opcional)
     * @return array Resposta da requisição
     * @throws Exception
     */
    public function get(string $url, array $headers = []): array
    {
        try {
            $defaultHeaders = [
                'Accept' => 'application/json',
            ];

            $response = $this->client->get($url, [
                'headers' => array_merge($defaultHeaders, $headers),
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            $resultado = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'statusCode' => $statusCode,
                    'body' => $body,
                    'raw' => $body,
                ];
            }

            return [
                'statusCode' => $statusCode,
                'body' => $resultado,
                'raw' => $body,
            ];
        } catch (GuzzleException $e) {
            throw new Exception('Erro na requisição HTTP: ' . $e->getMessage(), 0, $e);
        }
    }
}
