<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

use InvalidArgumentException;
use NfseNacional\Domain\Contract\DocumentoInterface;
use NfseNacional\Domain\Enum\MotivoNaoInformarNif;
use NfseNacional\Domain\ValueObject\Email;
use NfseNacional\Domain\ValueObject\Endereco;
use NfseNacional\Domain\ValueObject\Telefone;

/**
 * Entidade Tomador
 *
 * @package NfseNacional\Domain\Entity
 */
class Tomador extends Pessoa
{
    /**
     * Razão social do tomador
     * @var string|null
     */
    private ?string $razaoSocial = null;

    /**
     * Dados adicionais do tomador
     * @var string|null
     */
    private ?string $dadosAdicionais = null;

    /**
     * Motivo de não informar NIF
     * @var MotivoNaoInformarNif|null
     */
    private ?MotivoNaoInformarNif $motivoNaoInformarNif = null;

    /**
     * Construtor
     *
     * @param string|null $nome
     * @param string|null $razaoSocial
     * @param Email|string|null $email
     * @param int|null $cmc
     * @param DocumentoInterface|string|null $documento
     * @param Endereco|null $endereco
     * @param string|null $dadosAdicionais
     * @param Telefone|null $telefone
     */
    public function __construct(
        ?string $nome = null,
        ?string $razaoSocial = null,
        Email|string|null $email = null,
        ?int $cmc = null,
        DocumentoInterface|string|null $documento = null,
        ?Endereco $endereco = null,
        ?string $dadosAdicionais = null,
        ?Telefone $telefone = null
    ) {
        if ($nome !== null) {
            $this->definirNome($nome);
        }
        if ($razaoSocial !== null) {
            $this->definirRazaoSocial($razaoSocial);
        }
        if ($email !== null) {
            $emailObj = is_string($email) ? new Email($email) : $email;
            $this->definirEmail($emailObj);
        }
        if ($cmc !== null) {
            $this->definirCmc($cmc);
        }
        if ($documento !== null) {
            $this->definirDocumento($documento);
        }
        if ($endereco !== null) {
            $this->definirEndereco($endereco);
        }
        if ($dadosAdicionais !== null) {
            $this->definirDadosAdicionais($dadosAdicionais);
        }
        if ($telefone !== null) {
            $this->definirTelefone($telefone);
        }
    }


    /**
     * Define a razão social
     *
     * @param string $razaoSocial
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirRazaoSocial(string $razaoSocial): self
    {
        if (empty(trim($razaoSocial))) {
            throw new InvalidArgumentException('Razão social está vazia!');
        }

        $this->razaoSocial = $razaoSocial;
        return $this;
    }

    /**
     * Retorna a razão social
     *
     * @return string|null
     */
    public function obterRazaoSocial(): ?string
    {
        return $this->razaoSocial;
    }

    /**
     * Define os dados adicionais
     *
     * @param string $dadosAdicionais
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirDadosAdicionais(string $dadosAdicionais): self
    {
        if (strlen($dadosAdicionais) > 600) {
            throw new InvalidArgumentException('Dados adicionais excedem o limite máximo de 600 caracteres!');
        }

        $this->dadosAdicionais = $dadosAdicionais;
        return $this;
    }

    /**
     * Retorna os dados adicionais
     *
     * @return string|null
     */
    public function obterDadosAdicionais(): ?string
    {
        return $this->dadosAdicionais;
    }

    /**
     * Define o motivo de não informar NIF
     *
     * @param MotivoNaoInformarNif $motivoNaoInformarNif
     * @return self
     */
    public function definirMotivoNaoInformarNif(MotivoNaoInformarNif $motivoNaoInformarNif): self
    {
        $this->motivoNaoInformarNif = $motivoNaoInformarNif;
        return $this;
    }

    /**
     * Retorna o motivo de não informar NIF
     *
     * @return MotivoNaoInformarNif|null
     */
    public function obterMotivoNaoInformarNif(): ?MotivoNaoInformarNif
    {
        return $this->motivoNaoInformarNif;
    }

}

