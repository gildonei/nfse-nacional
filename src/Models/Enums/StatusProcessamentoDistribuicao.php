<?php

declare(strict_types=1);

namespace NfseNacional\Models\Enums;

/**
 * Enum para status de processamento da distribuição
 */
enum StatusProcessamentoDistribuicao: string
{
    case REJEICAO = 'REJEICAO';
    case NENHUM_DOCUMENTO_LOCALIZADO = 'NENHUM_DOCUMENTO_LOCALIZADO';
    case DOCUMENTOS_LOCALIZADOS = 'DOCUMENTOS_LOCALIZADOS';
}

