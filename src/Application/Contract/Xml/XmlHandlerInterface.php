<?php

declare(strict_types=1);

namespace NfseNacional\Application\Contract\Xml;

use DOMDocument;

/**
 * Interface para manipulação de XML
 */
interface XmlHandlerInterface
{
    /**
     * Cria um DOMDocument a partir de uma string XML
     */
    public function fromString(string $xml): DOMDocument;

    /**
     * Converte um DOMDocument para string
     */
    public function toString(DOMDocument $doc, bool $formatOutput = false): string;

    /**
     * Valida um XML contra um schema XSD
     */
    public function validate(string $xml, ?string $schemaPath = null): bool;

    /**
     * Verifica se uma string é um XML válido
     */
    public function isValid(string $xml): bool;

    /**
     * Extrai um valor de um elemento XML por tag
     */
    public function getElementValue(DOMDocument $doc, string $tagName): ?string;

    /**
     * Extrai todos os valores de elementos com determinada tag
     *
     * @return string[]
     */
    public function getElementsValues(DOMDocument $doc, string $tagName): array;
}

