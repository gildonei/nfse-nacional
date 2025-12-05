<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject\Documento;

/**
 * Interface para documentos (CPF ou CNPJ)
 */
interface DocumentoInterface
{
    /**
     * Retorna o documento formatado
     */
    public function getFormatado(): string;

    /**
     * Retorna o documento sem formatação (apenas números)
     */
    public function getSemFormatacao(): string;

    /**
     * Retorna o tipo do documento (CPF ou CNPJ)
     */
    public function getTipo(): string;

    /**
     * Valida o documento
     */
    public function validar(): bool;

    /**
     * Converte para array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * Representação em string
     */
    public function __toString(): string;
}

