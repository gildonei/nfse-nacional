<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Contract;

/**
 * Interface para entidades DPS (Documento de Prestação de Serviços)
 *
 * Define o contrato para renderização de DPS em XML
 */
interface DpsInterface
{
    /**
     * Converte os dados da DPS em XML
     *
     * @return string XML formatado
     */
    public function render(): string;
}

