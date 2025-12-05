<?php

declare(strict_types=1);

namespace NfseNacional\Application\DTO\Response;

use NfseNacional\Shared\Enum\StatusProcessamento;
use NfseNacional\Shared\Enum\TipoAmbiente;

/**
 * DTO para resposta de distribuição de lotes/consultas
 */
final class LoteDistribuicaoDTO
{
    /**
     * @param StatusProcessamento $status Status do processamento
     * @param TipoAmbiente $ambiente Ambiente (produção/homologação)
     * @param \DateTimeInterface $dataHoraProcessamento Data/hora do processamento
     * @param int|null $ultimoNsu Último NSU retornado
     * @param int|null $maxNsu Maior NSU disponível
     * @param DistribuicaoItemDTO[] $itens Itens do lote
     * @param MensagemProcessamentoDTO[] $alertas Lista de alertas
     * @param MensagemProcessamentoDTO[] $erros Lista de erros
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
     * Verifica se há mais documentos para consultar
     */
    public function hasMore(): bool
    {
        if ($this->ultimoNsu === null || $this->maxNsu === null) {
            return false;
        }

        return $this->ultimoNsu < $this->maxNsu;
    }

    /**
     * Verifica se há erros
     */
    public function hasErrors(): bool
    {
        return !empty($this->erros);
    }

    /**
     * Retorna a quantidade de itens
     */
    public function count(): int
    {
        return count($this->itens);
    }
}

