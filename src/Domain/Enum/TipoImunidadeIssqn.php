<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Tipo de Imunidade ISSQN
 *
 * Referência: CF88, Art. 150, VI
 *
 * @package NfseNacional\Domain\Enum
 */
enum TipoImunidadeIssqn: int
{
    case TipoNaoInformadoNotaOrigem = 0;
    case PatrimonioRendaServicosUnosDosOutros = 1;
    case TemplosQualquerCulto = 2;
    case PartidosPoliticosEntidadesSindicaisEducacaoAssistenciaSocial = 3;
    case LivrosJornaisPeriodicosPapelImpressao = 4;
    case FonogramasVideofonogramasMusicais = 5;

    /**
     * Retorna a descrição do tipo de imunidade ISSQN
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::TipoNaoInformadoNotaOrigem => 'Imunidade (tipo não informado na nota de origem)',
            self::PatrimonioRendaServicosUnosDosOutros => 'Patrimônio, renda ou serviços, uns dos outros (CF88, Art 150, VI, a)',
            self::TemplosQualquerCulto => 'Templos de qualquer culto (CF88, Art 150, VI, b)',
            self::PartidosPoliticosEntidadesSindicaisEducacaoAssistenciaSocial => 'Patrimônio, renda ou serviços dos partidos políticos, inclusive suas fundações, das entidades sindicais dos trabalhadores, das instituições de educação e de assistência social, sem fins lucrativos, atendidos os requisitos da lei (CF88, Art 150, VI, c)',
            self::LivrosJornaisPeriodicosPapelImpressao => 'Livros, jornais, periódicos e o papel destinado a sua impressão (CF88, Art 150, VI, d)',
            self::FonogramasVideofonogramasMusicais => 'Fonogramas e videofonogramas musicais produzidos no Brasil contendo obras musicais ou literomusicais de autores brasileiros e/ou obras em geral interpretadas por artistas brasileiros bem como os suportes materiais ou arquivos digitais que os contenham, salvo na etapa de replicação industrial de mídias ópticas de leitura a laser. (CF88, Art 150, VI, e)',
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
