<?php

declare(strict_types=1);

namespace NfseNacional\Application\DTO\Response;

/**
 * DTO para mensagens de processamento (alertas/erros)
 */
final class MensagemProcessamentoDTO
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $descricao,
        public readonly ?string $correcao = null
    ) {
    }

    /**
     * Cria a partir de um array
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            codigo: $data['Codigo'] ?? $data['codigo'] ?? '',
            descricao: $data['Descricao'] ?? $data['descricao'] ?? '',
            correcao: $data['Correcao'] ?? $data['correcao'] ?? null
        );
    }

    /**
     * Converte para string
     */
    public function __toString(): string
    {
        $msg = "[{$this->codigo}] {$this->descricao}";
        if ($this->correcao) {
            $msg .= " - Correção: {$this->correcao}";
        }
        return $msg;
    }
}

