<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

use InvalidArgumentException;
use NfseNacional\Domain\Enum\OptanteSimplesNacional;
use NfseNacional\Domain\Enum\RegimeEspecialTributacaoMunicipal;
use NfseNacional\Domain\Enum\RegimeTributacaoSimplesNacional;
use NfseNacional\Domain\Contract\DocumentoInterface;
use NfseNacional\Domain\ValueObject\Email;
use NfseNacional\Domain\ValueObject\Endereco;
use NfseNacional\Domain\ValueObject\Telefone;

/**
 * Entidade Prestador
 *
 * @package NfseNacional\Domain\Entity
 */
class Prestador extends Pessoa
{
    /**
     * Número AEDF (Autorização para Emissão de Nota Fiscal)
     * @var int|null
     */
    private ?int $aedf = null;

    /**
     * Optante pelo Simples Nacional
     * @var OptanteSimplesNacional|null
     */
    private ?OptanteSimplesNacional $optanteSimplesNacional = null;

    /**
     * Regime Especial de Tributação Municipal
     * @var RegimeEspecialTributacaoMunicipal|null
     */
    private ?RegimeEspecialTributacaoMunicipal $regimeEspecialTributacao = null;

    /**
     * Regime de Tributação Simples Nacional
     * @var RegimeTributacaoSimplesNacional|null
     */
    private ?RegimeTributacaoSimplesNacional $regimeTributacaoSimplesNacional = null;

    /**
     * Construtor
     *
     * @param string|null $nome
     * @param Email|string|null $email
     * @param int|null $cmc
     * @param int|null $aedf
     * @param DocumentoInterface|string|null $documento
     * @param Endereco|null $endereco
     * @param Telefone|null $telefone
     * @param OptanteSimplesNacional|null $optanteSimplesNacional
     * @param RegimeEspecialTributacaoMunicipal|null $regimeEspecialTributacao
     */
    public function __construct(
        ?string $nome = null,
        Email|string|null $email = null,
        ?int $cmc = null,
        ?int $aedf = null,
        DocumentoInterface|string|null $documento = null,
        ?Endereco $endereco = null,
        ?Telefone $telefone = null,
        ?OptanteSimplesNacional $optanteSimplesNacional = null,
        ?RegimeEspecialTributacaoMunicipal $regimeEspecialTributacao = null
    ) {
        if ($nome !== null) {
            $this->definirNome($nome);
        }
        if ($email !== null) {
            $emailObj = is_string($email) ? new Email($email) : $email;
            $this->definirEmail($emailObj);
        }
        if ($cmc !== null) {
            $this->definirCmc($cmc);
        }
        if ($aedf !== null) {
            $this->definirAedf($aedf);
        }
        if ($documento !== null) {
            $this->definirDocumento($documento);
        }
        if ($endereco !== null) {
            $this->definirEndereco($endereco);
        }
        if ($telefone !== null) {
            $this->definirTelefone($telefone);
        }
        if ($optanteSimplesNacional !== null) {
            $this->definirOptanteSimplesNacional($optanteSimplesNacional);
        }
        if ($regimeEspecialTributacao !== null) {
            $this->definirRegimeEspecialTributacao($regimeEspecialTributacao);
        }
    }


    /**
     * Define o valor do AEDF
     *
     * @param int $aedf
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirAedf(int $aedf): self
    {
        if ($aedf <= 0) {
            throw new InvalidArgumentException('AEDF deve ser um número válido!');
        }

        $this->aedf = $aedf;
        return $this;
    }

    /**
     * Retorna o AEDF do prestador
     *
     * @return int|null
     */
    public function obterAedf(): ?int
    {
        return $this->aedf;
    }

    /**
     * Define se é optante pelo Simples Nacional
     *
     * @param OptanteSimplesNacional $optanteSimplesNacional
     * @return self
     */
    public function definirOptanteSimplesNacional(OptanteSimplesNacional $optanteSimplesNacional): self
    {
        $this->optanteSimplesNacional = $optanteSimplesNacional;
        return $this;
    }

    /**
     * Retorna se é optante pelo Simples Nacional
     *
     * @return OptanteSimplesNacional|null
     */
    public function obterOptanteSimplesNacional(): ?OptanteSimplesNacional
    {
        return $this->optanteSimplesNacional;
    }

    /**
     * Define o regime especial de tributação municipal
     *
     * @param RegimeEspecialTributacaoMunicipal $regimeEspecialTributacao
     * @return self
     */
    public function definirRegimeEspecialTributacao(RegimeEspecialTributacaoMunicipal $regimeEspecialTributacao): self
    {
        $this->regimeEspecialTributacao = $regimeEspecialTributacao;
        return $this;
    }

    /**
     * Retorna o regime especial de tributação municipal
     *
     * @return RegimeEspecialTributacaoMunicipal|null
     */
    public function obterRegimeEspecialTributacao(): ?RegimeEspecialTributacaoMunicipal
    {
        return $this->regimeEspecialTributacao;
    }

    /**
     * Retorna a descrição do regime especial de tributação
     *
     * @return string|null
     */
    public function obterDescricaoRegimeEspecialTributacao(): ?string
    {
        return $this->regimeEspecialTributacao?->descricao();
    }

    /**
     * Retorna o valor numérico do regime especial de tributação
     *
     * @return int|null
     */
    public function obterValorRegimeEspecialTributacao(): ?int
    {
        return $this->regimeEspecialTributacao?->valor();
    }

    /**
     * Define o regime de tributação simples nacional
     *
     * @param RegimeTributacaoSimplesNacional $regimeTributacaoSimplesNacional
     * @return self
     */
    public function definirRegimeTributacaoSimplesNacional(RegimeTributacaoSimplesNacional $regimeTributacaoSimplesNacional): self
    {
        $this->regimeTributacaoSimplesNacional = $regimeTributacaoSimplesNacional;
        return $this;
    }

    /**
     * Retorna o regime de tributação simples nacional
     *
     * @return RegimeTributacaoSimplesNacional|null
     */
    public function obterRegimeTributacaoSimplesNacional(): ?RegimeTributacaoSimplesNacional
    {
        return $this->regimeTributacaoSimplesNacional;
    }
}

