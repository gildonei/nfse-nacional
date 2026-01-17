<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

use InvalidArgumentException;
use DateTime;
use NfseNacional\Domain\Enum\ListaServicosNacional;
use NfseNacional\Domain\Enum\ModoPrestacao;
use NfseNacional\Domain\Enum\TributacaoIssqn;
use NfseNacional\Domain\Enum\TipoEmitente;
use NfseNacional\Domain\Enum\VinculoEntrePartes;

/**
 * Entidade DPS (Documento de Prestação de Serviços)
 *
 * Responsável pela construção e validação de dados da DPS
 *
 * @package NfseNacional\Domain\Entity
 */
class Dps
{
    /**
     * Prestador de serviços
     * @var Prestador|null
     */
    private ?Prestador $prestador = null;

    /**
     * Tomador de serviços
     * @var Tomador|null
     */
    private ?Tomador $tomador = null;

    /**
     * Tipo de ambiente (1 = Produção, 2 = Homologação)
     * @var int|null
     */
    private ?int $tipoAmbiente = null;

    /**
     * Data e hora de emissão
     * @var DateTime|null
     */
    private ?DateTime $dataHoraEmissao = null;

    /**
     * Versão da aplicação
     * @var string|null
     */
    private ?string $versaoAplicacao = null;

    /**
     * Série da DPS
     * @var string|null
     */
    private ?string $serie = null;

    /**
     * Número da DPS
     * @var string|null
     */
    private ?string $numeroDps = null;

    /**
     * Data de competência
     * @var DateTime|null
     */
    private ?DateTime $dataCompetencia = null;

    /**
     * Tipo de emitente
     * @var TipoEmitente|null
     */
    private ?TipoEmitente $tipoEmitente = null;

    /**
     * Código motivo de emissão TI
     * @var int|null
     */
    private ?int $codigoMotivoEmissaoTI = null;

    /**
     * Chave da NFS-e rejeitada
     * @var string|null
     */
    private ?string $chaveNfseRejeitada = null;

    /**
     * Código do local de emissão
     * @var string|null
     */
    private ?string $codigoLocalEmissao = null;

    /**
     * Chave da DPS substituída
     * @var string|null
     */
    private ?string $chaveSubstituida = null;

    /**
     * Código do motivo da substituição
     * @var int|null
     */
    private ?int $codigoMotivoSubstituicao = null;

    /**
     * Descrição do motivo da substituição
     * @var string|null
     */
    private ?string $descricaoMotivoSubstituicao = null;

    /**
     * Lista de serviços
     * @var array
     */
    private array $servicos = [];

    /**
     * Código do local de prestação
     * @var string|null
     */
    private ?string $codigoLocalPrestacao = null;

    /**
     * Código do país de prestação
     * @var int|null
     */
    private ?int $codigoPaisPrestacao = null;

    /**
     * Modo de prestação
     * @var ModoPrestacao|null
     */
    private ?ModoPrestacao $modoPrestacao = null;

    /**
     * Vínculo entre partes
     * @var VinculoEntrePartes|null
     */
    private ?VinculoEntrePartes $vinculoEntrePartes = null;

    /**
     * Código tributação nacional
     * @var string|null
     */
    private ?string $codigoTributacaoNacional = null;

    /**
     * Código tributação municipal
     * @var string|null
     */
    private ?string $codigoTributacaoMunicipal = null;

    /**
     * Descrição do serviço
     * @var string|null
     */
    private ?string $descricaoServico = null;

    /**
     * Código NBS
     * @var string|null
     */
    private ?string $codigoNbs = null;

    /**
     * Código interno do contribuinte
     * @var string|null
     */
    private ?string $codigoInternoContribuinte = null;

    /**
     * Valor do serviço prestado
     * @var float|null
     */
    private ?float $valorServicoPrestado = null;

    /**
     * Valor recebido
     * @var float|null
     */
    private ?float $valorRecebido = null;

    /**
     * Valor do serviço
     * @var float|null
     */
    private ?float $valorServico = null;

    /**
     * Tributação ISSQN
     * @var TributacaoIssqn|null
     */
    private ?TributacaoIssqn $tributacaoIssqn = null;

    /**
     * Tipo de retenção ISSQN
     * @var int|null
     */
    private ?int $tipoRetencaoIssqn = null;

