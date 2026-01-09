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

    /**
     * Construtor
     *
     * @param array $config Configurações do cliente Guzzle (opcional)
     */
    public function __construct(array $config = [])
    {
        $defaultConfig = [
            'timeout' => 30,
            'verify' => true,
            'http_errors' => false,
        ];

        $this->client = new Client(array_merge($defaultConfig, $config));
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
