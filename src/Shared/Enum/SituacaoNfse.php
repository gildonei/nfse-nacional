<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Enum;

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

    public function getDescricao(): string
    {
        return match($this) {
            self::NORMAL => 'Normal',
            self::CANCELADA => 'Cancelada',
            self::SUBSTITUIDA => 'Substituída',
            self::CANCELADA_POR_SUBSTITUICAO => 'Cancelada por substituição',
            self::CANCELAMENTO_SOLICITADO => 'Cancelamento solicitado',
        };
    }
}

