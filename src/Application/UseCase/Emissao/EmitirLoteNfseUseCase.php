<?php

declare(strict_types=1);

namespace NfseNacional\Application\UseCase\Emissao;

use NfseNacional\Application\Contract\Gateway\NfseGatewayInterface;
use NfseNacional\Application\DTO\Response\MensagemProcessamentoDTO;
use NfseNacional\Application\Exception\ApplicationException;
use NfseNacional\Domain\Entity\Dps;
use NfseNacional\Domain\Exception\ValidationException;

/**
 * Request DTO para emissão de lote de NFS-e
 */
final class EmitirLoteNfseRequest
{
    /**
     * @param Dps[] $lote
     */
    public function __construct(
        public readonly array $lote
    ) {
    }
}

/**
 * Response DTO para emissão de lote de NFS-e
 */
final class EmitirLoteNfseResponse
{
    /**
     * @param bool $sucesso
     * @param string|null $protocolo
     * @param array $nfsesEmitidas
     * @param MensagemProcessamentoDTO[] $alertas
     * @param MensagemProcessamentoDTO[] $erros
     */
    public function __construct(
        public readonly bool $sucesso,
        public readonly ?string $protocolo = null,
        public readonly array $nfsesEmitidas = [],
        public readonly array $alertas = [],
        public readonly array $erros = []
    ) {
    }
}

/**
 * Caso de uso para emissão de lote de NFS-e
 */
class EmitirLoteNfseUseCase
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
    public function execute(EmitirLoteNfseRequest $request): EmitirLoteNfseResponse
    {
        try {
            // Valida todas as DPS do lote
            foreach ($request->lote as $index => $dps) {
                try {
                    $dps->validate();
                } catch (ValidationException $e) {
                    $erros = [];
                    foreach ($e->getErrors() as $field => $messages) {
                        foreach ($messages as $message) {
                            $erros[] = new MensagemProcessamentoDTO(
                                codigo: 'VALIDATION',
                                descricao: "DPS {$index} - {$field}: {$message}"
                            );
                        }
                    }
                    return new EmitirLoteNfseResponse(sucesso: false, erros: $erros);
                }
            }

            // Emite o lote através do gateway
            $response = $this->gateway->emitirLote($request->lote);

            return new EmitirLoteNfseResponse(
                sucesso: $response->sucesso,
                protocolo: $response->protocolo,
                nfsesEmitidas: $response->nfse ? [$response->nfse] : [],
                alertas: $response->alertas,
                erros: $response->erros
            );
        } catch (\Throwable $e) {
            throw new ApplicationException(
                "Erro ao emitir lote de NFS-e: " . $e->getMessage(),
                previous: $e
            );
        }
    }
}

