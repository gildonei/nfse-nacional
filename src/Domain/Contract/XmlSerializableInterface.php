<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Contract;

use DOMDocument;

/**
 * Interface para entidades que podem ser serializadas para XML
 */
interface XmlSerializableInterface
{
    /**
     * Converte a entidade para DOMDocument
     */
    public function toXml(): DOMDocument;

    /**
     * Converte a entidade para string XML
     */
    public function toXmlString(bool $formatOutput = false): string;
}

