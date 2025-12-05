<?php

declare(strict_types=1);

namespace NfseNacional\Shared\Enum;

/**
 * Enum para status de processamento
 */
enum StatusProcessamento: int
{
    case SUCESSO = 0;
    case ERRO = 1;
    case REJEICAO = 2;
    case NENHUM_DOCUMENTO = 3;
    case DOCUMENTOS_LOCALIZADOS = 4;

    public function getDescricao(): string
    {
        return match($this) {
            self::SUCESSO => 'Sucesso',
            self::ERRO => 'Erro',
            self::REJEICAO => 'Rejeição',
            self::NENHUM_DOCUMENTO => 'Nenhum documento localizado',
            self::DOCUMENTOS_LOCALIZADOS => 'Documentos localizados',
        };
    }
}

