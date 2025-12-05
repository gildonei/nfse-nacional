<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Xml;

use DOMDocument;
use NfseNacional\Application\Contract\Xml\XmlHandlerInterface;

/**
 * Implementação do XmlHandler usando DOMDocument
 */
class DomXmlHandler implements XmlHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function fromString(string $xml): DOMDocument
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = false;

        libxml_use_internal_errors(true);
        $result = $doc->loadXML($xml);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        if (!$result) {
            $errorMessages = array_map(fn($e) => trim($e->message), $errors);
            throw new \InvalidArgumentException(
                "XML inválido: " . implode(', ', $errorMessages)
            );
        }

        return $doc;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(DOMDocument $doc, bool $formatOutput = false): string
    {
        $doc->formatOutput = $formatOutput;
        $xml = $doc->saveXML();

        if ($xml === false) {
            throw new \RuntimeException("Erro ao converter DOMDocument para string");
        }

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(string $xml, ?string $schemaPath = null): bool
    {
        $doc = $this->fromString($xml);

        if ($schemaPath !== null) {
            if (!file_exists($schemaPath)) {
                throw new \InvalidArgumentException("Schema XSD não encontrado: {$schemaPath}");
            }

            libxml_use_internal_errors(true);
            $result = $doc->schemaValidate($schemaPath);
            libxml_clear_errors();

            return $result;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(string $xml): bool
    {
        try {
            $this->fromString($xml);
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getElementValue(DOMDocument $doc, string $tagName): ?string
    {
        $elements = $doc->getElementsByTagName($tagName);
        if ($elements->length === 0) {
            return null;
        }

        return $elements->item(0)?->textContent;
    }

    /**
     * {@inheritdoc}
     */
    public function getElementsValues(DOMDocument $doc, string $tagName): array
    {
        $elements = $doc->getElementsByTagName($tagName);
        $values = [];

        foreach ($elements as $element) {
            $values[] = $element->textContent;
        }

        return $values;
    }
}

