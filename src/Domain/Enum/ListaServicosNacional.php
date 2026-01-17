<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Lista de Serviços Nacional
 *
 * Lista de serviços da Lei Complementar 116/2003 com os códigos de tributação nacional (cTribNac)
 *
 * Código de 6 dígitos no formato: IISSDD
 * - II = Item (01-40) - Corresponde ao grupo de serviços da LC 116/2003
 * - SS = Subitem (01-99) - Subdivisão do item
 * - DD = Desdobro (01-99) - Detalhamento do subitem
 *
 * Quando o código de tributação nacional (cTribNac) estiver vazio na tabela oficial,
 * utiliza-se a concatenação dos campos Item + Subitem + Desdobro.
 *
 * @package NfseNacional\Domain\Enum
 */
enum ListaServicosNacional: string
{
    // 01 - Serviços de informática e congêneres
    case S010101 = '010101'; // Análise e desenvolvimento de sistemas
    case S010102 = '010102'; // Programação
    case S010103 = '010103'; // Processamento de dados e congêneres
    case S010104 = '010104'; // Elaboração de programas de computadores, inclusive de jogos eletrônicos
    case S010105 = '010105'; // Licenciamento ou cessão de direito de uso de programas de computação
    case S010106 = '010106'; // Assessoria e consultoria em informática
    case S010107 = '010107'; // Suporte técnico em informática, inclusive instalação, configuração e manutenção
    case S010108 = '010108'; // Planejamento, confecção, manutenção e atualização de páginas eletrônicas
    case S010109 = '010109'; // Disponibilização, sem cessão definitiva, de conteúdos de áudio, vídeo, imagem e texto

    // 02 - Serviços de pesquisas e desenvolvimento de qualquer natureza
    case S020101 = '020101'; // Serviços de pesquisas e desenvolvimento de qualquer natureza

    // 03 - Serviços prestados mediante locação, cessão de direito de uso e congêneres
    case S030201 = '030201'; // Cessão de direito de uso de marcas e de sinais de propaganda
    case S030301 = '030301'; // Exploração de salões de festas, centro de convenções, escritórios virtuais
    case S030401 = '030401'; // Locação, sublocação, arrendamento, direito de passagem ou permissão de uso
    case S030501 = '030501'; // Cessão de andaimes, palcos, coberturas e outras estruturas de uso temporário

    // 04 - Serviços de saúde, assistência médica e congêneres
    case S040101 = '040101'; // Medicina e biomedicina
    case S040102 = '040102'; // Análises clínicas, patologia, eletricidade médica, radioterapia
    case S040103 = '040103'; // Hospitais, clínicas, laboratórios, sanatórios, manicômios
    case S040104 = '040104'; // Instrumentação cirúrgica
    case S040105 = '040105'; // Acupuntura
    case S040106 = '040106'; // Enfermagem, inclusive serviços auxiliares
    case S040107 = '040107'; // Serviços farmacêuticos
    case S040108 = '040108'; // Terapia ocupacional, fisioterapia e fonoaudiologia
    case S040109 = '040109'; // Terapias de qualquer espécie destinadas ao tratamento físico, orgânico e mental
    case S040110 = '040110'; // Nutrição
    case S040111 = '040111'; // Obstetrícia
    case S040112 = '040112'; // Odontologia
    case S040113 = '040113'; // Ortóptica
    case S040114 = '040114'; // Próteses sob encomenda
    case S040115 = '040115'; // Psicanálise
    case S040116 = '040116'; // Psicologia
    case S040117 = '040117'; // Casas de repouso e de recuperação, creches, asilos e congêneres
    case S040118 = '040118'; // Inseminação artificial, fertilização in vitro e congêneres
    case S040119 = '040119'; // Bancos de sangue, leite, pele, olhos, óvulos, sêmen e congêneres
    case S040120 = '040120'; // Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos
    case S040121 = '040121'; // Unidade de atendimento, assistência ou tratamento móvel e congêneres
    case S040122 = '040122'; // Planos de medicina de grupo ou individual e convênios
    case S040123 = '040123'; // Outros planos de saúde

    // 05 - Serviços de medicina e assistência veterinária e congêneres
    case S050101 = '050101'; // Medicina veterinária e zootecnia
    case S050201 = '050201'; // Hospitais, clínicas, ambulatórios, prontos-socorros e congêneres, na área veterinária
    case S050301 = '050301'; // Laboratórios de análise na área veterinária
    case S050401 = '050401'; // Inseminação artificial, fertilização in vitro e congêneres
    case S050501 = '050501'; // Bancos de sangue e de órgãos e congêneres
    case S050601 = '050601'; // Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos
    case S050701 = '050701'; // Unidade de atendimento, assistência ou tratamento móvel e congêneres
    case S050801 = '050801'; // Guarda, tratamento, amestramento, embelezamento, alojamento e congêneres
    case S050901 = '050901'; // Planos de atendimento e assistência médico-veterinária

