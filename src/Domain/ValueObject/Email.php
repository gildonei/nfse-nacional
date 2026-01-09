<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject;

use InvalidArgumentException;

/**
 * Value Object Email
 *
 * @package NfseNacional\Domain\ValueObject
 */
class Email
{
    /**
     * Endereço de email
     * @var string
     */
    private string $endereco;

    /**
     * Construtor
     *
     * @param string $endereco
     * @throws InvalidArgumentException
     */
    public function __construct(string $endereco)
    {
        $this->definirEndereco($endereco);
    }

    /**
     * Define o endereço de email
     *
     * @param string $endereco
     * @throws InvalidArgumentException
     * @return self
     */
    private function definirEndereco(string $endereco): self
    {
        $endereco = trim($endereco);

        if (empty($endereco)) {
            throw new InvalidArgumentException('Endereço de email está vazio!');
        }

        // Validação básica de formato
        if (filter_var($endereco, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Endereço de email inválido!');
        }

        // Validação adicional de comprimento
        if (strlen($endereco) > 255) {
            throw new InvalidArgumentException('Endereço de email excede o limite máximo de 255 caracteres!');
        }

        // Validação de formato básico (deve conter @ e pelo menos um ponto após o @)
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $endereco)) {
            throw new InvalidArgumentException('Formato de email inválido!');
        }

        $this->endereco = strtolower($endereco);
        return $this;
    }

    /**
     * Retorna o endereço de email
     *
     * @return string
     */
    public function obterEndereco(): string
    {
        return $this->endereco;
    }

    /**
     * Retorna o domínio do email
     *
     * @return string
     */
    public function obterDominio(): string
    {
        $partes = explode('@', $this->endereco);
        return $partes[1] ?? '';
    }

    /**
     * Retorna o nome de usuário do email (parte antes do @)
     *
     * @return string
     */
    public function obterUsuario(): string
    {
        $partes = explode('@', $this->endereco);
        return $partes[0] ?? '';
    }

    /**
     * Retorna o email como string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->endereco;
    }

    /**
     * Compara dois emails
     *
     * @param Email $outro
     * @return bool
     */
    public function equals(Email $outro): bool
    {
        return $this->endereco === $outro->endereco;
    }
}

