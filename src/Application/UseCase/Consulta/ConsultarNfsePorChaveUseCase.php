<?php

declare(strict_types=1);

namespace NfseNacional\Application\UseCase\Consulta;

use NfseNacional\Application\Contract\Gateway\NfseGatewayInterface;
use NfseNacional\Application\DTO\Response\MensagemProcessamentoDTO;
use NfseNacional\Application\DTO\Response\NfseResponseDTO;
use NfseNacional\Application\Exception\ApplicationException;
use NfseNacional\Domain\Entity\Nfse;

/**
 * Request DTO para consulta de NFS-e por chave de acesso
 */
final class ConsultarNfsePorChaveRequest
{
    public function __construct(
        public readonly string $chaveAcesso
    ) {
    }
}

/**
 * Response DTO para consulta de NFS-e
 */
final class ConsultarNfsePorChaveResponse
{
    /**
     * @param bool $encontrada
     * @param Nfse|null $nfse
     * @param MensagemProcessamentoDTO[] $alertas
     * @param MensagemProcessamentoDTO[] $erros
     */
    public function __construct(
        public readonly bool $encontrada,
        public readonly ?Nfse $nfse = null,
        public readonly array $alertas = [],
        public readonly array $erros = []
    ) {
    }
}

/**
 * Caso de uso para consulta de NFS-e por chave de acesso
 */
class ConsultarNfsePorChaveUseCase
{
    public function __construct(
        private readonly NfseGatewayInterface $gateway
    ) {
    }

    /**
     * Executa o caso de uso
     *
     * @throws ApplicationException
     */
    public function execute(ConsultarNfsePorChaveRequest $request): ConsultarNfsePorChaveResponse
    {
        try {
            $response = $this->gateway->consultarPorChave($request->chaveAcesso);

            return new ConsultarNfsePorChaveResponse(
                encontrada: $response->sucesso && $response->nfse !== null,
                nfse: $response->nfse,
                alertas: $response->alertas,
                erros: $response->erros
            );
        } catch (\Throwable $e) {
            throw new ApplicationException(
                "Erro ao consultar NFS-e: " . $e->getMessage(),
                previous: $e
            );
        }
    }
}