    // 06 - Serviços de cuidados pessoais, estética, atividades físicas e congêneres
    case S060101 = '060101'; // Barbearia, cabeleireiros, manicuros, pedicuros e congêneres
    case S060201 = '060201'; // Esteticistas, tratamento de pele, depilação e congêneres
    case S060301 = '060301'; // Banhos, duchas, sauna, massagens e congêneres
    case S060401 = '060401'; // Ginástica, dança, esportes, natação, artes marciais e demais atividades físicas
    case S060501 = '060501'; // Centros de emagrecimento, spa e congêneres
    case S060601 = '060601'; // Aplicação de tatuagens, piercings e congêneres

    // 07 - Serviços relativos a engenharia, arquitetura, geologia, urbanismo
    case S070101 = '070101'; // Engenharia, agronomia, agrimensura, arquitetura, geologia, urbanismo, paisagismo
    case S070201 = '070201'; // Execução, por administração, empreitada ou subempreitada, de obras
    case S070501 = '070501'; // Reparação, conservação e reforma de edifícios, estradas, pontes, portos
    case S070601 = '070601'; // Colocação e instalação de tapetes, carpetes, assoalhos
    case S070701 = '070701'; // Recuperação, raspagem, polimento e lustração de pisos e congêneres
    case S070801 = '070801'; // Calafetação
    case S070901 = '070901'; // Varrição, coleta, remoção, incineração, tratamento, reciclagem de lixo
    case S071001 = '071001'; // Limpeza, manutenção e conservação de vias e logradouros públicos
    case S071101 = '071101'; // Decoração e jardinagem, inclusive corte e poda de árvores
    case S071201 = '071201'; // Controle e tratamento de efluentes de qualquer natureza
    case S071301 = '071301'; // Dedetização, desinfecção, desinsetização, imunização
    case S071601 = '071601'; // Florestamento, reflorestamento, semeadura, adubação, reparação de solo
    case S071701 = '071701'; // Escoramento, contenção de encostas e serviços congêneres
    case S071801 = '071801'; // Limpeza e dragagem de rios, portos, canais, baías, lagos, lagoas
    case S071901 = '071901'; // Acompanhamento e fiscalização da execução de obras
    case S072001 = '072001'; // Aerofotogrametria
    case S072101 = '072101'; // Pesquisa, perfuração, cimentação, mergulho, perfilagem
    case S072201 = '072201'; // Nucleação e bombardeamento de nuvens e congêneres

    // 08 - Serviços de educação, ensino, orientação pedagógica e educacional
    case S080101 = '080101'; // Ensino regular pré-escolar, fundamental, médio e superior
    case S080201 = '080201'; // Instrução, treinamento, orientação pedagógica e educacional

    // 09 - Serviços relativos a hospedagem, turismo, viagens e congêneres
    case S090101 = '090101'; // Hospedagem de qualquer natureza em hotéis, apart-service condominiais
    case S090201 = '090201'; // Agenciamento, organização, promoção, intermediação e execução de programas de turismo
    case S090301 = '090301'; // Guias de turismo

    // 10 - Serviços de intermediação e congêneres
    case S100101 = '100101'; // Agenciamento, corretagem ou intermediação de câmbio, de seguros
    case S100201 = '100201'; // Agenciamento, corretagem ou intermediação de títulos em geral
    case S100301 = '100301'; // Agenciamento, corretagem ou intermediação de direitos de propriedade industrial
    case S100401 = '100401'; // Agenciamento, corretagem ou intermediação de contratos de arrendamento mercantil
    case S100501 = '100501'; // Agenciamento, corretagem ou intermediação de bens móveis ou imóveis
    case S100601 = '100601'; // Agenciamento marítimo
    case S100701 = '100701'; // Agenciamento de notícias
    case S100801 = '100801'; // Agenciamento de publicidade e propaganda
    case S100901 = '100901'; // Representação de qualquer natureza, inclusive comercial
    case S101001 = '101001'; // Distribuição de bens de terceiros

    // 11 - Serviços de guarda, estacionamento, armazenamento, vigilância e congêneres
    case S110101 = '110101'; // Guarda e estacionamento de veículos terrestres automotores
    case S110201 = '110201'; // Vigilância, segurança ou monitoramento de bens, pessoas e semoventes
    case S110301 = '110301'; // Escolta, inclusive de veículos e cargas
    case S110401 = '110401'; // Armazenamento, depósito, carga, descarga, arrumação e guarda de bens

    // 12 - Serviços de diversões, lazer, entretenimento e congêneres
    case S120101 = '120101'; // Espetáculos teatrais
    case S120201 = '120201'; // Exibições cinematográficas
    case S120301 = '120301'; // Espetáculos circenses
    case S120401 = '120401'; // Programas de auditório
    case S120501 = '120501'; // Parques de diversões, centros de lazer e congêneres
    case S120601 = '120601'; // Boates, taxi-dancing e congêneres
    case S120701 = '120701'; // Shows, ballet, danças, desfiles, bailes, óperas, concertos, recitais
    case S120801 = '120801'; // Feiras, exposições, congressos e congêneres
    case S120901 = '120901'; // Bilhares, boliches e diversões eletrônicas ou não
    case S121001 = '121001'; // Corridas e competições de animais
    case S121101 = '121101'; // Competições esportivas ou de destreza física ou intelectual
    case S121201 = '121201'; // Execução de música
    case S121301 = '121301'; // Produção, mediante ou sem encomenda prévia, de eventos, espetáculos
    case S121401 = '121401'; // Fornecimento de música para ambientes fechados ou não
    case S121501 = '121501'; // Desfiles de blocos carnavalescos ou folclóricos, trios elétricos
    case S121601 = '121601'; // Exibição de filmes, entrevistas, musicais, espetáculos, shows
    case S121701 = '121701'; // Recreação e animação, inclusive em festas e eventos de qualquer natureza

