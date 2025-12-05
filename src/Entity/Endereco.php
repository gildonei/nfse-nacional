<?php

declare(strict_types=1);

namespace NfseNacional\Entity;

/**
 * Entidade para Endereço
 */
class Endereco
{
    public function __construct(
        public readonly ?string $endereco = null,
        public readonly ?string $numero = null,
        public readonly ?string $complemento = null,
        public readonly ?string $bairro = null,
        public readonly ?string $codigoMunicipio = null,
        public readonly ?string $uf = null,
        public readonly ?string $cep = null,
        public readonly ?string $codigoPais = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            endereco: $data['endereco'] ?? $data['Endereco'] ?? null,
            numero: $data['numero'] ?? $data['Numero'] ?? null,
            complemento: $data['complemento'] ?? $data['Complemento'] ?? null,
            bairro: $data['bairro'] ?? $data['Bairro'] ?? null,
            codigoMunicipio: $data['codigoMunicipio'] ?? $data['CodigoMunicipio'] ?? null,
            uf: $data['uf'] ?? $data['Uf'] ?? null,
            cep: $data['cep'] ?? $data['Cep'] ?? null,
            codigoPais: $data['codigoPais'] ?? $data['CodigoPais'] ?? null
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return array_filter([
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'bairro' => $this->bairro,
            'codigoMunicipio' => $this->codigoMunicipio,
            'uf' => $this->uf,
            'cep' => $this->cep,
            'codigoPais' => $this->codigoPais,
        ], fn($value) => $value !== null);
    }
}

