<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject;

use InvalidArgumentException;

/**
 * Value Object Telefone
 *
 * @package NfseNacional\Domain\ValueObject
 */
class Telefone
{
    /**
     * Código do país do telefone
     * @var int
     */
    private int $codigoPais = 55;

    /**
     * Código de área do telefone
     * @var int|null
     */
    private ?int $codigoArea = null;

    /**
     * Número do telefone
     * @var int|null
     */
    private ?int $numero = null;

    /**
     * Construtor
     *
     * @param int|null $codigoPais
     * @param int|null $codigoArea
     * @param int|null $numero
     */
    public function __construct(?int $codigoPais = null, ?int $codigoArea = null, ?int $numero = null)
    {
        if ($codigoPais !== null) {
            $this->definirCodigoPais($codigoPais);
        }
        if ($codigoArea !== null) {
            $this->definirCodigoArea($codigoArea);
        }
        if ($numero !== null) {
            $this->definirNumero($numero);
        }
    }

    /**
     * Define o código do país
     *
     * @param int $codigo
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCodigoPais(int $codigo): self
    {
        $codigoStr = (string) $codigo;

        if (empty($codigoStr)) {
            throw new InvalidArgumentException('Código do país está vazio!');
        }

        if (strlen($codigoStr) > 2) {
            throw new InvalidArgumentException('Código do país excede o limite máximo de 2 dígitos!');
        }

        $this->codigoPais = $codigo;
        return $this;
    }

    /**
     * Retorna o código do país
     *
     * @return int
     */
    public function obterCodigoPais(): int
    {
        return $this->codigoPais;
    }

    /**
     * Define o código de área
     *
     * @param int $codigo
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCodigoArea(int $codigo): self
    {
        $codigoStr = (string) $codigo;

        if (empty($codigoStr)) {
            throw new InvalidArgumentException('Código de área está vazio!');
        }

        if (strlen($codigoStr) > 2) {
            throw new InvalidArgumentException('Código de área excede o limite máximo de 2 dígitos!');
        }

        $this->codigoArea = $codigo;
        return $this;
    }

    /**
     * Retorna o código de área
     *
     * @return int|null
     */
    public function obterCodigoArea(): ?int
    {
        return $this->codigoArea;
    }

    /**
     * Define o número do telefone
     *
     * @param int $numero
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirNumero(int $numero): self
    {
        $numeroStr = (string) $numero;

        if (empty($numeroStr)) {
            throw new InvalidArgumentException('Número está vazio!');
        }

        if (strlen($numeroStr) > 10) {
            throw new InvalidArgumentException('Número excede o limite máximo de 10 dígitos!');
        }

        $this->numero = $numero;
        return $this;
    }

    /**
     * Retorna o número do telefone
     *
     * @return int|null
     */
    public function obterNumero(): ?int
    {
        return $this->numero;
    }

    /**
     * Retorna o código de área com o número do telefone
     *
     * @return string
     */
    public function obterTelefone(): string
    {
        if ($this->codigoArea === null || $this->numero === null) {
            return '';
        }

        return (string) $this->codigoArea . (string) $this->numero;
    }

    /**
     * Retorna o telefone completo com código do país
     *
     * @return string
     */
    public function obterTelefoneComCodigoPais(): string
    {
        $telefone = $this->obterTelefone();

        if (empty($telefone)) {
            return '';
        }

        return '+' . $this->codigoPais . $telefone;
    }
}

