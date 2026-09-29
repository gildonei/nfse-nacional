<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Mecanismo de apoio/fomento ao Comércio Exterior utilizado pelo TOMADOR
 *
 * Usado em `comExt/mecAFComexT`. Mesma regra do prestador: obrigatório
 * dentro do bloco, e `Nenhum` é uma resposta válida.
 *
 * Valores conforme `TSMecAFComExToma` do schema da NFS-e Nacional.
 *
 * @package NfseNacional\Domain\Enum
 */
enum MecanismoApoioComexTomador: string
{
    case Desconhecido = '00';
    case Nenhum = '01';
    case AdmPublicaRepresentacaoInternacional = '02';
    case AlugueisArrendamentoMaquinasEquipamentos = '03';
    case ArrendamentoAeronaveTransporteAereo = '04';
    case ComissaoAgentesExternosExportacao = '05';
    case ArmazenagemMovimentacaoTransporteExterior = '06';
    case EventosFifaSubsidiaria = '07';
    case EventosFifa = '08';
    case FretesArrendamentosEmbarcacoes = '09';
    case MaterialAeronautico = '10';
    case PromocaoBensExterior = '11';
    case PromocaoDestinosTuristicos = '12';
    case PromocaoBrasilExterior = '13';
    case PromocaoServicosExterior = '14';
    case Recine = '15';
    case Recopa = '16';
    case RegistroManutencaoMarcasPatentes = '17';
    case Reicomp = '18';
    case Reidi = '19';
    case Repenec = '20';
    case Repes = '21';
    case Retaero = '22';
    case Retid = '23';
    case RoyaltiesAssistenciaTecnica = '24';
    case AvaliacaoConformidadeOmc = '25';
    case Zpe = '26';

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
            self::AdmPublicaRepresentacaoInternacional => 'Adm. Pública e Repr. Internacional',
            self::AlugueisArrendamentoMaquinasEquipamentos => 'Alugueis e Arrend. Mercantil de maquinas, equip., embarc. e aeronaves',
            self::ArrendamentoAeronaveTransporteAereo => 'Arrendamento Mercantil de aeronave para empresa de transporte aéreo público',
            self::ComissaoAgentesExternosExportacao => 'Comissão a agentes externos na exportação',
            self::ArmazenagemMovimentacaoTransporteExterior => 'Despesas de armazenagem, mov. e transporte de carga no exterior',
            self::EventosFifaSubsidiaria => 'Eventos FIFA (subsidiária)',
            self::EventosFifa => 'Eventos FIFA',
            self::FretesArrendamentosEmbarcacoes => 'Fretes, arrendamentos de embarcações ou aeronaves e outros',
            self::MaterialAeronautico => 'Material Aeronáutico',
            self::PromocaoBensExterior => 'Promoção de Bens no Exterior',
            self::PromocaoDestinosTuristicos => 'Promoção de Dest. Turísticos Brasileiros',
            self::PromocaoBrasilExterior => 'Promoção do Brasil no Exterior',
            self::PromocaoServicosExterior => 'Promoção Serviços no Exterior',
            self::Recine => 'RECINE',
            self::Recopa => 'RECOPA',
            self::RegistroManutencaoMarcasPatentes => 'Registro e Manutenção de marcas, patentes e cultivares',
            self::Reicomp => 'REICOMP',
            self::Reidi => 'REIDI',
            self::Repenec => 'REPENEC',
            self::Repes => 'REPES',
            self::Retaero => 'RETAERO',
            self::Retid => 'RETID',
            self::RoyaltiesAssistenciaTecnica => 'Royalties, Assistência Técnica, Científica e Assemelhados',
            self::AvaliacaoConformidadeOmc => 'Serviços de avaliação da conformidade vinculados aos Acordos da OMC',
            self::Zpe => 'ZPE',
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
