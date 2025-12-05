<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Entity;

use NfseNacional\Domain\Contract\ValidatableInterface;
use NfseNacional\Domain\Exception\ValidationException;

/**
 * Entidade para Declaração de Prestação de Serviços (DPS)
 */
class Dps implements ValidatableInterface
{
    public function __construct(
        public readonly string $numero,
        public readonly string $serie,
        public readonly \DateTimeInterface $dataEmissao,
        public readonly Prestador $prestador,
        public readonly Tomador $tomador,
        public readonly Servico|array $servico,
        public readonly ?array $intermediario = null,
        public readonly ?array $construcaoCivil = null,
        public readonly ?string $informacoesComplementares = null
    ) {
    }

    /**
     * Cria uma instância a partir de um array
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $prestador = $data['prestador'] ?? $data['Prestador'] ?? [];
        if (is_array($prestador)) {
            $prestador = Prestador::fromArray($prestador);
        }

        $tomador = $data['tomador'] ?? $data['Tomador'] ?? [];
        if (is_array($tomador)) {
            $tomador = Tomador::fromArray($tomador);
        }

        $servico = $data['servico'] ?? $data['Servico'] ?? [];
        if (is_array($servico) && !empty($servico)) {
            $servico = Servico::fromArray($servico);
        }

        return new self(
            numero: $data['numero'] ?? $data['Numero'] ?? '',
            serie: $data['serie'] ?? $data['Serie'] ?? '1',
            dataEmissao: isset($data['dataEmissao'])
                ? new \DateTime($data['dataEmissao'])
                : (isset($data['DataEmissao']) ? new \DateTime($data['DataEmissao']) : new \DateTime()),
            prestador: $prestador,
            tomador: $tomador,
            servico: $servico,
            intermediario: $data['intermediario'] ?? $data['Intermediario'] ?? null,
            construcaoCivil: $data['construcaoCivil'] ?? $data['ConstrucaoCivil'] ?? null,
            informacoesComplementares: $data['informacoesComplementares'] ?? $data['InformacoesComplementares'] ?? null
        );
    }

    /**
     * Converte para array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $servicoArray = $this->servico instanceof Servico
            ? $this->servico->toArray()
            : $this->servico;

        return array_filter([
            'numero' => $this->numero,
            'serie' => $this->serie,
            'dataEmissao' => $this->dataEmissao->format('Y-m-d\TH:i:s'),
            'prestador' => $this->prestador->toArray(),
            'tomador' => $this->tomador->toArray(),
            'servico' => $servicoArray,
            'intermediario' => $this->intermediario,
            'construcaoCivil' => $this->construcaoCivil,
            'informacoesComplementares' => $this->informacoesComplementares,
        ], fn($value) => $value !== null);
    }

    /**
     * Valida a entidade
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate(): bool
    {
        $errors = [];

        if (empty($this->numero)) {
            $errors['numero'][] = "Número da DPS é obrigatório";
        }

        if (empty($this->serie)) {
            $errors['serie'][] = "Série da DPS é obrigatória";
        }

        if ($this->servico instanceof Servico) {
            if (empty($this->servico->itemListaServico)) {
                $errors['servico.itemListaServico'][] = "Item da lista de serviço é obrigatório";
            }
            if ($this->servico->valorServicos <= 0) {
                $errors['servico.valorServicos'][] = "Valor do serviço deve ser maior que zero";
            }
        } elseif (is_array($this->servico)) {
            if (empty($this->servico)) {
                $errors['servico'][] = "Dados do serviço são obrigatórios";
            }
        }

        if (!empty($errors)) {
            $exception = new ValidationException("Validação da DPS falhou", $errors);
            throw $exception;
        }

        return true;
    }

    /**
     * Retorna o ID único da DPS (para assinatura)
     */
    public function getId(): string
    {
        return 'DPS' . $this->numero;
    }

    /**
     * Retorna o serviço como objeto Servico
     */
    public function getServico(): Servico
    {
        if ($this->servico instanceof Servico) {
            return $this->servico;
        }

        return Servico::fromArray($this->servico);
    }
}

