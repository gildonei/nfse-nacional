<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Regime Especial de Tributação Municipal
 *
 * @package NfseNacional\Domain\Enum
 */
enum RegimeEspecialTributacaoMunicipal: int
{
    case Nenhum = 0;
    case AtoCooperado = 1;
    case Estimativa = 2;
    case MicroempresaMunicipal = 3;
    case NotarioOuRegistrador = 4;
    case ProfissionalAutonomo = 5;
    case SociedadeDeProfissionais = 6;

    /**
     * Retorna a descrição do regime especial de tributação municipal
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::Nenhum => 'Nenhum',
            self::AtoCooperado => 'Ato Cooperado (Cooperativa)',
            self::Estimativa => 'Estimativa',
            self::MicroempresaMunicipal => 'Microempresa Municipal',
            self::NotarioOuRegistrador => 'Notário ou Registrador',
            self::ProfissionalAutonomo => 'Profissional Autônomo',
            self::SociedadeDeProfissionais => 'Sociedade de Profissionais',
        };
    }

    /**
     * Retorna o valor numérico do enum
     *
     * @return int
     */
    public function valor(): int
    {
        return $this->value;
    }

    /**
     * Cria uma instância do enum a partir de um valor inteiro
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
     * Tenta criar uma instância do enum a partir de um valor inteiro
     *
     * @param int $valor
     * @return self|null
     */
    public static function tryFromInt(int $valor): ?self
    {
        return self::tryFrom($valor);
    }
}

