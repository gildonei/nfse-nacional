<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Contract;

/**
 * Interface para cliente HTTP
 *
 * @package NfseNacional\Domain\Contract
 */
interface HttpClientInterface
{
    /**
     * Realiza uma requisição POST
     *
     * @param string $url URL da requisição
     * @param array $dados Dados a serem enviados
     * @param array $headers Headers adicionais (opcional)
     * @return array Resposta da requisição
     * @throws \Exception
     */
    public function post(string $url, array $dados, array $headers = []): array;

    /**
     * Realiza uma requisição GET
     *
     * @param string $url URL da requisição
     * @param array $headers Headers adicionais (opcional)
     * @return array Resposta da requisição
     * @throws \Exception
     */
    public function get(string $url, array $headers = []): array;
}
