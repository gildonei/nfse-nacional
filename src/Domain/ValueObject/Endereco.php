<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject;

use InvalidArgumentException;

/**
 * Value Object Endereço
 *
 * @package NfseNacional\Domain\ValueObject
 */
class Endereco
{
    /**
     * Bairro
     * @var string|null
     */
    private ?string $bairro = null;

    /**
     * Código postal (CEP)
     * @var string|null
     */
    private ?string $cep = null;

    /**
     * Estado (UF)
     * @var string|null
     */
    private ?string $estado = null;

    /**
     * Nome da cidade
     * @var string|null
     */
    private ?string $cidade = null;

    /**
     * Nome da rua
     * @var string|null
     */
    private ?string $logradouro = null;

    /**
     * Número da rua
     * @var string|null
     */
    private ?string $numero = null;

    /**
     * Código IBGE da cidade
     * @var int|null
     */
    private ?int $codigoCidade = null;

    /**
     * Complemento do endereço
     * @var string|null
     */
    private ?string $complemento = null;

    /**
     * Código do país (IBGE)
     * @var int
     */
    private int $pais = 1058;

    /**
     * Estados válidos do Brasil
     */
    private const ESTADOS_VALIDOS = [
        'AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
        'MG', 'MS', 'MT', 'PA', 'PB', 'PE', 'PI', 'PR', 'RJ', 'RN',
        'RO', 'RR', 'RS', 'SC', 'SE', 'SP', 'TO'
    ];

    /**
     * Construtor
     *
     * @param string|null $bairro
     * @param string|null $cep
     * @param string|null $estado
     * @param string|null $cidade
     * @param string|null $rua
     * @param string|null $numero
     * @param int|null $codigoCidade
     * @param string|null $complemento
     * @param int|null $pais
     */
    public function __construct(
        ?string $bairro = null,
        ?string $cep = null,
        ?string $estado = null,
        ?string $cidade = null,
        ?string $rua = null,
        ?string $numero = null,
        ?int $codigoCidade = null,
        ?string $complemento = null,
        ?int $pais = null
    ) {
        if ($bairro !== null) {
            $this->definirBairro($bairro);
        }
        if ($cep !== null) {
            $this->definirCep($cep);
        }
        if ($estado !== null) {
            $this->definirEstado($estado);
        }
        if ($cidade !== null) {
            $this->definirCidade($cidade);
        }
        if ($rua !== null) {
            $this->definirLogradouro($rua);
        }
        if ($numero !== null) {
            $this->definirNumero($numero);
        }
        if ($codigoCidade !== null) {
            $this->definirCodigoCidade($codigoCidade);
        }
        if ($complemento !== null) {
            $this->definirComplemento($complemento);
        }
        if ($pais !== null) {
            $this->definirPais($pais);
        }
    }

    /**
     * Define o bairro
     *
     * @param string $bairro
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirBairro(string $bairro): self
    {
        if (empty(trim($bairro))) {
            throw new InvalidArgumentException('Bairro está vazio!');
        }

        if (strlen($bairro) > 60) {
            throw new InvalidArgumentException('Bairro excede o limite máximo de 60 caracteres!');
        }

        $this->bairro = $bairro;
        return $this;
    }

    /**
     * Retorna o bairro
     *
     * @return string|null
     */
    public function obterBairro(): ?string
    {
        return $this->bairro;
    }

    /**
     * Define o código postal (CEP)
     *
     * @param string $cep
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCep(string $cep): self
    {
        if (empty(trim($cep))) {
            throw new InvalidArgumentException('CEP está vazio!');
        }

        // Remove formatação do CEP
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) > 10) {
            throw new InvalidArgumentException('CEP excede o limite máximo de 10 caracteres!');
        }

        if (!ctype_digit($cep)) {
            throw new InvalidArgumentException('CEP deve conter apenas números!');
        }

        $this->cep = $cep;
        return $this;
    }

    /**
     * Retorna o código postal (CEP)
     *
     * @return string|null
     */
    public function obterCep(): ?string
    {
        return $this->cep;
    }

