<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject;

use InvalidArgumentException;
use NfseNacional\Domain\Contract\DocumentoInterface;

/**
 * Value Object CPF (Cadastro de Pessoa Física)
 *
 * @package NfseNacional\Domain\ValueObject
 */
class Cpf implements DocumentoInterface
{
    /**
     * CPF sem formatação (apenas números)
     * @var string
     */
    private string $numero;

    /**
     * Construtor
     *
     * @param string $cpf
     * @throws InvalidArgumentException
     */
    public function __construct(string $cpf)
    {
        $this->definirNumero($cpf);
    }

    /**
     * Define o número do CPF
     *
     * @param string $cpf
     * @throws InvalidArgumentException
     * @return self
     */
    private function definirNumero(string $cpf): self
    {
        // Remove formatação
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (empty($cpf)) {
            throw new InvalidArgumentException('CPF está vazio!');
        }

        if (strlen($cpf) !== 11) {
            throw new InvalidArgumentException('CPF deve conter exatamente 11 dígitos!');
        }

        // Verifica se todos os dígitos são iguais (CPF inválido)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            throw new InvalidArgumentException('CPF inválido!');
        }

        // Valida os dígitos verificadores
        if (!$this->validarDigitosVerificadores($cpf)) {
            throw new InvalidArgumentException('CPF inválido! Dígitos verificadores incorretos.');
        }

        $this->numero = $cpf;
        return $this;
    }

    /**
     * Valida os dígitos verificadores do CPF
     *
     * @param string $cpf
     * @return bool
     */
    private function validarDigitosVerificadores(string $cpf): bool
    {
        // Calcula o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int) $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        if ((int) $cpf[9] !== $digito1) {
            return false;
        }

        // Calcula o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int) $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return (int) $cpf[10] === $digito2;
    }

    /**
     * Retorna o CPF sem formatação
     *
     * @return string
     */
    public function obterNumero(): string
    {
        return $this->numero;
    }

    /**
     * Retorna o CPF formatado (XXX.XXX.XXX-XX)
     *
     * @return string
     */
    public function obterFormatado(): string
    {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->numero);
    }

    /**
     * Retorna o CPF como string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->numero;
    }

    /**
     * Compara dois CPFs
     *
     * @param Cpf $outro
     * @return bool
     */
    public function equals(Cpf $outro): bool
    {
        return $this->numero === $outro->numero;
    }
}

