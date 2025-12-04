<?php

declare(strict_types=1);

namespace NfseNacional\Models\Enums;

/**
 * Enum para tipo de documento da requisição
 */
enum TipoDocumentoRequisicao: string
{
    case NENHUM = 'NENHUM';
    case DPS = 'DPS';
    case PEDIDO_REGISTRO_EVENTO = 'PEDIDO_REGISTRO_EVENTO';
    case NFSE = 'NFSE';
    case EVENTO = 'EVENTO';
    case CNC = 'CNC';
}

