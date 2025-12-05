<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Enum;

/**
 * Enum para tipo de manifestação
 */
enum TipoManifestacao: string
{
    case CONFIRMACAO = 'CONFIRMACAO';
    case REJEICAO = 'REJEICAO';

    public function getDescricao(): string
    {
        return match($this) {
            self::CONFIRMACAO => 'Confirmação',
            self::REJEICAO => 'Rejeição',
        };
    }
}