    /**
     * Percentual de alíquota
     * @var float|null
     */
    private ?float $percentualAliquota = null;

    /**
     * Define o prestador
     *
     * @param Prestador $prestador
     * @return self
     */
    public function definirPrestador(Prestador $prestador): self
    {
        $this->prestador = $prestador;
        return $this;
    }

    /**
     * Retorna o prestador
     *
     * @return Prestador
     */
    public function obterPrestador(): Prestador
    {
        return $this->prestador ?? new Prestador();
    }

    /**
     * Define o tomador
     *
     * @param Tomador $tomador
     * @return self
     */
    public function definirTomador(Tomador $tomador): self
    {
        $this->tomador = $tomador;
        return $this;
    }

    /**
     * Retorna o tomador
     *
     * @return Tomador
     */
    public function obterTomador(): Tomador
    {
        return $this->tomador ?? new Tomador();
    }

    /**
     * Define o tipo de ambiente
     *
     * @param int $tipoAmbiente
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirTipoAmbiente(int $tipoAmbiente): self
    {
        if ($tipoAmbiente !== 1 && $tipoAmbiente !== 2) {
            throw new InvalidArgumentException('Tipo de ambiente deve ser 1 (Produção) ou 2 (Homologação)!');
        }
        $this->tipoAmbiente = $tipoAmbiente;
        return $this;
    }

    /**
     * Retorna o tipo de ambiente
     *
     * @return int|null
     */
    public function obterTipoAmbiente(): ?int
    {
        return $this->tipoAmbiente;
    }

    /**
     * Define a data e hora de emissão
     *
     * @param DateTime $dataHoraEmissao
     * @return self
     */
    public function definirDataHoraEmissao(DateTime $dataHoraEmissao): self
    {
        $this->dataHoraEmissao = $dataHoraEmissao;
        return $this;
    }

    /**
     * Retorna a data e hora de emissão
     *
     * @return DateTime|null
     */
    public function obterDataHoraEmissao(): ?DateTime
    {
        return $this->dataHoraEmissao;
    }

    /**
     * Define a versão da aplicação
     *
     * @param string $versaoAplicacao
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirVersaoAplicacao(string $versaoAplicacao): self
    {
        if (empty(trim($versaoAplicacao))) {
            throw new InvalidArgumentException('Versão da aplicação está vazia!');
        }
        $this->versaoAplicacao = $versaoAplicacao;
        return $this;
    }

    /**
     * Retorna a versão da aplicação
     *
     * @return string|null
     */
    public function obterVersaoAplicacao(): ?string
    {
        return $this->versaoAplicacao;
    }

    /**
     * Define a série
     *
     * @param string $serie
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirSerie(string $serie): self
    {
        if (empty(trim($serie))) {
            throw new InvalidArgumentException('Série está vazia!');
        }
        $this->serie = $serie;
        return $this;
    }

    /**
     * Retorna a série
     *
     * @return string|null
     */
    public function obterSerie(): ?string
    {
        return $this->serie;
    }

    /**
     * Define o número da DPS
     *
     * @param string $numeroDps
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirNumeroDps(string $numeroDps): self
    {
        if (empty(trim($numeroDps))) {
            throw new InvalidArgumentException('Número da DPS está vazio!');
        }
        $this->numeroDps = $numeroDps;
        return $this;
    }

    /**
     * Retorna o número da DPS
     *
     * @return string|null
     */
    public function obterNumeroDps(): ?string
    {
        return $this->numeroDps;
    }

    /**
     * Define a data de competência
     *
     * @param DateTime $dataCompetencia
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirDataCompetencia(DateTime $dataCompetencia): self
    {
        if ($dataCompetencia === null) {
            throw new InvalidArgumentException('Data de competência está vazia!');
        }
        $this->dataCompetencia = $dataCompetencia;
        return $this;
    }

    /**
     * Retorna a data de competência
     *
     * @return DateTime|null
     */
    public function obterDataCompetencia(): ?DateTime
    {
        return $this->dataCompetencia;
    }

    /**
     * Define o tipo de emitente
     *
     * @param TipoEmitente $tipoEmitente
     * @return self
     */
    public function definirTipoEmitente(TipoEmitente $tipoEmitente): self
    {
        $this->tipoEmitente = $tipoEmitente;
        return $this;
    }

