<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Enum;

/**
 * Enum para tipo de documento na requisição
 */
enum TipoDocumentoRequisicao: int
{
    case NFSE = 1;
    case EVENTO = 2;

    public function getDescricao(): string
    {
        return match($this) {
            self::NFSE => 'NFS-e',
            self::EVENTO => 'Evento',
        };
    }
}

