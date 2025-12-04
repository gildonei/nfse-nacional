<?php

declare(strict_types=1);

namespace NfseNacional\Models\Enums;

/**
 * Enum para situação da NFS-e
 */
enum SituacaoNfse: string
{
    case NORMAL = 'NORMAL';
    case CANCELADA = 'CANCELADA';
    case SUBSTITUIDA = 'SUBSTITUIDA';
    case CANCELADA_POR_SUBSTITUICAO = 'CANCELADA_POR_SUBSTITUICAO';
    case CANCELAMENTO_SOLICITADO = 'CANCELAMENTO_SOLICITADO';
}