    /**
     * Retorna o tipo de emitente
     *
     * @return TipoEmitente|null
     */
    public function obterTipoEmitente(): ?TipoEmitente
    {
        return $this->tipoEmitente;
    }

    /**
     * Define o código motivo de emissão TI
     *
     * @param int $codigoMotivoEmissaoTI
     * @return self
     */
    public function definirCodigoMotivoEmissaoTI(int $codigoMotivoEmissaoTI): self
    {
        $this->codigoMotivoEmissaoTI = $codigoMotivoEmissaoTI;
        return $this;
    }

    /**
     * Retorna o código motivo de emissão TI
     *
     * @return int|null
     */
    public function obterCodigoMotivoEmissaoTI(): ?int
    {
        return $this->codigoMotivoEmissaoTI;
    }

    /**
     * Define a chave da NFS-e rejeitada
     *
     * @param string $chaveNfseRejeitada
     * @return self
     */
    public function definirChaveNfseRejeitada(string $chaveNfseRejeitada): self
    {
        $this->chaveNfseRejeitada = $chaveNfseRejeitada;
        return $this;
    }

    /**
     * Retorna a chave da NFS-e rejeitada
     *
     * @return string|null
     */
    public function obterChaveNfseRejeitada(): ?string
    {
        return $this->chaveNfseRejeitada;
    }

    /**
     * Define o código do local de emissão
     *
     * @param string $codigoLocalEmissao
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCodigoLocalEmissao(string $codigoLocalEmissao): self
    {
        if (empty(trim($codigoLocalEmissao))) {
            throw new InvalidArgumentException('Código do local de emissão está vazio!');
        }
        $this->codigoLocalEmissao = $codigoLocalEmissao;
        return $this;
    }

    /**
     * Retorna o código do local de emissão
     *
     * @return string|null
     */
    public function obterCodigoLocalEmissao(): ?string
    {
        return $this->codigoLocalEmissao;
    }

    /**
     * Define a chave da DPS substituída
     *
     * @param string $chaveSubstituida
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirChaveSubstituida(string $chaveSubstituida): self
    {
        if (empty(trim($chaveSubstituida))) {
            throw new InvalidArgumentException('Chave da DPS substituída está vazia!');
        }
        $this->chaveSubstituida = $chaveSubstituida;
        return $this;
    }

    /**
     * Retorna a chave da DPS substituída
     *
     * @return string|null
     */
    public function obterChaveSubstituida(): ?string
    {
        return $this->chaveSubstituida;
    }

    /**
     * Define o código do motivo da substituição
     *
     * @param int $codigoMotivoSubstituicao
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCodigoMotivoSubstituicao(int $codigoMotivoSubstituicao): self
    {
        if ($codigoMotivoSubstituicao <= 0) {
            throw new InvalidArgumentException('Código do motivo da substituição inválido!');
        }
        $this->codigoMotivoSubstituicao = $codigoMotivoSubstituicao;
        return $this;
    }

    /**
     * Retorna o código do motivo da substituição
     *
     * @return int|null
     */
    public function obterCodigoMotivoSubstituicao(): ?int
    {
        return $this->codigoMotivoSubstituicao;
    }

    /**
     * Define a descrição do motivo da substituição
     *
     * @param string $descricaoMotivoSubstituicao
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirDescricaoMotivoSubstituicao(string $descricaoMotivoSubstituicao): self
    {
        if (empty(trim($descricaoMotivoSubstituicao))) {
            throw new InvalidArgumentException('Descrição do motivo da substituição está vazia!');
        }
        $this->descricaoMotivoSubstituicao = $descricaoMotivoSubstituicao;
        return $this;
    }

    /**
     * Retorna a descrição do motivo da substituição
     *
     * @return string|null
     */
    public function obterDescricaoMotivoSubstituicao(): ?string
    {
        return $this->descricaoMotivoSubstituicao;
    }

    /**
     * Adiciona um serviço à lista
     *
     * @param array $servico
     * @return self
     */
    public function adicionarServico(array $servico): self
    {
        $this->servicos[] = $servico;
        return $this;
    }

    /**
     * Retorna a lista de serviços
     *
     * @return array
     */
    public function obterServicos(): array
    {
        return $this->servicos;
    }

