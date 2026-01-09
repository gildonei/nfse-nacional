<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

use InvalidArgumentException;
use NfseNacional\Domain\Contract\DocumentoInterface;
use NfseNacional\Domain\ValueObject\Certificado;
use NfseNacional\Domain\ValueObject\Email;
use NfseNacional\Domain\ValueObject\Endereco;
use NfseNacional\Domain\ValueObject\Telefone;

/**
 * Entidade Emitente
 *
 * Representa o emitente da NFS-e com todas as informações obrigatórias
 * incluindo certificado digital para assinatura do XML
 *
 * @package NfseNacional\Domain\Entity
 */
class Emitente extends Pessoa
{
    /**
     * Certificado digital para assinatura do XML
     * @var Certificado|null
     */
    private ?Certificado $certificado = null;

    /**
     * Construtor
     *
     * @param string $nome Nome do emitente (obrigatório)
     * @param DocumentoInterface|string $documento Documento CPF/CNPJ (obrigatório)
     * @param Endereco $endereco Endereço do emitente (obrigatório)
     * @param Telefone $telefone Telefone do emitente (obrigatório)
     * @param Email|string $email Email do emitente (obrigatório)
     * @param Certificado $certificado Certificado digital para assinatura (obrigatório)
     * @param int|null $cmc Código Municipal Contribuinte (opcional)
     */
    public function __construct(
        string $nome,
        DocumentoInterface|string $documento,
        Endereco $endereco,
        Telefone $telefone,
        Email|string $email,
        Certificado $certificado,
        ?int $cmc = null
    ) {
        // Define propriedades obrigatórias
        $this->definirNome($nome);
        $this->definirDocumento($documento);
        $this->definirEndereco($endereco);
        $this->definirTelefone($telefone);

        // Converte email se for string
        $emailObj = is_string($email) ? new Email($email) : $email;
        $this->definirEmail($emailObj);

        // Define certificado
        $this->definirCertificado($certificado);

        // Define CMC se fornecido
        if ($cmc !== null) {
            $this->definirCmc($cmc);
        }
    }

    /**
     * Define o certificado digital
     *
     * @param Certificado $certificado Value Object do certificado
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCertificado(Certificado $certificado): self
    {
        $this->certificado = $certificado;

        // Valida o certificado
        $this->certificado->validar();

        return $this;
    }

    /**
     * Retorna o certificado digital
     *
     * @return Certificado|null
     */
    public function obterCertificado(): ?Certificado
    {
        return $this->certificado;
    }

    /**
     * Retorna o conteúdo do certificado digital (método de conveniência)
     *
     * @return string|null
     */
    public function obterConteudoCertificado(): ?string
    {
        return $this->certificado?->obterConteudo();
    }

    /**
     * Retorna a senha do certificado digital (método de conveniência)
     *
     * @return string|null
     */
    public function obterSenhaCertificado(): ?string
    {
        return $this->certificado?->obterSenha();
    }

    /**
     * Valida se todas as propriedades obrigatórias estão definidas
     *
     * @throws InvalidArgumentException
     * @return bool
     */
    public function validar(): bool
    {
        if (empty($this->obterNome())) {
            throw new InvalidArgumentException('Nome do emitente é obrigatório!');
        }

        if ($this->obterDocumento() === null) {
            throw new InvalidArgumentException('Documento do emitente é obrigatório!');
        }

        if ($this->obterEndereco() === null) {
            throw new InvalidArgumentException('Endereço do emitente é obrigatório!');
        }

        if ($this->obterTelefone() === null) {
            throw new InvalidArgumentException('Telefone do emitente é obrigatório!');
        }

        if ($this->obterEmail() === null) {
            throw new InvalidArgumentException('Email do emitente é obrigatório!');
        }

        if ($this->certificado === null) {
            throw new InvalidArgumentException('Certificado do emitente é obrigatório!');
        }

        // Valida o certificado
        $this->certificado->validar();

        return true;
    }
}

