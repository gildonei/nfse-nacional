<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Enum;

/**
 * Enum para tipo de ambiente
 */
enum TipoAmbiente: int
{
    case PRODUCAO = 1;
    case HOMOLOGACAO = 2;

    public function getDescricao(): string
    {
        return match($this) {
            self::PRODUCAO => 'Produção',
            self::HOMOLOGACAO => 'Homologação',
        };
    }
}