    /**
     * Define o código do local de prestação
     *
     * @param string $codigoLocalPrestacao
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCodigoLocalPrestacao(string $codigoLocalPrestacao): self
    {
        if (empty(trim($codigoLocalPrestacao))) {
            throw new InvalidArgumentException('Código do local de prestação está vazio!');
        }
        $this->codigoLocalPrestacao = $codigoLocalPrestacao;
        return $this;
    }

    /**
     * Retorna o código do local de prestação
     *
     * @return string|null
     */
    public function obterCodigoLocalPrestacao(): ?string
    {
        return $this->codigoLocalPrestacao;
    }

    /**
     * Define o código do país de prestação
     *
     * @param int $codigoPaisPrestacao
     * @return self
     */
    public function definirCodigoPaisPrestacao(int $codigoPaisPrestacao): self
    {
        $this->codigoPaisPrestacao = $codigoPaisPrestacao;
        return $this;
    }

    /**
     * Retorna o código do país de prestação
     *
     * @return int|null
     */
    public function obterCodigoPaisPrestacao(): ?int
    {
        return $this->codigoPaisPrestacao;
    }

    /**
     * Define o modo de prestação
     *
     * @param ModoPrestacao $modoPrestacao
     * @return self
     */
    public function definirModoPrestacao(ModoPrestacao $modoPrestacao): self
    {
        $this->modoPrestacao = $modoPrestacao;
        return $this;
    }

    /**
     * Retorna o modo de prestação
     *
     * @return ModoPrestacao|null
     */
    public function obterModoPrestacao(): ?ModoPrestacao
    {
        return $this->modoPrestacao;
    }

    /**
     * Define o vínculo entre partes
     *
     * @param VinculoEntrePartes $vinculoEntrePartes
     * @return self
     */
    public function definirVinculoEntrePartes(VinculoEntrePartes $vinculoEntrePartes): self
    {
        $this->vinculoEntrePartes = $vinculoEntrePartes;
        return $this;
    }

    /**
     * Retorna o vínculo entre partes
     *
     * @return VinculoEntrePartes|null
     */
    public function obterVinculoEntrePartes(): ?VinculoEntrePartes
    {
        return $this->vinculoEntrePartes;
    }

    /**
     * Define o código de tributação nacional
     *
     * @param string|ListaServicosNacional $codigoTributacaoNacional Código de 6 dígitos ou enum ListaServicosNacional
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCodigoTributacaoNacional(string|ListaServicosNacional $codigoTributacaoNacional): self
    {
        // Se for enum, obtém o valor string
        if ($codigoTributacaoNacional instanceof ListaServicosNacional) {
            $this->codigoTributacaoNacional = $codigoTributacaoNacional->value;
            return $this;
        }

        $codigo = trim($codigoTributacaoNacional);

        if (empty($codigo)) {
            throw new InvalidArgumentException('Código de tributação nacional está vazio!');
        }

        // Valida o formato (6 dígitos numéricos)
        if (!preg_match('/^\d{6}$/', $codigo)) {
            throw new InvalidArgumentException('Código de tributação nacional deve ter exatamente 6 dígitos numéricos!');
        }

        // Valida se o código existe na lista de serviços nacional
        if (!ListaServicosNacional::isValid($codigo)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Código de tributação nacional "%s" não é válido. Utilize um código da Lista de Serviços Nacional (LC 116/2003).',
                    $codigo
                )
            );
        }

        $this->codigoTributacaoNacional = $codigo;
        return $this;
    }

    /**
     * Retorna o código de tributação nacional
     *
     * @return string|null
     */
    public function obterCodigoTributacaoNacional(): ?string
    {
        return $this->codigoTributacaoNacional;
    }

    /**
     * Define o código de tributação municipal
     *
     * @param string $codigoTributacaoMunicipal
     * @return self
     */
    public function definirCodigoTributacaoMunicipal(string $codigoTributacaoMunicipal): self
    {
        $this->codigoTributacaoMunicipal = $codigoTributacaoMunicipal;
        return $this;
    }

    /**
     * Retorna o código de tributação municipal
     *
     * @return string|null
     */
    public function obterCodigoTributacaoMunicipal(): ?string
    {
        return $this->codigoTributacaoMunicipal;
    }