    // 13 - Serviços relativos a fonografia, fotografia, cinematografia e reprografia
    case S130201 = '130201'; // Fonografia ou gravação de sons, inclusive trucagem, dublagem
    case S130301 = '130301'; // Fotografia e cinematografia, inclusive revelação, ampliação
    case S130401 = '130401'; // Reprografia, microfilmagem e digitalização
    case S130501 = '130501'; // Composição gráfica, inclusive confecção de impressos gráficos

    // 14 - Serviços relativos a bens de terceiros
    case S140101 = '140101'; // Lubrificação, limpeza, lustração, revisão, carga e recarga
    case S140201 = '140201'; // Assistência técnica
    case S140301 = '140301'; // Recondicionamento de motores
    case S140401 = '140401'; // Recauchutagem ou regeneração de pneus
    case S140501 = '140501'; // Restauração, recondicionamento, acondicionamento, pintura
    case S140601 = '140601'; // Instalação e montagem de aparelhos, máquinas e equipamentos
    case S140701 = '140701'; // Colocação de molduras e congêneres
    case S140801 = '140801'; // Encadernação, gravação e douração de livros, revistas e congêneres
    case S140901 = '140901'; // Alfaiataria e costura
    case S141001 = '141001'; // Tinturaria e lavanderia
    case S141101 = '141101'; // Tapeçaria e reforma de estofamentos em geral
    case S141201 = '141201'; // Funilaria e lanternagem
    case S141301 = '141301'; // Carpintaria e serralheria
    case S141401 = '141401'; // Guincho intramunicipal, guindaste e içamento

    // 15 - Serviços relacionados ao setor bancário ou financeiro
    case S150101 = '150101'; // Administração de fundos, consórcios, cartão de crédito
    case S150201 = '150201'; // Abertura de contas em geral
    case S150301 = '150301'; // Locação e manutenção de cofres particulares
    case S150401 = '150401'; // Fornecimento ou emissão de atestados em geral
    case S150501 = '150501'; // Cadastro, elaboração de ficha cadastral, renovação cadastral
    case S150601 = '150601'; // Emissão, reemissão e fornecimento de avisos, comprovantes e documentos
    case S150701 = '150701'; // Acesso, movimentação, atendimento e consulta a contas em geral
    case S150801 = '150801'; // Emissão, reemissão, alteração, cessão, substituição, cancelamento
    case S150901 = '150901'; // Compensação de cheques e títulos quaisquer
    case S151001 = '151001'; // Custódia em geral, inclusive de títulos e valores mobiliários
    case S151101 = '151101'; // Serviços de liquidação e custódia de títulos
    case S151201 = '151201'; // Serviços relacionados a crédito imobiliário
    case S151301 = '151301'; // Cessões de direito de crédito
    case S151401 = '151401'; // Serviços de cobrança, recebimento ou pagamento
    case S151501 = '151501'; // Serviços de operação de câmbio em geral
    case S151601 = '151601'; // Fornecimento, emissão, reemissão, renovação e manutenção de cartão magnético
    case S151701 = '151701'; // Compensação de cheques e outros títulos
    case S151801 = '151801'; // Serviços relacionados a depósito, inclusive depósito identificado

    // 16 - Serviços de transporte de natureza municipal
    case S160101 = '160101'; // Serviços de transporte coletivo municipal rodoviário, metroviário
    case S160201 = '160201'; // Outros serviços de transporte de natureza municipal

    // 17 - Serviços de apoio técnico, administrativo, jurídico, contábil, comercial e congêneres
    case S170101 = '170101'; // Assessoria ou consultoria de qualquer natureza
    case S170201 = '170201'; // Análise, exame, pesquisa, coleta, compilação e fornecimento de dados
    case S170301 = '170301'; // Planejamento, coordenação, programação ou organização técnica
    case S170401 = '170401'; // Recrutamento, agenciamento, seleção e colocação de mão-de-obra
    case S170501 = '170501'; // Fornecimento de mão-de-obra, mesmo em caráter temporário
    case S170601 = '170601'; // Propaganda e publicidade, inclusive promoção de vendas
    case S170801 = '170801'; // Franquia (franchising)
    case S170901 = '170901'; // Perícias, laudos, exames técnicos e análises técnicas
    case S171001 = '171001'; // Planejamento, organização e administração de feiras, exposições
    case S171101 = '171101'; // Organização de festas e recepções; bufê
    case S171201 = '171201'; // Administração em geral, inclusive de bens e negócios de terceiros
    case S171301 = '171301'; // Leilão e congêneres
    case S171401 = '171401'; // Advocacia
    case S171501 = '171501'; // Arbitragem de qualquer espécie, inclusive jurídica
    case S171601 = '171601'; // Auditoria
    case S171701 = '171701'; // Análise de Organização e Métodos
    case S171801 = '171801'; // Atuária e cálculos técnicos de qualquer natureza
    case S171901 = '171901'; // Contabilidade, inclusive serviços técnicos e auxiliares
    case S172001 = '172001'; // Consultoria e assessoria econômica ou financeira
    case S172101 = '172101'; // Estatística
    case S172201 = '172201'; // Cobrança em geral
    case S172301 = '172301'; // Assessoria, análise, avaliação, atendimento, consulta
    case S172401 = '172401'; // Apresentação de palestras, conferências, seminários e congêneres
    case S172501 = '172501'; // Inserção de textos, desenhos e outros materiais de propaganda

