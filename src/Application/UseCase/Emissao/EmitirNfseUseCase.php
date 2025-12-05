<?php

declare(strict_types=1);

namespace NfseNacional\Application\UseCase\Emissao;

use NfseNacional\Application\Contract\Gateway\NfseGatewayInterface;
use NfseNacional\Application\DTO\Response\MensagemProcessamentoDTO;
use NfseNacional\Application\Exception\ApplicationException;
use NfseNacional\Domain\Exception\ValidationException;

/**
 * Caso de uso para emissão de NFS-e
 */
class EmitirNfseUseCase
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
    public function execute(EmitirNfseRequest $request): EmitirNfseResponse
    {
        try {
            // Valida a DPS
            $request->dps->validate();

            // Emite a NFS-e através do gateway
            $response = $this->gateway->emitir($request->dps);

            if (!$response->sucesso) {
                return EmitirNfseResponse::error($response->erros);
            }

            return EmitirNfseResponse::success(
                nfse: $response->nfse,
                protocolo: $response->protocolo
            );
        } catch (ValidationException $e) {
            $erros = [];
            foreach ($e->getErrors() as $field => $messages) {
                foreach ($messages as $message) {
                    $erros[] = new MensagemProcessamentoDTO(
                        codigo: 'VALIDATION',
                        descricao: "{$field}: {$message}"
                    );
                }
            }
            return EmitirNfseResponse::error($erros);
        } catch (\Throwable $e) {
            throw new ApplicationException(
                "Erro ao emitir NFS-e: " . $e->getMessage(),
                previous: $e
            );
        }
    }
}