    /**
     * Define a descrição do serviço
     *
     * @param string $descricaoServico
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirDescricaoServico(string $descricaoServico): self
    {
        if (empty(trim($descricaoServico))) {
            throw new InvalidArgumentException('Descrição do serviço está vazia!');
        }
        $this->descricaoServico = $descricaoServico;
        return $this;
    }

    /**
     * Retorna a descrição do serviço
     *
     * @return string|null
     */
    public function obterDescricaoServico(): ?string
    {
        return $this->descricaoServico;
    }

    /**
     * Define o código NBS
     *
     * @param string $codigoNbs
     * @return self
     */
    public function definirCodigoNbs(string $codigoNbs): self
    {
        $this->codigoNbs = $codigoNbs;
        return $this;
    }

    /**
     * Retorna o código NBS
     *
     * @return string|null
     */
    public function obterCodigoNbs(): ?string
    {
        return $this->codigoNbs;
    }

    /**
     * Define o código interno do contribuinte
     *
     * @param string $codigoInternoContribuinte
     * @return self
     */
    public function definirCodigoInternoContribuinte(string $codigoInternoContribuinte): self
    {
        $this->codigoInternoContribuinte = $codigoInternoContribuinte;
        return $this;
    }

    /**
     * Retorna o código interno do contribuinte
     *
     * @return string|null
     */
    public function obterCodigoInternoContribuinte(): ?string
    {
        return $this->codigoInternoContribuinte;
    }

    /**
     * Define o valor do serviço prestado
     *
     * @param float $valorServicoPrestado
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirValorServicoPrestado(float $valorServicoPrestado): self
    {
        if ($valorServicoPrestado < 0) {
            throw new InvalidArgumentException('Valor do serviço prestado não pode ser negativo!');
        }
        $this->valorServicoPrestado = $valorServicoPrestado;
        return $this;
    }

    /**
     * Retorna o valor do serviço prestado
     *
     * @return float|null
     */
    public function obterValorServicoPrestado(): ?float
    {
        return $this->valorServicoPrestado;
    }

    /**
     * Define o valor recebido
     *
     * @param float $valorRecebido
     * @return self
     */
    public function definirValorRecebido(float $valorRecebido): self
    {
        $this->valorRecebido = $valorRecebido;
        return $this;
    }

    /**
     * Retorna o valor recebido
     *
     * @return float|null
     */
    public function obterValorRecebido(): ?float
    {
        return $this->valorRecebido;
    }

    /**
     * Define o valor do serviço
     *
     * @param float $valorServico
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirValorServico(float $valorServico): self
    {
        if ($valorServico < 0) {
            throw new InvalidArgumentException('Valor do serviço não pode ser negativo!');
        }
        $this->valorServico = $valorServico;
        return $this;
    }

    /**
     * Retorna o valor do serviço
     *
     * @return float|null
     */
    public function obterValorServico(): ?float
    {
        return $this->valorServico;
    }

    /**
     * Define a tributação ISSQN
     *
     * @param TributacaoIssqn $tributacaoIssqn
     * @return self
     */
    public function definirTributacaoIssqn(TributacaoIssqn $tributacaoIssqn): self
    {
        $this->tributacaoIssqn = $tributacaoIssqn;
        return $this;
    }

    /**
     * Retorna a tributação ISSQN
     *
     * @return TributacaoIssqn|null
     */
    public function obterTributacaoIssqn(): ?TributacaoIssqn
    {
        return $this->tributacaoIssqn;
    }

    /**
     * Define o tipo de retenção ISSQN
     *
     * @param int $tipoRetencaoIssqn
     * @return self
     */
    public function definirTipoRetencaoIssqn(int $tipoRetencaoIssqn): self
    {
        $this->tipoRetencaoIssqn = $tipoRetencaoIssqn;
        return $this;
    }

    /**
     * Retorna o tipo de retenção ISSQN
     *
     * @return int|null
     */
    public function obterTipoRetencaoIssqn(): ?int
    {
        return $this->tipoRetencaoIssqn;
    }

    /**
     * Define o percentual de alíquota
     *
     * @param float $percentualAliquota
     * @return self
     */
    public function definirPercentualAliquota(float $percentualAliquota): self
    {
        $this->percentualAliquota = $percentualAliquota;
        return $this;
    }

    /**
     * Retorna o percentual de alíquota
     *
     * @return float|null
     */
    public function obterPercentualAliquota(): ?float
    {
        return $this->percentualAliquota;
    }
}
