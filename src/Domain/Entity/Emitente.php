<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

use InvalidArgumentException;
use NfseNacional\Domain\Contract\DocumentoInterface;
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
     * @var string|null
     */
    private ?string $certificado = null;

    /**
     * Senha do certificado digital
     * @var string|null
     */
    private ?string $senhaCertificado = null;

    /**
     * Construtor
     *
     * @param string $nome Nome do emitente (obrigatório)
     * @param DocumentoInterface|string $documento Documento CPF/CNPJ (obrigatório)
     * @param Endereco $endereco Endereço do emitente (obrigatório)
     * @param Telefone $telefone Telefone do emitente (obrigatório)
     * @param Email|string $email Email do emitente (obrigatório)
     * @param string $certificado Certificado digital para assinatura (obrigatório)
     * @param string $senhaCertificado Senha do certificado digital (obrigatório)
     * @param int|null $cmc Código Municipal Contribuinte (opcional)
     */
    public function __construct(
        string $nome,
        DocumentoInterface|string $documento,
        Endereco $endereco,
        Telefone $telefone,
        Email|string $email,
        string $certificado,
        string $senhaCertificado,
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

        // Define senha do certificado
        $this->definirSenhaCertificado($senhaCertificado);

        // Define CMC se fornecido
        if ($cmc !== null) {
            $this->definirCmc($cmc);
        }
    }

    /**
     * Define o certificado digital
     *
     * @param string $certificado Caminho do arquivo ou conteúdo do certificado
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCertificado(string $certificado): self
    {
        $certificado = trim($certificado);

        if (empty($certificado)) {
            throw new InvalidArgumentException('Certificado não pode estar vazio!');
        }

        // Verifica se é um caminho de arquivo e se existe
        if (file_exists($certificado)) {
            $conteudo = file_get_contents($certificado);
            if ($conteudo === false) {
                throw new InvalidArgumentException('Não foi possível ler o arquivo do certificado!');
            }
            $this->certificado = $conteudo;
        } else {
            // Assume que é o conteúdo do certificado
            $this->certificado = $certificado;
        }

        return $this;
    }

    /**
     * Retorna o certificado digital
     *
     * @return string|null
     */
    public function obterCertificado(): ?string
    {
        return $this->certificado;
    }

    /**
     * Define a senha do certificado digital
     *
     * @param string $senhaCertificado Senha do certificado
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirSenhaCertificado(string $senhaCertificado): self
    {
        $senhaCertificado = trim($senhaCertificado);

        if (empty($senhaCertificado)) {
            throw new InvalidArgumentException('Senha do certificado não pode estar vazia!');
        }

        $this->senhaCertificado = $senhaCertificado;
        return $this;
    }

    /**
     * Retorna a senha do certificado digital
     *
     * @return string|null
     */
    public function obterSenhaCertificado(): ?string
    {
        return $this->senhaCertificado;
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

        if (empty($this->certificado)) {
            throw new InvalidArgumentException('Certificado do emitente é obrigatório!');
        }

        if (empty($this->senhaCertificado)) {
            throw new InvalidArgumentException('Senha do certificado do emitente é obrigatória!');
        }

        return true;
    }
}

