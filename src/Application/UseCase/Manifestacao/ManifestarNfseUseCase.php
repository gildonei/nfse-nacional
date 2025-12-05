<?php

declare(strict_types=1);

namespace NfseNacional\Application\UseCase\Manifestacao;

use NfseNacional\Application\Contract\Gateway\NfseGatewayInterface;
use NfseNacional\Application\DTO\Response\MensagemProcessamentoDTO;
use NfseNacional\Application\Exception\ApplicationException;
use NfseNacional\Shared\Enum\TipoManifestacao;

/**
 * Request DTO para manifestação de NFS-e
 */
final class ManifestarNfseRequest
{
    public function __construct(
        public readonly string $chaveAcesso,
        public readonly TipoManifestacao|string $tipoManifestacao,
        public readonly ?string $motivo = null
    ) {
    }
}

/**
 * Response DTO para manifestação de NFS-e
 */
final class ManifestarNfseResponse
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
 * Caso de uso para manifestação de NFS-e
 */
class ManifestarNfseUseCase
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
    public function execute(ManifestarNfseRequest $request): ManifestarNfseResponse
    {
        try {
            $tipoManifestacao = $request->tipoManifestacao instanceof TipoManifestacao
                ? $request->tipoManifestacao->value
                : $request->tipoManifestacao;

            $response = $this->gateway->manifestar(
                $request->chaveAcesso,
                $tipoManifestacao,
                $request->motivo
            );

            return new ManifestarNfseResponse(
                sucesso: $response->sucesso,
                protocolo: $response->protocolo,
                alertas: $response->alertas,
                erros: $response->erros
            );
        } catch (\Throwable $e) {
            throw new ApplicationException(
                "Erro ao manifestar NFS-e: " . $e->getMessage(),
                previous: $e
            );
        }
    }
}