    /**
     * Define o estado (UF)
     *
     * @param string $estado
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirEstado(string $estado): self
    {
        if (empty(trim($estado))) {
            throw new InvalidArgumentException('Estado está vazio!');
        }

        $estado = strtoupper(trim($estado));

        if (strlen($estado) !== 2) {
            throw new InvalidArgumentException('Estado deve ter exatamente 2 caracteres!');
        }

        if (!in_array($estado, self::ESTADOS_VALIDOS, true)) {
            throw new InvalidArgumentException('Estado inválido!');
        }

        $this->estado = $estado;
        return $this;
    }

    /**
     * Retorna o estado (UF)
     *
     * @return string|null
     */
    public function obterEstado(): ?string
    {
        return $this->estado;
    }

    /**
     * Define o nome da cidade
     *
     * @param string $cidade
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCidade(string $cidade): self
    {
        if (empty(trim($cidade))) {
            throw new InvalidArgumentException('Cidade está vazia!');
        }

        if (strlen($cidade) > 60) {
            throw new InvalidArgumentException('Cidade excede o limite máximo de 60 caracteres!');
        }

        $this->cidade = $cidade;
        return $this;
    }

    /**
     * Retorna o nome da cidade
     *
     * @return string|null
     */
    public function obterCidade(): ?string
    {
        return $this->cidade;
    }

    /**
     * Define o código do país
     *
     * @param int $pais
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirPais(int $pais): self
    {
        if ($pais <= 0) {
            throw new InvalidArgumentException('Código do país está vazio ou inválido!');
        }

        $this->pais = $pais;
        return $this;
    }

    /**
     * Retorna o código do país
     *
     * @return int
     */
    public function obterPais(): int
    {
        return $this->pais;
    }

    /**
     * Define o nome da rua
     *
     * @param string $logradouro
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirLogradouro(string $logradouro): self
    {
        if (empty(trim($logradouro))) {
            throw new InvalidArgumentException('Logradouro está vazio!');
        }

        if (strlen($logradouro) > 60) {
            throw new InvalidArgumentException('Logradouro excede o limite máximo de 60 caracteres!');
        }

        $this->logradouro = $logradouro;
        return $this;
    }

    /**
     * Retorna o logradouro
     *
     * @return string|null
     */
    public function obterLogradouro(): ?string
    {
        return $this->logradouro;
    }

    /**
     * Define o número da rua
     *
     * @param string $numero
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirNumero(string $numero = ''): self
    {
        if (strlen($numero) > 9) {
            throw new InvalidArgumentException('Número da rua excede o limite máximo de 9 caracteres!');
        }

        $this->numero = $numero;
        return $this;
    }

    /**
     * Retorna o número da rua
     *
     * @return string|null
     */
    public function obterNumero(): ?string
    {
        return $this->numero;
    }

    /**
     * Define o código IBGE da cidade
     *
     * @param int $codigoCidade
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirCodigoCidade(int $codigoCidade): self
    {
        if ($codigoCidade <= 0) {
            throw new InvalidArgumentException('Código da cidade está vazio ou inválido!');
        }

        $this->codigoCidade = $codigoCidade;
        return $this;
    }

    /**
     * Retorna o código IBGE da cidade
     *
     * @return int|null
     */
    public function obterCodigoCidade(): ?int
    {
        return $this->codigoCidade;
    }

    /**
     * Define o complemento do endereço
     *
     * @param string $complemento
     * @throws InvalidArgumentException
     * @return self
     */
    public function definirComplemento(string $complemento): self
    {
        if (strlen($complemento) > 30) {
            throw new InvalidArgumentException('Complemento do endereço excede o limite máximo de 30 caracteres!');
        }

        $this->complemento = $complemento;
        return $this;
    }

    /**
     * Retorna o complemento do endereço
     *
     * @return string|null
     */
    public function obterComplemento(): ?string
    {
        return $this->complemento;
    }

    /**
     * Retorna o endereço completo
     *
     * @return string
     */
    public function obterEnderecoCompleto(): string
    {
        return $this->logradouro . ', ' . $this->numero . ' - ' . $this->bairro . ', ' . $this->cidade . ' - ' . $this->estado . ' - ' . $this->cep;
    }

    /**
     * Retorna o endereço completo como string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->obterEnderecoCompleto();
    }
}

