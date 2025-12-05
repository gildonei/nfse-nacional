<?php

declare(strict_types=1);

namespace NfseNacional\Application\DTO\Response;

use NfseNacional\Domain\Entity\Nfse;

/**
 * DTO para resposta de operações com NFS-e
 */
final class NfseResponseDTO
{
    /**
     * @param bool $sucesso Indica se a operação foi bem sucedida
     * @param Nfse|null $nfse Dados da NFS-e (quando aplicável)
     * @param string|null $mensagem Mensagem de retorno
     * @param string|null $protocolo Número do protocolo
     * @param MensagemProcessamentoDTO[] $alertas Lista de alertas
     * @param MensagemProcessamentoDTO[] $erros Lista de erros
     * @param array<string, mixed> $dados Dados adicionais
     */
    public function __construct(
        public readonly bool $sucesso,
        public readonly ?Nfse $nfse = null,
        public readonly ?string $mensagem = null,
        public readonly ?string $protocolo = null,
        public readonly array $alertas = [],
        public readonly array $erros = [],
        public readonly array $dados = []
    ) {
    }

    /**
     * Cria uma resposta de sucesso
     */
    public static function success(Nfse $nfse, ?string $mensagem = null, ?string $protocolo = null): self
    {
        return new self(
            sucesso: true,
            nfse: $nfse,
            mensagem: $mensagem ?? 'Operação realizada com sucesso',
            protocolo: $protocolo
        );
    }

    /**
     * Cria uma resposta de erro
     *
     * @param MensagemProcessamentoDTO[] $erros
     */
    public static function error(string $mensagem, array $erros = []): self
    {
        return new self(
            sucesso: false,
            mensagem: $mensagem,
            erros: $erros
        );
    }

    /**
     * Verifica se há erros
     */
    public function hasErrors(): bool
    {
        return !empty($this->erros);
    }

    /**
     * Verifica se há alertas
     */
    public function hasWarnings(): bool
    {
        return !empty($this->alertas);
    }
}

