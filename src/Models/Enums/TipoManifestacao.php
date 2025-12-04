<?php

declare(strict_types=1);

namespace NfseNacional\Models\Enums;

/**
 * Enum para tipo de manifestação de NFS-e recebida
 */
enum TipoManifestacao: string
{
    case CONFIRMACAO_TOMADOR = 'CONFIRMACAO_TOMADOR';
    case REJEICAO_TOMADOR = 'REJEICAO_TOMADOR';
    case CONFIRMACAO_PRESTADOR = 'CONFIRMACAO_PRESTADOR';
    case REJEICAO_PRESTADOR = 'REJEICAO_PRESTADOR';
    case CONFIRMACAO_INTERMEDIARIO = 'CONFIRMACAO_INTERMEDIARIO';
    case REJEICAO_INTERMEDIARIO = 'REJEICAO_INTERMEDIARIO';
}

