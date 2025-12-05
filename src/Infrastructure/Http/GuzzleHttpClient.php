<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use NfseNacional\Application\Contract\Security\CertificateHandlerInterface;
use NfseNacional\Shared\Enum\TipoAmbiente;
use NfseNacional\Shared\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Cliente HTTP usando Guzzle com suporte a certificado digital
 */
class GuzzleHttpClient
{
    private Client $client;
    private CertificateHandlerInterface $certificateHandler;
    private TipoAmbiente $ambiente;

    private const BASE_URL_PRODUCAO = 'https://nfse-nacional.receita.economia.gov.br/';
    private const BASE_URL_HOMOLOGACAO = 'https://hom.nfse.fazenda.gov.br/';

    public function __construct(
        CertificateHandlerInterface $certificateHandler,
        TipoAmbiente $ambiente = TipoAmbiente::HOMOLOGACAO,
        array $options = []
    ) {
        $this->certificateHandler = $certificateHandler;
        $this->ambiente = $ambiente;

        $baseUri = $ambiente === TipoAmbiente::PRODUCAO
            ? self::BASE_URL_PRODUCAO
            : self::BASE_URL_HOMOLOGACAO;

        $defaultOptions = [
            'base_uri' => $baseUri,
            'timeout' => 30,
            'connect_timeout' => 10,
            'verify' => true,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ];

        $this->client = new Client(array_merge($defaultOptions, $options));
    }

    /**
     * Realiza uma requisição GET
     *
     * @param string $endpoint
     * @param array<string, mixed> $queryParams
     * @return array<string, mixed>
     * @throws ApiException
     */
    public function get(string $endpoint, array $queryParams = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $queryParams]);
    }

    /**
     * Realiza uma requisição POST
     *
     * @param string $endpoint
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws ApiException
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    /**
     * Realiza uma requisição PUT
     *
     * @param string $endpoint
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws ApiException
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * Realiza uma requisição DELETE
     *
     * @param string $endpoint
     * @return array<string, mixed>
     * @throws ApiException
     */
    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Realiza uma requisição HTTP
     *
     * @param string $method
     * @param string $endpoint
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     * @throws ApiException
     */
    private function request(string $method, string $endpoint, array $options = []): array
    {
        // Adiciona certificado SSL
        $options['cert'] = [
            $this->getCertificateTempPath(),
            ''
        ];
        $options['ssl_key'] = [
            $this->getPrivateKeyTempPath(),
            ''
        ];

        try {
            $response = $this->client->request($method, $endpoint, $options);
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw new ApiException(
                "Erro na requisição HTTP: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Parseia a resposta da API
     *
     * @param ResponseInterface $response
     * @return array<string, mixed>
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();

        if (empty($body)) {
            return [];
        }

        $data = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException("Resposta da API não é um JSON válido");
        }

        return $data;
    }

    /**
     * Cria arquivo temporário com o certificado
     */
    private function getCertificateTempPath(): string
    {
        $tempPath = sys_get_temp_dir() . '/nfse_cert_' . md5(spl_object_id($this)) . '.pem';
        file_put_contents($tempPath, $this->certificateHandler->getCertificatePem());
        return $tempPath;
    }

    /**
     * Cria arquivo temporário com a chave privada
     */
    private function getPrivateKeyTempPath(): string
    {
        $tempPath = sys_get_temp_dir() . '/nfse_key_' . md5(spl_object_id($this)) . '.pem';
        file_put_contents($tempPath, $this->certificateHandler->getPrivateKeyPem());
        return $tempPath;
    }

    /**
     * Retorna o ambiente atual
     */
    public function getAmbiente(): TipoAmbiente
    {
        return $this->ambiente;
    }
}

