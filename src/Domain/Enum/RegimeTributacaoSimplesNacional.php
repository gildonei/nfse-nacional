<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Regime de Tributação Simples Nacional
 *
 * @package NfseNacional\Domain\Enum
 */
enum RegimeTributacaoSimplesNacional: int
{
    case RegimeApuracaoTributosFederaisMunicipalSN = 1;
    case RegimeApuracaoTributosFederaisSNISSQNNfse = 2;
    case RegimeApuracaoTributosFederaisMunicipalNfse = 3;

    /**
     * Retorna a descrição do regime de tributação simples nacional
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::RegimeApuracaoTributosFederaisMunicipalSN => 'Regime de apuração dos tributos federais e municipal pelo SN',
            self::RegimeApuracaoTributosFederaisSNISSQNNfse => 'Regime de apuração dos tributos federais pelo SN e o ISSQN pela NFS-e conforme respectiva legislação municipal do tributo',
            self::RegimeApuracaoTributosFederaisMunicipalNfse => 'Regime de apuração dos tributos federais e municipal pela NFS-e conforme respectivas legislações federal e municipal de cada tributo',
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

