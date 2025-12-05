<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Enum;

/**
 * Enum para tipo de evento
 */
enum TipoEvento: int
{
    case CANCELAMENTO = 101;
    case SUBSTITUICAO = 102;
    case MANIFESTACAO_CONFIRMACAO = 201;
    case MANIFESTACAO_REJEICAO = 202;

    public function getDescricao(): string
    {
        return match($this) {
            self::CANCELAMENTO => 'Cancelamento',
            self::SUBSTITUICAO => 'Substituição',
            self::MANIFESTACAO_CONFIRMACAO => 'Manifestação - Confirmação',
            self::MANIFESTACAO_REJEICAO => 'Manifestação - Rejeição',
        };
    }
}

