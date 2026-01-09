<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

use InvalidArgumentException;
use NfseNacional\Domain\Contract\DocumentoInterface;
use NfseNacional\Domain\Factory\DocumentoFactory;
use NfseNacional\Domain\ValueObject\Email;
use NfseNacional\Domain\ValueObject\Endereco;
use NfseNacional\Domain\ValueObject\Telefone;

/**
 * Entidade Abstrata Pessoa
 *
 * Classe base que unifica propriedades comuns entre Prestador e Tomador
 *
 * @package NfseNacional\Domain\Entity
 */
abstract class Pessoa
{
    /**
     * Nome da pessoa
     * @var string|null
     */
    protected ?string $nome = null;

    /**
     * Email da pessoa
     * @var Email|null
     */
    protected ?Email $email = null;

    /**
     * CMC (Código Municipal Contribuinte ou IM - Inscrição Municipal)
     * @var int|null
     */
    protected ?int $cmc = null;

    /**
     * Endereço da pessoa
     * @var Endereco|null
     */
    protected ?Endereco $endereco = null;

    /**
     * Telefone da pessoa
     * @var Telefone|null
     */
    protected ?Telefone $telefone = null;

    /**
     * Documento da pessoa (CPF/CNPJ)
     * @var DocumentoInterface|null
     */
    protected ?DocumentoInterface $documento = null;

    /**
     * Define o nome
     *
     * @param string $nome
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirNome(string $nome): self
    {
        if (empty(trim($nome))) {
            throw new InvalidArgumentException('Nome está vazio!');
        }

        $this->nome = $nome;
        return $this;
    }

    /**
     * Retorna o nome
     *
     * @return string|null
     */
    public function obterNome(): ?string
    {
        return $this->nome;
    }

    /**
     * Define o email
     *
     * @param Email $email
     * @return self
     */
    public function definirEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Retorna o email
     *
     * @return Email|null
     */
    public function obterEmail(): ?Email
    {
        return $this->email;
    }

    /**
     * Define o CMC (Código Municipal Contribuinte)
     *
     * @param int $cmc
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCmc(int $cmc): self
    {
        if ($cmc <= 0) {
            throw new InvalidArgumentException('CMC está vazio ou inválido!');
        }

        $this->cmc = $cmc;
        return $this;
    }

    /**
     * Retorna o CMC
     *
     * @return int|null
     */
    public function obterCmc(): ?int
    {
        return $this->cmc;
    }

    /**
     * Define o endereço
     *
     * @param Endereco $endereco
     * @return self
     */
    public function definirEndereco(Endereco $endereco): self
    {
        $this->endereco = $endereco;
        return $this;
    }

    /**
     * Retorna o endereço
     *
     * @return Endereco
     */
    public function obterEndereco(): Endereco
    {
        return $this->endereco ?? new Endereco();
    }

    /**
     * Define o telefone
     *
     * @param Telefone $telefone
     * @return self
     */
    public function definirTelefone(Telefone $telefone): self
    {
        $this->telefone = $telefone;
        return $this;
    }

    /**
     * Retorna o telefone
     *
     * @return Telefone
     */
    public function obterTelefone(): Telefone
    {
        return $this->telefone ?? new Telefone();
    }

    /**
     * Define o documento (CPF/CNPJ)
     *
     * @param DocumentoInterface|string $documento
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirDocumento(DocumentoInterface|string $documento): self
    {
        if (is_string($documento)) {
            $this->documento = DocumentoFactory::criar($documento);
        } else {
            $this->documento = $documento;
        }

        return $this;
    }

    /**
     * Retorna o documento (CPF/CNPJ)
     *
     * @return DocumentoInterface|null
     */
    public function obterDocumento(): ?DocumentoInterface
    {
        return $this->documento;
    }
}

