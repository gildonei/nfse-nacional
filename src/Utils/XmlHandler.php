<?php

declare(strict_types=1);

namespace NfseNacional\Utils;

use DOMDocument;
use DOMElement;
use NfseNacional\Exceptions\NfseException;

/**
 * Classe utilitária para manipulação de XML
 */
class XmlHandler
{
    /**
     * Valida um XML
     *
     * @param string $xml
     * @return bool
     */
    public static function isValid(string $xml): bool
    {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $result = $doc->loadXML($xml);
        libxml_clear_errors();
        return $result !== false;
    }

    /**
     * Carrega um XML em um DOMDocument
     *
     * @param string $xml
     * @return DOMDocument
     * @throws NfseException
     */
    public static function load(string $xml): DOMDocument
    {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;

        if (!$doc->loadXML($xml)) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            $errorMessages = array_map(fn($error) => $error->message, $errors);
            throw new NfseException("Erro ao carregar XML: " . implode(', ', $errorMessages));
        }

        libxml_clear_errors();
        return $doc;
    }

    /**
     * Converte um DOMDocument para string XML
     *
     * @param DOMDocument $doc
     * @param bool $formatOutput
     * @return string
     */
    public static function toString(DOMDocument $doc, bool $formatOutput = false): string
    {
        $doc->formatOutput = $formatOutput;
        return $doc->saveXML();
    }

    /**
     * Remove declaração XML e retorna apenas o conteúdo
     *
     * @param string $xml
     * @return string
     */
    public static function removeDeclaration(string $xml): string
    {
        return preg_replace('/<\?xml[^>]*\?>/i', '', $xml);
    }

    /**
     * Extrai o conteúdo de um elemento específico
     *
     * @param string $xml
     * @param string $xpath
     * @return string|null
     */
    public static function extractContent(string $xml, string $xpath): ?string
    {
        try {
            $doc = self::load($xml);
            $xpathObj = new \DOMXPath($doc);
            $nodes = $xpathObj->query($xpath);

            if ($nodes === false || $nodes->length === 0) {
                return null;
            }

            return $nodes->item(0)?->textContent;
        } catch (NfseException $e) {
            return null;
        }
    }
}

