<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Mecanismo de apoio/fomento ao Comércio Exterior utilizado pelo PRESTADOR
 *
 * Usado em `comExt/mecAFComexP`. Quem não usa nenhum mecanismo informa
 * `Nenhum` — o campo é obrigatório dentro do bloco de comércio exterior,
 * então não existe "deixar em branco".
 *
 * Valores conforme `TSMecAFComExPrest` do schema da NFS-e Nacional.
 *
 * @package NfseNacional\Domain\Enum
 */
enum MecanismoApoioComexPrestador: string
{
    case Desconhecido = '00';
    case Nenhum = '01';
    case AccAdiantamentoContratoCambio = '02';
    case AceAdiantamentoCambiaisEntregues = '03';
    case BndesEximPosEmbarque = '04';
    case BndesEximPreEmbarque = '05';
    case FgeFundoGarantiaExportacao = '06';
    case ProexEqualizacao = '07';
    case ProexFinanciamento = '08';

    /**
     * Retorna a descrição
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::Desconhecido => 'Desconhecido (tipo não informado na nota de origem)',
            self::Nenhum => 'Nenhum',
            self::AccAdiantamentoContratoCambio => 'ACC - Adiantamento sobre Contrato de Câmbio – Redução a Zero do IR e do IOF',
            self::AceAdiantamentoCambiaisEntregues => 'ACE – Adiantamento sobre Cambiais Entregues - Redução a Zero do IR e do IOF',
            self::BndesEximPosEmbarque => 'BNDES-Exim Pós-Embarque – Serviços',
            self::BndesEximPreEmbarque => 'BNDES-Exim Pré-Embarque - Serviços',
            self::FgeFundoGarantiaExportacao => 'FGE - Fundo de Garantia à Exportação',
            self::ProexEqualizacao => 'PROEX - EQUALIZAÇÃO',
            self::ProexFinanciamento => 'PROEX - Financiamento',
        };
    }

    /**
     * Retorna o valor do enum
     *
     * @return string
     */
    public function valor(): string
    {
        return $this->value;
    }

    /**
     * Cria uma instância do enum a partir de um valor string
     *
     * @param string $valor
     * @return self
     * @throws \ValueError
     */
    public static function fromString(string $valor): self
    {
        return self::from($valor);
    }

    /**
     * Tenta criar uma instância do enum a partir de um valor string
     *
     * @param string $valor
     * @return self|null
     */
    public static function tryFromString(string $valor): ?self
    {
        return self::tryFrom($valor);
    }
}
