<?php

declare(strict_types=1);

namespace NfseNacional\Models\Enums;

/**
 * Enum para tipo de ambiente
 */
enum TipoAmbiente: string
{
    case PRODUCAO = 'PRODUCAO';
    case HOMOLOGACAO = 'HOMOLOGACAO';
}

