<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject;

use InvalidArgumentException;
use NfseNacional\Domain\Contract\DocumentoInterface;

/**
 * Value Object CNPJ (Cadastro Nacional de Pessoa Jurídica)
 *
 * @package NfseNacional\Domain\ValueObject
 */
class Cnpj implements DocumentoInterface
{
    /**
     * CNPJ sem formatação (apenas números)
     * @var string
     */
    private string $numero;

    /**
     * Construtor
     *
     * @param string $cnpj
     * @throws InvalidArgumentException
     */
    public function __construct(string $cnpj)
    {
        $this->definirNumero($cnpj);
    }

    /**
     * Define o número do CNPJ
     *
     * @param string $cnpj
     * @throws InvalidArgumentException
     * @return self
     */
    private function definirNumero(string $cnpj): self
    {
        // Remove formatação
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (empty($cnpj)) {
            throw new InvalidArgumentException('CNPJ está vazio!');
        }

        if (strlen($cnpj) !== 14) {
            throw new InvalidArgumentException('CNPJ deve conter exatamente 14 dígitos!');
        }

        // Verifica se todos os dígitos são iguais (CNPJ inválido)
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            throw new InvalidArgumentException('CNPJ inválido!');
        }

        // Valida os dígitos verificadores
        if (!$this->validarDigitosVerificadores($cnpj)) {
            throw new InvalidArgumentException('CNPJ inválido! Dígitos verificadores incorretos.');
        }

        $this->numero = $cnpj;
        return $this;
    }

    /**
     * Valida os dígitos verificadores do CNPJ
     *
     * @param string $cnpj
     * @return bool
     */
    private function validarDigitosVerificadores(string $cnpj): bool
    {
        // Calcula o primeiro dígito verificador
        $soma = 0;
        $peso = 5;
        for ($i = 0; $i < 12; $i++) {
            $soma += (int) $cnpj[$i] * $peso;
            $peso = ($peso === 2) ? 9 : $peso - 1;
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        if ((int) $cnpj[12] !== $digito1) {
            return false;
        }

        // Calcula o segundo dígito verificador
        $soma = 0;
        $peso = 6;
        for ($i = 0; $i < 13; $i++) {
            $soma += (int) $cnpj[$i] * $peso;
            $peso = ($peso === 2) ? 9 : $peso - 1;
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return (int) $cnpj[13] === $digito2;
    }

    /**
     * Retorna o CNPJ sem formatação
     *
     * @return string
     */
    public function obterNumero(): string
    {
        return $this->numero;
    }

    /**
     * Retorna o CNPJ formatado (XX.XXX.XXX/XXXX-XX)
     *
     * @return string
     */
    public function obterFormatado(): string
    {
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->numero);
    }

    /**
     * Retorna o CNPJ como string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->numero;
    }

    /**
     * Compara dois CNPJs
     *
     * @param Cnpj $outro
     * @return bool
     */
    public function equals(Cnpj $outro): bool
    {
        return $this->numero === $outro->numero;
    }
}