    // 18 - Serviços de regulação de sinistros vinculados a contratos de seguros
    case S180101 = '180101'; // Serviços de regulação de sinistros vinculados a contratos de seguros

    // 19 - Serviços de distribuição e venda de bilhetes e demais produtos de loteria
    case S190101 = '190101'; // Serviços de distribuição e venda de bilhetes e demais produtos de loteria

    // 20 - Serviços portuários, aeroportuários, ferroportuários, de terminais rodoviários
    case S200101 = '200101'; // Serviços portuários, ferroportuários, utilização de porto
    case S200201 = '200201'; // Serviços aeroportuários, utilização de aeroporto, movimentação de passageiros
    case S200301 = '200301'; // Serviços de terminais rodoviários, ferroviários, metroviários

    // 21 - Serviços de registros públicos, cartorários e notariais
    case S210101 = '210101'; // Serviços de registros públicos, cartorários e notariais

    // 22 - Serviços de exploração de rodovia
    case S220101 = '220101'; // Serviços de exploração de rodovia

    // 23 - Serviços de programação e comunicação visual, desenho industrial
    case S230101 = '230101'; // Serviços de programação e comunicação visual, desenho industrial

    // 24 - Serviços de chaveiros, confecção de carimbos, placas, sinalização visual
    case S240101 = '240101'; // Serviços de chaveiros, confecção de carimbos, placas, sinalização visual

    // 25 - Serviços funerários
    case S250101 = '250101'; // Funerais, inclusive fornecimento de caixão, urna ou esquife
    case S250201 = '250201'; // Translado intramunicipal e cremação de corpos e partes de corpos cadavéricos
    case S250301 = '250301'; // Planos ou convênio funerários
    case S250401 = '250401'; // Manutenção e conservação de jazigos e cemitérios
    case S250501 = '250501'; // Cessão de uso de espaços em cemitérios para sepultamento

    // 26 - Serviços de coleta, remessa ou entrega de correspondências
    case S260101 = '260101'; // Serviços de coleta, remessa ou entrega de correspondências

    // 27 - Serviços de assistência social
    case S270101 = '270101'; // Serviços de assistência social

    // 28 - Serviços de avaliação de bens e serviços de qualquer natureza
    case S280101 = '280101'; // Serviços de avaliação de bens e serviços de qualquer natureza

    // 29 - Serviços de biblioteconomia
    case S290101 = '290101'; // Serviços de biblioteconomia

    // 30 - Serviços de biologia, biotecnologia e química
    case S300101 = '300101'; // Serviços de biologia, biotecnologia e química

    // 31 - Serviços técnicos em edificações, eletrônica, eletrotécnica
    case S310101 = '310101'; // Serviços técnicos em edificações, eletrônica, eletrotécnica

    // 32 - Serviços de desenhos técnicos
    case S320101 = '320101'; // Serviços de desenhos técnicos

    // 33 - Serviços de desembaraço aduaneiro, comissários, despachantes e congêneres
    case S330101 = '330101'; // Serviços de desembaraço aduaneiro, comissários, despachantes e congêneres

    // 34 - Serviços de investigações particulares, detetives e congêneres
    case S340101 = '340101'; // Serviços de investigações particulares, detetives e congêneres

    // 35 - Serviços de reportagem, assessoria de imprensa, jornalismo e relações públicas
    case S350101 = '350101'; // Serviços de reportagem, assessoria de imprensa, jornalismo e relações públicas

    // 36 - Serviços de meteorologia
    case S360101 = '360101'; // Serviços de meteorologia

    // 37 - Serviços de artistas, atletas, modelos e manequins
    case S370101 = '370101'; // Serviços de artistas, atletas, modelos e manequins

    // 38 - Serviços de museologia
    case S380101 = '380101'; // Serviços de museologia

    // 39 - Serviços de ourivesaria e lapidação
    case S390101 = '390101'; // Serviços de ourivesaria e lapidação

    // 40 - Serviços relativos a obras de arte sob encomenda
    case S400101 = '400101'; // Obras de arte sob encomenda

