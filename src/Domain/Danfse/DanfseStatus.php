<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Danfse;

enum DanfseStatus: string
{
    case Normal = 'normal';
    case Cancelada = 'cancelada';
    case Substituida = 'substituida';
}
