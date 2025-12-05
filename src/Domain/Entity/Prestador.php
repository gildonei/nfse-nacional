<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

use NfseNacional\Domain\Factory\DocumentoFactory;
use NfseNacional\Domain\ValueObject\Contato\Endereco;
use NfseNacional\Domain\ValueObject\Documento\Cnpj;
use NfseNacional\Domain\ValueObject\Documento\Cpf;
use NfseNacional\Domain\ValueObject\Documento\DocumentoInterface;

/**
 * Entidade para Prestador de Serviços
 */
class Prestador
{
    public readonly DocumentoInterface $documento;
    public readonly ?string $razaoSocial;
    public readonly ?string $nomeFantasia;
    public readonly ?string $inscricaoMunicipal;
    public readonly ?string $inscricaoEstadual;
    public readonly ?Endereco $endereco;
    public readonly ?Contato $contato;

    public function __construct(
        DocumentoInterface|string $documento,
        ?string $razaoSocial = null,
        ?string $nomeFantasia = null,
        ?string $inscricaoMunicipal = null,
        ?string $inscricaoEstadual = null,
        ?Endereco $endereco = null,
        ?Contato $contato = null
    ) {
        if (is_string($documento)) {
            $this->documento = DocumentoFactory::criar($documento);
        } else {
            $this->documento = $documento;
        }

        $this->razaoSocial = $razaoSocial;
        $this->nomeFantasia = $nomeFantasia;
        $this->inscricaoMunicipal = $inscricaoMunicipal;
        $this->inscricaoEstadual = $inscricaoEstadual;
        $this->endereco = $endereco;
        $this->contato = $contato;
    }

    /**
     * Cria uma instância a partir de um array
     *
     * @param array<string, mixed> $data
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

        $documento = null;
        if (isset($data['documento']) || isset($data['Documento'])) {
            $docData = $data['documento'] ?? $data['Documento'];
            if ($docData instanceof DocumentoInterface) {
                $documento = $docData;
            } elseif (is_string($docData)) {
                $documento = DocumentoFactory::criar($docData);
            }
        } else {
            $documento = DocumentoFactory::fromArray($data);
        }

        return new self(
            documento: $documento,
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
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [
            'razaoSocial' => $this->razaoSocial,
            'nomeFantasia' => $this->nomeFantasia,
            'inscricaoMunicipal' => $this->inscricaoMunicipal,
            'inscricaoEstadual' => $this->inscricaoEstadual,
            'endereco' => $this->endereco?->toArray(),
            'contato' => $this->contato?->toArray(),
        ];

        if ($this->documento instanceof Cnpj) {
            $array['cnpj'] = $this->documento->getSemFormatacao();
        } elseif ($this->documento instanceof Cpf) {
            $array['cpf'] = $this->documento->getSemFormatacao();
        }

        return array_filter($array, fn($value) => $value !== null);
    }

    public function getDocumento(): DocumentoInterface
    {
        return $this->documento;
    }

    public function getCnpj(): ?string
    {
        return $this->documento instanceof Cnpj ? $this->documento->getSemFormatacao() : null;
    }

    public function getCpf(): ?string
    {
        return $this->documento instanceof Cpf ? $this->documento->getSemFormatacao() : null;
    }
}

