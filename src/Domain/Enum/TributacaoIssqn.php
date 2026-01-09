<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Tributação ISSQN
 *
 * @package NfseNacional\Domain\Enum
 */
enum TributacaoIssqn: int
{
    case OperacaoTributavel = 1;
    case Imunidade = 2;
    case ExportacaoServico = 3;
    case NaoIncidencia = 4;

    /**
     * Retorna a descrição da tributação ISSQN
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::OperacaoTributavel => 'Operação tributável',
            self::Imunidade => 'Imunidade',
            self::ExportacaoServico => 'Exportação de serviço',
            self::NaoIncidencia => 'Não Incidência',
        };
    }

    /**
     * Retorna o valor do enum
     *
     * @return int
     */
    public function valor(): int
    {
        return $this->value;
    }

    /**
     * Cria uma instância do enum a partir de um valor int
     *
     * @param int $valor
     * @return self
     * @throws \ValueError
     */
    public static function fromInt(int $valor): self
    {
        return self::from($valor);
    }

    /**
     * Tenta criar uma instância do enum a partir de um valor int
     *
     * @param int $valor
     * @return self|null
     */
    public static function tryFromInt(int $valor): ?self
    {
        return self::tryFrom($valor);
    }
}

