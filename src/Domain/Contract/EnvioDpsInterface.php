<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Contract;

use NfseNacional\Domain\Xml\DpsXml;

/**
 * Interface para envio de DPS
 *
 * @package NfseNacional\Domain\Contract
 */
interface EnvioDpsInterface
{
    /**
     * Envia uma DPS para a API NFS-e Nacional
     *
     * O emitente será obtido automaticamente do DpsXml
     *
     * @param DpsXml $dpsXml XML da DPS gerado (deve conter o emitente com certificado)
     * @return array Resposta da API
     * @throws \Exception
     */
    public function enviar(DpsXml $dpsXml): array;
}
