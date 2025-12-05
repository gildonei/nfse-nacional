<?php

declare(strict_types=1);

namespace NfseNacional\Application\UseCase\Emissao;

use NfseNacional\Application\DTO\Response\MensagemProcessamentoDTO;
use NfseNacional\Domain\Entity\Nfse;

/**
 * Response DTO para emissão de NFS-e
 */
final class EmitirNfseResponse
{
    /**
     * @param bool $sucesso Indica se a emissão foi bem sucedida
     * @param Nfse|null $nfse NFS-e emitida
     * @param string|null $protocolo Número do protocolo
     * @param MensagemProcessamentoDTO[] $alertas Lista de alertas
     * @param MensagemProcessamentoDTO[] $erros Lista de erros
     */
    public function __construct(
        public readonly bool $sucesso,
        public readonly ?Nfse $nfse = null,
        public readonly ?string $protocolo = null,
        public readonly array $alertas = [],
        public readonly array $erros = []
    ) {
    }

    /**
     * Cria uma resposta de sucesso
     */
    public static function success(Nfse $nfse, ?string $protocolo = null): self
    {
        return new self(
            sucesso: true,
            nfse: $nfse,
            protocolo: $protocolo
        );
    }

    /**
     * Cria uma resposta de erro
     *
     * @param MensagemProcessamentoDTO[] $erros
     */
    public static function error(array $erros): self
    {
        return new self(
            sucesso: false,
            erros: $erros
        );
    }
}

