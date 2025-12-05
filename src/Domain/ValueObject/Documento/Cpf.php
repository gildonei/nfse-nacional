<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject\Documento;

/**
 * Value Object para CPF com validação
 */
final class Cpf implements DocumentoInterface
{
    private string $cpf;

    public function __construct(string $cpf)
    {
        $this->setCpf($cpf);
    }

    /**
     * Define e valida o CPF
     */
    private function setCpf(string $cpf): void
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $cpf);

        // Valida comprimento
        if (strlen($cpf) !== 11) {
            throw new \InvalidArgumentException("CPF deve conter exatamente 11 dígitos");
        }

        // Valida se não é uma sequência inválida
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            throw new \InvalidArgumentException("CPF inválido: sequência repetida");
        }

        // Valida dígitos verificadores
        if (!$this->validarDigitosVerificadores($cpf)) {
            throw new \InvalidArgumentException("CPF inválido: dígitos verificadores incorretos");
        }

        $this->cpf = $cpf;
    }

    /**
     * Valida os dígitos verificadores do CPF
     */
    private function validarDigitosVerificadores(string $cpf): bool
    {
        // Calcula o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int)$cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        if ($digito1 !== (int)$cpf[9]) {
            return false;
        }

        // Calcula o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int)$cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return $digito2 === (int)$cpf[10];
    }

    /**
     * Cria uma instância a partir de uma string
     */
    public static function fromString(string $cpf): self
    {
        return new self($cpf);
    }

    /**
     * Retorna o CPF formatado (XXX.XXX.XXX-XX)
     */
    public function getFormatado(): string
    {
        return sprintf(
            '%s.%s.%s-%s',
            substr($this->cpf, 0, 3),
            substr($this->cpf, 3, 3),
            substr($this->cpf, 6, 3),
            substr($this->cpf, 9, 2)
        );
    }

    /**
     * Retorna o CPF sem formatação (apenas números)
     */
    public function getSemFormatacao(): string
    {
        return $this->cpf;
    }

    /**
     * Retorna o tipo do documento
     */
    public function getTipo(): string
    {
        return 'CPF';
    }

    /**
     * Valida o CPF
     */
    public function validar(): bool
    {
        return true; // Já validado no construtor
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return [
            'cpf' => $this->cpf,
            'formatado' => $this->getFormatado(),
            'tipo' => $this->getTipo(),
        ];
    }

    /**
     * Representação em string (sem formatação)
     */
    public function __toString(): string
    {
        return $this->getSemFormatacao();
    }
}

