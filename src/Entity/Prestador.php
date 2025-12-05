<?php

declare(strict_types=1);

namespace NfseNacional\Entity;

/**
 * Entidade para Prestador de Serviços
 */
class Prestador
{
    public function __construct(
        public readonly ?string $cnpj = null,
        public readonly ?string $cpf = null,
        public readonly ?string $razaoSocial = null,
        public readonly ?string $nomeFantasia = null,
        public readonly ?string $inscricaoMunicipal = null,
        public readonly ?string $inscricaoEstadual = null,
        public readonly ?Endereco $endereco = null,
        public readonly ?Contato $contato = null
    ) {
        // Validação: deve ter CPF ou CNPJ
        if ($this->cnpj === null && $this->cpf === null) {
            throw new \InvalidArgumentException("Prestador deve ter CPF ou CNPJ");
        }

        // Não pode ter ambos
        if ($this->cnpj !== null && $this->cpf !== null) {
            throw new \InvalidArgumentException("Prestador não pode ter CPF e CNPJ simultaneamente");
        }
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        $endereco = null;
        if (isset($data['endereco']) && is_array($data['endereco'])) {
            $endereco = Endereco::fromArray($data['endereco']);
        }

        $contato = null;
        if (isset($data['contato']) && is_array($data['contato'])) {
            $contato = Contato::fromArray($data['contato']);
        }

        return new self(
            cnpj: $data['cnpj'] ?? $data['Cnpj'] ?? null,
            cpf: $data['cpf'] ?? $data['Cpf'] ?? null,
            razaoSocial: $data['razaoSocial'] ?? $data['RazaoSocial'] ?? null,
            nomeFantasia: $data['nomeFantasia'] ?? $data['NomeFantasia'] ?? null,
            inscricaoMunicipal: $data['inscricaoMunicipal'] ?? $data['InscricaoMunicipal'] ?? null,
            inscricaoEstadual: $data['inscricaoEstadual'] ?? $data['InscricaoEstadual'] ?? null,
            endereco: $endereco,
            contato: $contato
        );
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return array_filter([
            'cnpj' => $this->cnpj,
            'cpf' => $this->cpf,
            'razaoSocial' => $this->razaoSocial,
            'nomeFantasia' => $this->nomeFantasia,
            'inscricaoMunicipal' => $this->inscricaoMunicipal,
            'inscricaoEstadual' => $this->inscricaoEstadual,
            'endereco' => $this->endereco?->toArray(),
            'contato' => $this->contato?->toArray(),
        ], fn($value) => $value !== null);
    }
}

