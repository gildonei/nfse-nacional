<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Xml;

use DOMDocument;
use NfseNacional\Application\Contract\Xml\XmlSignerInterface;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

/**
 * Implementação do XmlSigner usando XMLSecLibs
 */
class XmlSecLibsSigner implements XmlSignerInterface
{
    /**
     * {@inheritdoc}
     */
    public function sign(
        DOMDocument $doc,
        \OpenSSLCertificate $certificate,
        \OpenSSLAsymmetricKey $privateKey,
        ?string $referenceId = null
    ): string {
        $rootNode = $doc->documentElement;
        if ($rootNode === null) {
            throw new \InvalidArgumentException("XML não possui elemento raiz");
        }

        $objDSig = new XMLSecurityDSig();
        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

        $options = ['id_name' => 'Id', 'overwrite' => false];

        $objDSig->addReferenceList(
            [$rootNode],
            XMLSecurityDSig::SHA256,
            ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
            $options
        );

        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);

        $pemKey = '';
        openssl_pkey_export($privateKey, $pemKey);
        $objKey->loadKey($pemKey, false);

        $objDSig->sign($objKey);

        $certData = '';
        openssl_x509_export($certificate, $certData);
        $objDSig->add509Cert($certData, true);

        $objDSig->appendSignature($rootNode);

        $xml = $doc->saveXML();
        if ($xml === false) {
            throw new \RuntimeException("Erro ao salvar XML assinado");
        }

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    public function verify(string $signedXml): bool
    {
        $doc = new DOMDocument();
        $doc->loadXML($signedXml);

        $objDSig = new XMLSecurityDSig();
        $objDSig->locateSignature($doc);

        try {
            $objDSig->canonicalizeSignedInfo();
            $objDSig->validateReference();

            $objKey = $objDSig->locateKey();
            if ($objKey === null) {
                return false;
            }

            if (!$objDSig->verify($objKey)) {
                return false;
            }

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

