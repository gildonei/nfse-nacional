<?php

declare(strict_types=1);

namespace NfseNacional\Application\UseCase\Cancelamento;

use NfseNacional\Application\Contract\Gateway\NfseGatewayInterface;
use NfseNacional\Application\DTO\Response\MensagemProcessamentoDTO;
use NfseNacional\Application\Exception\ApplicationException;

/**
 * Request DTO para cancelamento de NFS-e
 */
final class CancelarNfseRequest
{
    public function __construct(
        public readonly string $chaveAcesso,
        public readonly string $codigoCancelamento,
        public readonly string $motivo
    ) {
    }
}

/**
 * Response DTO para cancelamento de NFS-e
 */
final class CancelarNfseResponse
{
    /**
     * @param bool $sucesso
     * @param string|null $protocolo
     * @param MensagemProcessamentoDTO[] $alertas
     * @param MensagemProcessamentoDTO[] $erros
     */
    public function __construct(
        public readonly bool $sucesso,
        public readonly ?string $protocolo = null,
        public readonly array $alertas = [],
        public readonly array $erros = []
    ) {
    }
}

/**
 * Caso de uso para cancelamento de NFS-e
 */
class CancelarNfseUseCase
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
    public function execute(CancelarNfseRequest $request): CancelarNfseResponse
    {
        try {
            // Validações básicas
            if (empty($request->chaveAcesso)) {
                return new CancelarNfseResponse(
                    sucesso: false,
                    erros: [new MensagemProcessamentoDTO('VALIDATION', 'Chave de acesso é obrigatória')]
                );
            }

            if (empty($request->codigoCancelamento)) {
                return new CancelarNfseResponse(
                    sucesso: false,
                    erros: [new MensagemProcessamentoDTO('VALIDATION', 'Código de cancelamento é obrigatório')]
                );
            }

            if (empty($request->motivo)) {
                return new CancelarNfseResponse(
                    sucesso: false,
                    erros: [new MensagemProcessamentoDTO('VALIDATION', 'Motivo do cancelamento é obrigatório')]
                );
            }

            $response = $this->gateway->cancelar(
                $request->chaveAcesso,
                $request->codigoCancelamento,
                $request->motivo
            );

            return new CancelarNfseResponse(
                sucesso: $response->sucesso,
                protocolo: $response->protocolo,
                alertas: $response->alertas,
                erros: $response->erros
            );
        } catch (\Throwable $e) {
            throw new ApplicationException(
                "Erro ao cancelar NFS-e: " . $e->getMessage(),
                previous: $e
            );
        }
    }
}