    /**
     * Retorna a descrição do serviço
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            // 01 - Serviços de informática e congêneres
            self::S010101 => 'Análise e desenvolvimento de sistemas',
            self::S010102 => 'Programação',
            self::S010103 => 'Processamento de dados e congêneres',
            self::S010104 => 'Elaboração de programas de computadores, inclusive de jogos eletrônicos',
            self::S010105 => 'Licenciamento ou cessão de direito de uso de programas de computação',
            self::S010106 => 'Assessoria e consultoria em informática',
            self::S010107 => 'Suporte técnico em informática, inclusive instalação, configuração e manutenção',
            self::S010108 => 'Planejamento, confecção, manutenção e atualização de páginas eletrônicas',
            self::S010109 => 'Disponibilização, sem cessão definitiva, de conteúdos de áudio, vídeo, imagem e texto',

            // 02 - Serviços de pesquisas e desenvolvimento
            self::S020101 => 'Serviços de pesquisas e desenvolvimento de qualquer natureza',

            // 03 - Serviços prestados mediante locação, cessão de direito de uso
            self::S030201 => 'Cessão de direito de uso de marcas e de sinais de propaganda',
            self::S030301 => 'Exploração de salões de festas, centro de convenções, escritórios virtuais',
            self::S030401 => 'Locação, sublocação, arrendamento, direito de passagem ou permissão de uso',
            self::S030501 => 'Cessão de andaimes, palcos, coberturas e outras estruturas de uso temporário',

            // 04 - Serviços de saúde, assistência médica
            self::S040101 => 'Medicina e biomedicina',
            self::S040102 => 'Análises clínicas, patologia, eletricidade médica, radioterapia',
            self::S040103 => 'Hospitais, clínicas, laboratórios, sanatórios, manicômios',
            self::S040104 => 'Instrumentação cirúrgica',
            self::S040105 => 'Acupuntura',
            self::S040106 => 'Enfermagem, inclusive serviços auxiliares',
            self::S040107 => 'Serviços farmacêuticos',
            self::S040108 => 'Terapia ocupacional, fisioterapia e fonoaudiologia',
            self::S040109 => 'Terapias de qualquer espécie destinadas ao tratamento físico, orgânico e mental',
            self::S040110 => 'Nutrição',
            self::S040111 => 'Obstetrícia',
            self::S040112 => 'Odontologia',
            self::S040113 => 'Ortóptica',
            self::S040114 => 'Próteses sob encomenda',
            self::S040115 => 'Psicanálise',
            self::S040116 => 'Psicologia',
            self::S040117 => 'Casas de repouso e de recuperação, creches, asilos e congêneres',
            self::S040118 => 'Inseminação artificial, fertilização in vitro e congêneres',
            self::S040119 => 'Bancos de sangue, leite, pele, olhos, óvulos, sêmen e congêneres',
            self::S040120 => 'Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos',
            self::S040121 => 'Unidade de atendimento, assistência ou tratamento móvel e congêneres',
            self::S040122 => 'Planos de medicina de grupo ou individual e convênios',
            self::S040123 => 'Outros planos de saúde',

            // 05 - Serviços de medicina e assistência veterinária
            self::S050101 => 'Medicina veterinária e zootecnia',
            self::S050201 => 'Hospitais, clínicas, ambulatórios, prontos-socorros e congêneres, na área veterinária',
            self::S050301 => 'Laboratórios de análise na área veterinária',
            self::S050401 => 'Inseminação artificial, fertilização in vitro e congêneres',
            self::S050501 => 'Bancos de sangue e de órgãos e congêneres',
            self::S050601 => 'Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos',
            self::S050701 => 'Unidade de atendimento, assistência ou tratamento móvel e congêneres',
            self::S050801 => 'Guarda, tratamento, amestramento, embelezamento, alojamento e congêneres',
            self::S050901 => 'Planos de atendimento e assistência médico-veterinária',

            // 06 - Serviços de cuidados pessoais, estética, atividades físicas
            self::S060101 => 'Barbearia, cabeleireiros, manicuros, pedicuros e congêneres',
            self::S060201 => 'Esteticistas, tratamento de pele, depilação e congêneres',
            self::S060301 => 'Banhos, duchas, sauna, massagens e congêneres',
            self::S060401 => 'Ginástica, dança, esportes, natação, artes marciais e demais atividades físicas',
            self::S060501 => 'Centros de emagrecimento, spa e congêneres',
            self::S060601 => 'Aplicação de tatuagens, piercings e congêneres',

            // 07 - Serviços relativos a engenharia, arquitetura, geologia
            self::S070101 => 'Engenharia, agronomia, agrimensura, arquitetura, geologia, urbanismo, paisagismo',
            self::S070201 => 'Execução, por administração, empreitada ou subempreitada, de obras',
            self::S070501 => 'Reparação, conservação e reforma de edifícios, estradas, pontes, portos',
            self::S070601 => 'Colocação e instalação de tapetes, carpetes, assoalhos',
            self::S070701 => 'Recuperação, raspagem, polimento e lustração de pisos e congêneres',
            self::S070801 => 'Calafetação',
            self::S070901 => 'Varrição, coleta, remoção, incineração, tratamento, reciclagem de lixo',
            self::S071001 => 'Limpeza, manutenção e conservação de vias e logradouros públicos',
            self::S071101 => 'Decoração e jardinagem, inclusive corte e poda de árvores',
            self::S071201 => 'Controle e tratamento de efluentes de qualquer natureza',
            self::S071301 => 'Dedetização, desinfecção, desinsetização, imunização',
            self::S071601 => 'Florestamento, reflorestamento, semeadura, adubação, reparação de solo',
            self::S071701 => 'Escoramento, contenção de encostas e serviços congêneres',
            self::S071801 => 'Limpeza e dragagem de rios, portos, canais, baías, lagos, lagoas',
            self::S071901 => 'Acompanhamento e fiscalização da execução de obras',
            self::S072001 => 'Aerofotogrametria',
            self::S072101 => 'Pesquisa, perfuração, cimentação, mergulho, perfilagem',
            self::S072201 => 'Nucleação e bombardeamento de nuvens e congêneres',

            // 08 - Serviços de educação, ensino
            self::S080101 => 'Ensino regular pré-escolar, fundamental, médio e superior',
            self::S080201 => 'Instrução, treinamento, orientação pedagógica e educacional',

            // 09 - Serviços relativos a hospedagem, turismo, viagens
            self::S090101 => 'Hospedagem de qualquer natureza em hotéis, apart-service condominiais',
            self::S090201 => 'Agenciamento, organização, promoção, intermediação e execução de programas de turismo',
            self::S090301 => 'Guias de turismo',

            // 10 - Serviços de intermediação
            self::S100101 => 'Agenciamento, corretagem ou intermediação de câmbio, de seguros',
            self::S100201 => 'Agenciamento, corretagem ou intermediação de títulos em geral',
            self::S100301 => 'Agenciamento, corretagem ou intermediação de direitos de propriedade industrial',
            self::S100401 => 'Agenciamento, corretagem ou intermediação de contratos de arrendamento mercantil',
            self::S100501 => 'Agenciamento, corretagem ou intermediação de bens móveis ou imóveis',
            self::S100601 => 'Agenciamento marítimo',
            self::S100701 => 'Agenciamento de notícias',
            self::S100801 => 'Agenciamento de publicidade e propaganda',
            self::S100901 => 'Representação de qualquer natureza, inclusive comercial',
            self::S101001 => 'Distribuição de bens de terceiros',

            // 11 - Serviços de guarda, estacionamento, armazenamento, vigilância
            self::S110101 => 'Guarda e estacionamento de veículos terrestres automotores',
            self::S110201 => 'Vigilância, segurança ou monitoramento de bens, pessoas e semoventes',
            self::S110301 => 'Escolta, inclusive de veículos e cargas',
            self::S110401 => 'Armazenamento, depósito, carga, descarga, arrumação e guarda de bens',

            // 12 - Serviços de diversões, lazer, entretenimento
            self::S120101 => 'Espetáculos teatrais',
            self::S120201 => 'Exibições cinematográficas',
            self::S120301 => 'Espetáculos circenses',
            self::S120401 => 'Programas de auditório',
            self::S120501 => 'Parques de diversões, centros de lazer e congêneres',
            self::S120601 => 'Boates, taxi-dancing e congêneres',
            self::S120701 => 'Shows, ballet, danças, desfiles, bailes, óperas, concertos, recitais',
            self::S120801 => 'Feiras, exposições, congressos e congêneres',
            self::S120901 => 'Bilhares, boliches e diversões eletrônicas ou não',
            self::S121001 => 'Corridas e competições de animais',
            self::S121101 => 'Competições esportivas ou de destreza física ou intelectual',
            self::S121201 => 'Execução de música',
            self::S121301 => 'Produção, mediante ou sem encomenda prévia, de eventos, espetáculos',
            self::S121401 => 'Fornecimento de música para ambientes fechados ou não',
            self::S121501 => 'Desfiles de blocos carnavalescos ou folclóricos, trios elétricos',
            self::S121601 => 'Exibição de filmes, entrevistas, musicais, espetáculos, shows',
            self::S121701 => 'Recreação e animação, inclusive em festas e eventos de qualquer natureza',

            // 13 - Serviços relativos a fonografia, fotografia, cinematografia
            self::S130201 => 'Fonografia ou gravação de sons, inclusive trucagem, dublagem',
            self::S130301 => 'Fotografia e cinematografia, inclusive revelação, ampliação',
            self::S130401 => 'Reprografia, microfilmagem e digitalização',
            self::S130501 => 'Composição gráfica, inclusive confecção de impressos gráficos',

            // 14 - Serviços relativos a bens de terceiros
            self::S140101 => 'Lubrificação, limpeza, lustração, revisão, carga e recarga',
            self::S140201 => 'Assistência técnica',
            self::S140301 => 'Recondicionamento de motores',
            self::S140401 => 'Recauchutagem ou regeneração de pneus',
            self::S140501 => 'Restauração, recondicionamento, acondicionamento, pintura',
            self::S140601 => 'Instalação e montagem de aparelhos, máquinas e equipamentos',
            self::S140701 => 'Colocação de molduras e congêneres',
            self::S140801 => 'Encadernação, gravação e douração de livros, revistas e congêneres',
            self::S140901 => 'Alfaiataria e costura',
            self::S141001 => 'Tinturaria e lavanderia',
            self::S141101 => 'Tapeçaria e reforma de estofamentos em geral',
            self::S141201 => 'Funilaria e lanternagem',
            self::S141301 => 'Carpintaria e serralheria',
            self::S141401 => 'Guincho intramunicipal, guindaste e içamento',

            // 15 - Serviços relacionados ao setor bancário ou financeiro
            self::S150101 => 'Administração de fundos, consórcios, cartão de crédito',
            self::S150201 => 'Abertura de contas em geral',
            self::S150301 => 'Locação e manutenção de cofres particulares',
            self::S150401 => 'Fornecimento ou emissão de atestados em geral',
            self::S150501 => 'Cadastro, elaboração de ficha cadastral, renovação cadastral',
            self::S150601 => 'Emissão, reemissão e fornecimento de avisos, comprovantes e documentos',
            self::S150701 => 'Acesso, movimentação, atendimento e consulta a contas em geral',
            self::S150801 => 'Emissão, reemissão, alteração, cessão, substituição, cancelamento',
            self::S150901 => 'Compensação de cheques e títulos quaisquer',
            self::S151001 => 'Custódia em geral, inclusive de títulos e valores mobiliários',
            self::S151101 => 'Serviços de liquidação e custódia de títulos',
            self::S151201 => 'Serviços relacionados a crédito imobiliário',
            self::S151301 => 'Cessões de direito de crédito',
            self::S151401 => 'Serviços de cobrança, recebimento ou pagamento',
            self::S151501 => 'Serviços de operação de câmbio em geral',
            self::S151601 => 'Fornecimento, emissão, reemissão, renovação e manutenção de cartão magnético',
            self::S151701 => 'Compensação de cheques e outros títulos',
            self::S151801 => 'Serviços relacionados a depósito, inclusive depósito identificado',

            // 16 - Serviços de transporte de natureza municipal
            self::S160101 => 'Serviços de transporte coletivo municipal rodoviário, metroviário',
            self::S160201 => 'Outros serviços de transporte de natureza municipal',

            // 17 - Serviços de apoio técnico, administrativo, jurídico, contábil
            self::S170101 => 'Assessoria ou consultoria de qualquer natureza',
            self::S170201 => 'Análise, exame, pesquisa, coleta, compilação e fornecimento de dados',
            self::S170301 => 'Planejamento, coordenação, programação ou organização técnica',
            self::S170401 => 'Recrutamento, agenciamento, seleção e colocação de mão-de-obra',
            self::S170501 => 'Fornecimento de mão-de-obra, mesmo em caráter temporário',
            self::S170601 => 'Propaganda e publicidade, inclusive promoção de vendas',
            self::S170801 => 'Franquia (franchising)',
            self::S170901 => 'Perícias, laudos, exames técnicos e análises técnicas',
            self::S171001 => 'Planejamento, organização e administração de feiras, exposições',
            self::S171101 => 'Organização de festas e recepções; bufê',
            self::S171201 => 'Administração em geral, inclusive de bens e negócios de terceiros',
            self::S171301 => 'Leilão e congêneres',
            self::S171401 => 'Advocacia',
            self::S171501 => 'Arbitragem de qualquer espécie, inclusive jurídica',
            self::S171601 => 'Auditoria',
            self::S171701 => 'Análise de Organização e Métodos',
            self::S171801 => 'Atuária e cálculos técnicos de qualquer natureza',
            self::S171901 => 'Contabilidade, inclusive serviços técnicos e auxiliares',
            self::S172001 => 'Consultoria e assessoria econômica ou financeira',
            self::S172101 => 'Estatística',
            self::S172201 => 'Cobrança em geral',
            self::S172301 => 'Assessoria, análise, avaliação, atendimento, consulta',
            self::S172401 => 'Apresentação de palestras, conferências, seminários e congêneres',
            self::S172501 => 'Inserção de textos, desenhos e outros materiais de propaganda',

            // 18 - Serviços de regulação de sinistros
            self::S180101 => 'Serviços de regulação de sinistros vinculados a contratos de seguros',

            // 19 - Serviços de distribuição e venda de bilhetes de loteria
            self::S190101 => 'Serviços de distribuição e venda de bilhetes e demais produtos de loteria',

            // 20 - Serviços portuários, aeroportuários
            self::S200101 => 'Serviços portuários, ferroportuários, utilização de porto',
            self::S200201 => 'Serviços aeroportuários, utilização de aeroporto, movimentação de passageiros',
            self::S200301 => 'Serviços de terminais rodoviários, ferroviários, metroviários',

            // 21 - Serviços de registros públicos, cartorários e notariais
            self::S210101 => 'Serviços de registros públicos, cartorários e notariais',

            // 22 - Serviços de exploração de rodovia
            self::S220101 => 'Serviços de exploração de rodovia',

            // 23 - Serviços de programação e comunicação visual
            self::S230101 => 'Serviços de programação e comunicação visual, desenho industrial',

            // 24 - Serviços de chaveiros, confecção de carimbos
            self::S240101 => 'Serviços de chaveiros, confecção de carimbos, placas, sinalização visual',

            // 25 - Serviços funerários
            self::S250101 => 'Funerais, inclusive fornecimento de caixão, urna ou esquife',
            self::S250201 => 'Translado intramunicipal e cremação de corpos e partes de corpos cadavéricos',
            self::S250301 => 'Planos ou convênio funerários',
            self::S250401 => 'Manutenção e conservação de jazigos e cemitérios',
            self::S250501 => 'Cessão de uso de espaços em cemitérios para sepultamento',

            // 26 - Serviços de coleta, remessa ou entrega de correspondências
            self::S260101 => 'Serviços de coleta, remessa ou entrega de correspondências',

            // 27 - Serviços de assistência social
            self::S270101 => 'Serviços de assistência social',

            // 28 - Serviços de avaliação de bens e serviços
            self::S280101 => 'Serviços de avaliação de bens e serviços de qualquer natureza',

            // 29 - Serviços de biblioteconomia
            self::S290101 => 'Serviços de biblioteconomia',

            // 30 - Serviços de biologia, biotecnologia e química
            self::S300101 => 'Serviços de biologia, biotecnologia e química',

            // 31 - Serviços técnicos em edificações
            self::S310101 => 'Serviços técnicos em edificações, eletrônica, eletrotécnica',

            // 32 - Serviços de desenhos técnicos
            self::S320101 => 'Serviços de desenhos técnicos',

            // 33 - Serviços de desembaraço aduaneiro
            self::S330101 => 'Serviços de desembaraço aduaneiro, comissários, despachantes e congêneres',

            // 34 - Serviços de investigações particulares
            self::S340101 => 'Serviços de investigações particulares, detetives e congêneres',

            // 35 - Serviços de reportagem, assessoria de imprensa
            self::S350101 => 'Serviços de reportagem, assessoria de imprensa, jornalismo e relações públicas',

            // 36 - Serviços de meteorologia
            self::S360101 => 'Serviços de meteorologia',

            // 37 - Serviços de artistas, atletas, modelos
            self::S370101 => 'Serviços de artistas, atletas, modelos e manequins',

            // 38 - Serviços de museologia
            self::S380101 => 'Serviços de museologia',

            // 39 - Serviços de ourivesaria e lapidação
            self::S390101 => 'Serviços de ourivesaria e lapidação',

            // 40 - Serviços relativos a obras de arte sob encomenda
            self::S400101 => 'Obras de arte sob encomenda',
        };
    }

    /**
     * Retorna o código de tributação nacional
     *
     * @return string
     */
    public function codigo(): string
    {
        return $this->value;
    }

