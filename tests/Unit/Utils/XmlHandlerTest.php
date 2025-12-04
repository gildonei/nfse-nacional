<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Utils;

use NfseNacional\Utils\XmlHandler;
use PHPUnit\Framework\TestCase;

class XmlHandlerTest extends TestCase
{
    public function testIsValidWithValidXml(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><root><item>test</item></root>';
        $this->assertTrue(XmlHandler::isValid($xml));
    }

    public function testIsValidWithInvalidXml(): void
    {
        $xml = '<root><item>test</item>';
        $this->assertFalse(XmlHandler::isValid($xml));
    }

    public function testLoad(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><root><item>test</item></root>';
        $doc = XmlHandler::load($xml);
        $this->assertInstanceOf(\DOMDocument::class, $doc);
    }

    public function testRemoveDeclaration(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><root><item>test</item></root>';
        $result = XmlHandler::removeDeclaration($xml);
        $this->assertStringNotContainsString('<?xml', $result);
    }
}

