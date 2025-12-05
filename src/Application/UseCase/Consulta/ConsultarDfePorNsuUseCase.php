<?php

declare(strict_types=1);

namespace NfseNacional\Application\UseCase\Consulta;

use NfseNacional\Application\Contract\Gateway\NfseGatewayInterface;
use NfseNacional\Application\DTO\Response\DistribuicaoItemDTO;
use NfseNacional\Application\DTO\Response\LoteDistribuicaoDTO;
use NfseNacional\Application\DTO\Response\MensagemProcessamentoDTO;
use NfseNacional\Application\Exception\ApplicationException;
use NfseNacional\Shared\Enum\StatusProcessamento;
use NfseNacional\Shared\Enum\TipoAmbiente;

/**
 * Request DTO para consulta de DFe por NSU
 */
final class ConsultarDfePorNsuRequest
{
    public function __construct(
        public readonly int $nsu,
        public readonly ?string $cnpj = null,
        public readonly bool $lote = true
    ) {
    }
}

/**
 * Response DTO para consulta de DFe por NSU
 */
final class ConsultarDfePorNsuResponse
{
    /**
     * @param StatusProcessamento $status
     * @param TipoAmbiente $ambiente
     * @param \DateTimeInterface $dataHoraProcessamento
     * @param int|null $ultimoNsu
     * @param int|null $maxNsu
     * @param DistribuicaoItemDTO[] $itens
     * @param MensagemProcessamentoDTO[] $alertas
     * @param MensagemProcessamentoDTO[] $erros
     */
    public function __construct(
        public readonly StatusProcessamento $status,
        public readonly TipoAmbiente $ambiente,
        public readonly \DateTimeInterface $dataHoraProcessamento,
        public readonly ?int $ultimoNsu = null,
        public readonly ?int $maxNsu = null,
        public readonly array $itens = [],
        public readonly array $alertas = [],
        public readonly array $erros = []
    ) {
    }

    /**
     * Verifica se há mais documentos
     */
    public function hasMore(): bool
    {
        return $this->ultimoNsu !== null
            && $this->maxNsu !== null
            && $this->ultimoNsu < $this->maxNsu;
    }
}

/**
 * Caso de uso para consulta de DFe por NSU
 */
class ConsultarDfePorNsuUseCase
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
    public function execute(ConsultarDfePorNsuRequest $request): ConsultarDfePorNsuResponse
    {
        try {
            $response = $this->gateway->consultarPorNsu(
                $request->nsu,
                $request->cnpj,
                $request->lote
            );

            return new ConsultarDfePorNsuResponse(
                status: $response->status,
                ambiente: $response->ambiente,
                dataHoraProcessamento: $response->dataHoraProcessamento,
                ultimoNsu: $response->ultimoNsu,
                maxNsu: $response->maxNsu,
                itens: $response->itens,
                alertas: $response->alertas,
                erros: $response->erros
            );
        } catch (\Throwable $e) {
            throw new ApplicationException(
                "Erro ao consultar DFe por NSU: " . $e->getMessage(),
                previous: $e
            );
        }
    }
}

