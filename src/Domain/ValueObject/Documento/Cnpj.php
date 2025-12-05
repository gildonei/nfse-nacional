<?php

declare(strict_types=1);

namespace NfseNacional\Domain\ValueObject\Documento;

/**
 * Value Object para CNPJ com validação
 */
final class Cnpj implements DocumentoInterface
{
    private string $cnpj;

    public function __construct(string $cnpj)
    {
        $this->setCnpj($cnpj);
    }

    /**
     * Define e valida o CNPJ
     */
    private function setCnpj(string $cnpj): void
    {
        // Remove caracteres não numéricos
        $cnpj = preg_replace('/\D/', '', $cnpj);

        // Valida comprimento
        if (strlen($cnpj) !== 14) {
            throw new \InvalidArgumentException("CNPJ deve conter exatamente 14 dígitos");
        }

        // Valida se não é uma sequência inválida
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            throw new \InvalidArgumentException("CNPJ inválido: sequência repetida");
        }

        // Valida dígitos verificadores
        if (!$this->validarDigitosVerificadores($cnpj)) {
            throw new \InvalidArgumentException("CNPJ inválido: dígitos verificadores incorretos");
        }

        $this->cnpj = $cnpj;
    }

    /**
     * Valida os dígitos verificadores do CNPJ
     */
    private function validarDigitosVerificadores(string $cnpj): bool
    {
        // Calcula o primeiro dígito verificador
        $pesos = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma += (int)$cnpj[$i] * $pesos[$i];
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        if ($digito1 !== (int)$cnpj[12]) {
            return false;
        }

        // Calcula o segundo dígito verificador
        $pesos = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma += (int)$cnpj[$i] * $pesos[$i];
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return $digito2 === (int)$cnpj[13];
    }

    /**
     * Cria uma instância a partir de uma string
     */
    public static function fromString(string $cnpj): self
    {
        return new self($cnpj);
    }

    /**
     * Retorna o CNPJ formatado (XX.XXX.XXX/XXXX-XX)
     */
    public function getFormatado(): string
    {
        return sprintf(
            '%s.%s.%s/%s-%s',
            substr($this->cnpj, 0, 2),
            substr($this->cnpj, 2, 3),
            substr($this->cnpj, 5, 3),
            substr($this->cnpj, 8, 4),
            substr($this->cnpj, 12, 2)
        );
    }

    /**
     * Retorna o CNPJ sem formatação (apenas números)
     */
    public function getSemFormatacao(): string
    {
        return $this->cnpj;
    }

    /**
     * Retorna o tipo do documento
     */
    public function getTipo(): string
    {
        return 'CNPJ';
    }

    /**
     * Valida o CNPJ
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
            'cnpj' => $this->cnpj,
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

