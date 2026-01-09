<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Contract;

use NfseNacional\Domain\Entity\Emitente;

/**
 * Interface para assinatura de XML
 *
 * @package NfseNacional\Domain\Contract
 */
interface AssinadorXmlInterface
{
    /**
     * Assina um XML usando o certificado do emitente
     *
     * @param string $xmlString XML a ser assinado
     * @param Emitente $emitente Emitente com certificado digital
     * @param string $elementoId ID do elemento a ser assinado (ex: 'infDPS')
     * @param string $prefixoId Prefixo do ID (ex: 'DPS')
     * @return string XML assinado
     * @throws \Exception
     */
    public function assinar(string $xmlString, Emitente $emitente, string $elementoId = 'infDPS', string $prefixoId = 'DPS'): string;
}