    /**
     * Retorna o item do serviço (primeiros 2 dígitos - II)
     * Corresponde ao grupo de serviços da LC 116/2003 (01 a 40)
     *
     * @return string
     */
    public function item(): string
    {
        return substr($this->value, 0, 2);
    }

    /**
     * Retorna o subitem do serviço (dígitos 3 e 4 - SS)
     *
     * @return string
     */
    public function subitem(): string
    {
        return substr($this->value, 2, 2);
    }

    /**
     * Retorna o desdobro do serviço (dígitos 5 e 6 - DD)
     *
     * @return string
     */
    public function desdobro(): string
    {
        return substr($this->value, 4, 2);
    }

    /**
     * Alias para item() - mantido para compatibilidade
     * @deprecated Use item() ao invés deste método
     *
     * @return string
     */
    public function grupo(): string
    {
        return $this->item();
    }

    /**
     * Cria uma instância do enum a partir de um código string
     *
     * @param string $codigo
     * @return self
     * @throws \ValueError
     */
    public static function fromCodigo(string $codigo): self
    {
        return self::from($codigo);
    }

    /**
     * Tenta criar uma instância do enum a partir de um código string
     *
     * @param string $codigo
     * @return self|null
     */
    public static function tryFromCodigo(string $codigo): ?self
    {
        return self::tryFrom($codigo);
    }

    /**
     * Verifica se um código é válido
     *
     * @param string $codigo
     * @return bool
     */
    public static function isValid(string $codigo): bool
    {
        return self::tryFrom($codigo) !== null;
    }

    /**
     * Retorna todos os códigos disponíveis
     *
     * @return array<string>
     */
    public static function allCodigos(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }

    /**
     * Retorna todos os serviços de um determinado item (grupo da LC 116/2003)
     *
     * @param string $item Código do item (2 dígitos, ex: '01', '17')
     * @return array<self>
     */
    public static function byItem(string $item): array
    {
        return array_filter(
            self::cases(),
            fn(self $case) => $case->item() === $item
        );
    }

    /**
     * Alias para byItem() - mantido para compatibilidade
     * @deprecated Use byItem() ao invés deste método
     *
     * @param string $grupo Código do grupo (2 dígitos)
     * @return array<self>
     */
    public static function byGrupo(string $grupo): array
    {
        return self::byItem($grupo);
    }
}
