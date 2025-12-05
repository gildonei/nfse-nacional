<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Contract;

/**
 * Interface para entidades que podem ser validadas
 */
interface ValidatableInterface
{
    /**
     * Valida a entidade
     *
     * @return bool
     * @throws \NfseNacional\Domain\Exception\DomainException
     */
    public function validate(): bool;
}

