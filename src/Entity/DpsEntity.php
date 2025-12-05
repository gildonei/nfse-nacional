<?php

declare(strict_types=1);

namespace NfseNacional\Entity;

use DOMDocument;
use DOMElement;
use NfseNacional\Exceptions\ValidationException;

/**
 * Entidade para Declaração de Prestação de Serviços (DPS)
 */
class DpsEntity extends AbstractXml
{
    private string $numero;
    private string $serie;
    private \DateTimeInterface $dataEmissao;
    private Prestador $prestador;
    private Tomador $tomador;
    private array $servico;
    private ?array $intermediario = null;
    private ?array $construcaoCivil = null;
    private ?string $informacoesComplementares = null;

    public function __construct(
        string $numero,
        string $serie,
        \DateTimeInterface $dataEmissao,
        Prestador|array $prestador,
        Tomador|array $tomador,
        array $servico,
        ?array $intermediario = null,
        ?array $construcaoCivil = null,
        ?string $informacoesComplementares = null
    ) {
        $this->numero = $numero;
        $this->serie = $serie;
        $this->dataEmissao = $dataEmissao;

        // Aceita Prestador ou array (para compatibilidade)
        $this->prestador = $prestador instanceof Prestador
            ? $prestador
            : Prestador::fromArray($prestador);

        // Aceita Tomador ou array (para compatibilidade)
        $this->tomador = $tomador instanceof Tomador
            ? $tomador
            : Tomador::fromArray($tomador);

        $this->servico = $servico;
        $this->intermediario = $intermediario;
        $this->construcaoCivil = $construcaoCivil;
        $this->informacoesComplementares = $informacoesComplementares;
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            numero: $data['numero'] ?? $data['Numero'] ?? '',
            serie: $data['serie'] ?? $data['Serie'] ?? '1',
            dataEmissao: isset($data['dataEmissao'])
                ? new \DateTime($data['dataEmissao'])
                : (isset($data['DataEmissao']) ? new \DateTime($data['DataEmissao']) : new \DateTime()),
            prestador: $data['prestador'] ?? $data['Prestador'] ?? [],
            tomador: $data['tomador'] ?? $data['Tomador'] ?? [],
            servico: $data['servico'] ?? $data['Servico'] ?? [],
            intermediario: $data['intermediario'] ?? $data['Intermediario'] ?? null,
            construcaoCivil: $data['construcaoCivil'] ?? $data['ConstrucaoCivil'] ?? null,
            informacoesComplementares: $data['informacoesComplementares'] ?? $data['InformacoesComplementares'] ?? null
        );
    }

    /**
     * Converte para XML (DOMDocument)
     *
     * @return DOMDocument
     */
    public function toXml(): DOMDocument
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = false;

        // Elemento raiz DPS
        $dps = $doc->createElement('DPS');
        $dps->setAttribute('xmlns', 'http://www.abrasf.org.br/nfse.xsd');
        $doc->appendChild($dps);

        // InfDPS
        $infDps = $doc->createElement('InfDPS');
        $infDps->setAttribute('Id', 'DPS' . $this->numero);
        $dps->appendChild($infDps);

        // IdentificacaoDPS
        $identificacao = $doc->createElement('IdentificacaoDPS');
        $identificacao->appendChild($doc->createElement('Numero', $this->numero));
        $identificacao->appendChild($doc->createElement('Serie', $this->serie));
        $infDps->appendChild($identificacao);

        // DataEmissao
        $infDps->appendChild($doc->createElement('DataEmissao', $this->dataEmissao->format('Y-m-d\TH:i:s')));

        // Prestador
        $prestador = $this->createPrestadorElement($doc);
        if ($prestador !== null) {
            $infDps->appendChild($prestador);
        }

        // Tomador
        $tomador = $this->createTomadorElement($doc);
        if ($tomador !== null) {
            $infDps->appendChild($tomador);
        }

        // Intermediario (opcional)
        if ($this->intermediario !== null) {
            $intermediario = $this->createIntermediarioElement($doc);
            if ($intermediario !== null) {
                $infDps->appendChild($intermediario);
            }
        }

        // Servico
        $servico = $this->createServicoElement($doc);
        if ($servico !== null) {
            $infDps->appendChild($servico);
        }

        // ConstrucaoCivil (opcional)
        if ($this->construcaoCivil !== null) {
            $construcao = $this->createConstrucaoCivilElement($doc);
            if ($construcao !== null) {
                $infDps->appendChild($construcao);
            }
        }

        // InformacoesComplementares (opcional)
        if ($this->informacoesComplementares !== null) {
            $infDps->appendChild($doc->createElement('InformacoesComplementares', $this->informacoesComplementares));
        }

        return $doc;
    }

    /**
     * Cria elemento Prestador
     */
    private function createPrestadorElement(DOMDocument $doc): ?DOMElement
    {
        $prestador = $doc->createElement('Prestador');
        $cpfCnpj = $doc->createElement('CpfCnpj');

        if ($this->prestador->cnpj !== null) {
            $cpfCnpj->appendChild($doc->createElement('Cnpj', $this->prestador->cnpj));
        } elseif ($this->prestador->cpf !== null) {
            $cpfCnpj->appendChild($doc->createElement('Cpf', $this->prestador->cpf));
        }

        $prestador->appendChild($cpfCnpj);

        if ($this->prestador->razaoSocial !== null) {
            $prestador->appendChild($doc->createElement('RazaoSocial', $this->prestador->razaoSocial));
        }

        if ($this->prestador->nomeFantasia !== null) {
            $prestador->appendChild($doc->createElement('NomeFantasia', $this->prestador->nomeFantasia));
        }

        if ($this->prestador->inscricaoMunicipal !== null) {
            $prestador->appendChild($doc->createElement('InscricaoMunicipal', $this->prestador->inscricaoMunicipal));
        }

        if ($this->prestador->inscricaoEstadual !== null) {
            $prestador->appendChild($doc->createElement('InscricaoEstadual', $this->prestador->inscricaoEstadual));
        }

        if ($this->prestador->endereco !== null) {
            $endereco = $this->createEnderecoElementFromEntity($doc, $this->prestador->endereco);
            if ($endereco !== null) {
                $prestador->appendChild($endereco);
            }
        }

        if ($this->prestador->contato !== null) {
            $contato = $this->createContatoElementFromEntity($doc, $this->prestador->contato);
            if ($contato !== null) {
                $prestador->appendChild($contato);
            }
        }

        return $prestador;
    }

    /**
     * Cria elemento Tomador
     */
    private function createTomadorElement(DOMDocument $doc): ?DOMElement
    {
        $tomador = $doc->createElement('Tomador');
        $cpfCnpj = $doc->createElement('CpfCnpj');

        if ($this->tomador->cnpj !== null) {
            $cpfCnpj->appendChild($doc->createElement('Cnpj', $this->tomador->cnpj));
        } elseif ($this->tomador->cpf !== null) {
            $cpfCnpj->appendChild($doc->createElement('Cpf', $this->tomador->cpf));
        }

        $tomador->appendChild($cpfCnpj);

        if ($this->tomador->razaoSocial !== null) {
            $tomador->appendChild($doc->createElement('RazaoSocial', $this->tomador->razaoSocial));
        }

        if ($this->tomador->nomeFantasia !== null) {
            $tomador->appendChild($doc->createElement('NomeFantasia', $this->tomador->nomeFantasia));
        }

        if ($this->tomador->inscricaoMunicipal !== null) {
            $tomador->appendChild($doc->createElement('InscricaoMunicipal', $this->tomador->inscricaoMunicipal));
        }

        if ($this->tomador->inscricaoEstadual !== null) {
            $tomador->appendChild($doc->createElement('InscricaoEstadual', $this->tomador->inscricaoEstadual));
        }

        if ($this->tomador->endereco !== null) {
            $endereco = $this->createEnderecoElementFromEntity($doc, $this->tomador->endereco);
            if ($endereco !== null) {
                $tomador->appendChild($endereco);
            }
        }

        if ($this->tomador->contato !== null) {
            $contato = $this->createContatoElementFromEntity($doc, $this->tomador->contato);
            if ($contato !== null) {
                $tomador->appendChild($contato);
            }
        }

        return $tomador;
    }

    /**
     * Cria elemento Intermediario
     */
    private function createIntermediarioElement(DOMDocument $doc): ?DOMElement
    {
        if (empty($this->intermediario)) {
            return null;
        }

        $intermediario = $doc->createElement('Intermediario');
        $cpfCnpj = $doc->createElement('CpfCnpj');

        if (isset($this->intermediario['cnpj'])) {
            $cpfCnpj->appendChild($doc->createElement('Cnpj', $this->intermediario['cnpj']));
        } elseif (isset($this->intermediario['cpf'])) {
            $cpfCnpj->appendChild($doc->createElement('Cpf', $this->intermediario['cpf']));
        }

        $intermediario->appendChild($cpfCnpj);

        if (isset($this->intermediario['razaoSocial'])) {
            $intermediario->appendChild($doc->createElement('RazaoSocial', $this->intermediario['razaoSocial']));
        }

        return $intermediario;
    }

    /**
     * Cria elemento Servico
     */
    private function createServicoElement(DOMDocument $doc): ?DOMElement
    {
        if (empty($this->servico)) {
            return null;
        }

        $servico = $doc->createElement('Servico');

        // Valores
        if (isset($this->servico['valores'])) {
            $valores = $doc->createElement('Valores');
            $valoresData = $this->servico['valores'];

            if (isset($valoresData['valorServicos'])) {
                $valores->appendChild($doc->createElement('ValorServicos', number_format((float)$valoresData['valorServicos'], 2, '.', '')));
            }

            if (isset($valoresData['valorDeducoes'])) {
                $valores->appendChild($doc->createElement('ValorDeducoes', number_format((float)$valoresData['valorDeducoes'], 2, '.', '')));
            }

            if (isset($valoresData['valorPis'])) {
                $valores->appendChild($doc->createElement('ValorPis', number_format((float)$valoresData['valorPis'], 2, '.', '')));
            }

            if (isset($valoresData['valorCofins'])) {
                $valores->appendChild($doc->createElement('ValorCofins', number_format((float)$valoresData['valorCofins'], 2, '.', '')));
            }

            if (isset($valoresData['valorInss'])) {
                $valores->appendChild($doc->createElement('ValorInss', number_format((float)$valoresData['valorInss'], 2, '.', '')));
            }

            if (isset($valoresData['valorIr'])) {
                $valores->appendChild($doc->createElement('ValorIr', number_format((float)$valoresData['valorIr'], 2, '.', '')));
            }

            if (isset($valoresData['valorCsll'])) {
                $valores->appendChild($doc->createElement('ValorCsll', number_format((float)$valoresData['valorCsll'], 2, '.', '')));
            }

            if (isset($valoresData['valorIss'])) {
                $valores->appendChild($doc->createElement('ValorIss', number_format((float)$valoresData['valorIss'], 2, '.', '')));
            }

            if (isset($valoresData['valorIssRetido'])) {
                $valores->appendChild($doc->createElement('ValorIssRetido', number_format((float)$valoresData['valorIssRetido'], 2, '.', '')));
            }

            if (isset($valoresData['outrasRetencoes'])) {
                $valores->appendChild($doc->createElement('OutrasRetencoes', number_format((float)$valoresData['outrasRetencoes'], 2, '.', '')));
            }

            if (isset($valoresData['valorLiquidoNfse'])) {
                $valores->appendChild($doc->createElement('ValorLiquidoNfse', number_format((float)$valoresData['valorLiquidoNfse'], 2, '.', '')));
            }

            $servico->appendChild($valores);
        }

        // ItemListaServico
        if (isset($this->servico['itemListaServico'])) {
            $servico->appendChild($doc->createElement('ItemListaServico', $this->servico['itemListaServico']));
        }

        // CodigoCnae
        if (isset($this->servico['codigoCnae'])) {
            $servico->appendChild($doc->createElement('CodigoCnae', $this->servico['codigoCnae']));
        }

        // Discriminacao
        if (isset($this->servico['discriminacao'])) {
            $servico->appendChild($doc->createElement('Discriminacao', $this->servico['discriminacao']));
        }

        // CodigoMunicipio
        if (isset($this->servico['codigoMunicipio'])) {
            $servico->appendChild($doc->createElement('CodigoMunicipio', $this->servico['codigoMunicipio']));
        }

        return $servico;
    }

    /**
     * Cria elemento Endereco a partir de entidade
     */
    private function createEnderecoElementFromEntity(DOMDocument $doc, Endereco $endereco): ?DOMElement
    {
        $enderecoEl = $doc->createElement('Endereco');

        if ($endereco->endereco !== null) {
            $enderecoEl->appendChild($doc->createElement('Endereco', $endereco->endereco));
        }

        if ($endereco->numero !== null) {
            $enderecoEl->appendChild($doc->createElement('Numero', $endereco->numero));
        }

        if ($endereco->complemento !== null) {
            $enderecoEl->appendChild($doc->createElement('Complemento', $endereco->complemento));
        }

        if ($endereco->bairro !== null) {
            $enderecoEl->appendChild($doc->createElement('Bairro', $endereco->bairro));
        }

        if ($endereco->codigoMunicipio !== null) {
            $enderecoEl->appendChild($doc->createElement('CodigoMunicipio', $endereco->codigoMunicipio));
        }

        if ($endereco->uf !== null) {
            $enderecoEl->appendChild($doc->createElement('Uf', $endereco->uf));
        }

        if ($endereco->cep !== null) {
            $enderecoEl->appendChild($doc->createElement('Cep', $endereco->cep));
        }

        if ($endereco->codigoPais !== null) {
            $enderecoEl->appendChild($doc->createElement('CodigoPais', $endereco->codigoPais));
        }

        return $enderecoEl;
    }

    /**
     * Cria elemento Contato a partir de entidade
     */
    private function createContatoElementFromEntity(DOMDocument $doc, Contato $contato): ?DOMElement
    {
        $contatoEl = $doc->createElement('Contato');

        if ($contato->telefone !== null) {
            // Usa o número completo do telefone (DDD + número)
            $contatoEl->appendChild($doc->createElement('Telefone', $contato->telefone->toString()));
        }

        if ($contato->email !== null) {
            $contatoEl->appendChild($doc->createElement('Email', $contato->email->toString()));
        }

        return $contatoEl;
    }

    /**
     * Cria elemento ConstrucaoCivil
     */
    private function createConstrucaoCivilElement(DOMDocument $doc): ?DOMElement
    {
        if (empty($this->construcaoCivil)) {
            return null;
        }

        $construcao = $doc->createElement('ConstrucaoCivil');

        if (isset($this->construcaoCivil['codigoObra'])) {
            $construcao->appendChild($doc->createElement('CodigoObra', $this->construcaoCivil['codigoObra']));
        }

        if (isset($this->construcaoCivil['art'])) {
            $construcao->appendChild($doc->createElement('Art', $this->construcaoCivil['art']));
        }

        return $construcao;
    }

    /**
     * Valida a DPS
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate(): bool
    {
        if (empty($this->numero)) {
            throw new ValidationException("Número da DPS é obrigatório");
        }

        if (empty($this->serie)) {
            throw new ValidationException("Série da DPS é obrigatória");
        }

        // Validação do prestador já é feita no construtor de Prestador
        // Mas verificamos se foi criado corretamente
        if ($this->prestador->cnpj === null && $this->prestador->cpf === null) {
            throw new ValidationException("Prestador deve ter CPF ou CNPJ");
        }

        // Validação do tomador já é feita no construtor de Tomador
        if ($this->tomador->cnpj === null && $this->tomador->cpf === null) {
            throw new ValidationException("Tomador deve ter CPF ou CNPJ");
        }

        if (empty($this->servico)) {
            throw new ValidationException("Dados do serviço são obrigatórios");
        }

        return parent::validate();
    }

    // Getters
    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getSerie(): string
    {
        return $this->serie;
    }

    public function getDataEmissao(): \DateTimeInterface
    {
        return $this->dataEmissao;
    }

    public function getPrestador(): Prestador
    {
        return $this->prestador;
    }

    public function getTomador(): Tomador
    {
        return $this->tomador;
    }
}

