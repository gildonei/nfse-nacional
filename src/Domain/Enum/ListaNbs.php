<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Lista NBS - Nomenclatura Brasileira de Serviços
 *
 * Códigos NBS utilizados para classificação de serviços, intangíveis e outras operações
 * que produzam variações no patrimônio das pessoas físicas, jurídicas ou entes despersonalizados.
 *
 * Código de 9 dígitos no formato: S.DDGG.CC.SS
 * - S = Seção (1 dígito)
 * - DD = Divisão (2 dígitos)
 * - GG = Grupo (2 dígitos)
 * - CC = Classe (2 dígitos)
 * - SS = Subclasse (2 dígitos)
 *
 * Foi utilizado o prefixo "N" nos cases do enum pois nomes de constantes
 * não podem iniciar com números em PHP.
 *
 * @see https://www.gov.br/siscoserv/pt-br/orientacoes/nbs
 * @package NfseNacional\Domain\Enum
 */
enum ListaNbs: string
{
    // Seção 1 - Serviços de construção
    case N101011100 = '101011100'; // Serviços de construção de edifícios residenciais
    case N101011200 = '101011200'; // Serviços de construção de edifícios não residenciais
    case N101021100 = '101021100'; // Serviços de construção de rodovias e ferrovias
    case N101021200 = '101021200'; // Serviços de construção de pontes, viadutos e túneis
    case N101021300 = '101021300'; // Serviços de construção de portos e canais
    case N101021400 = '101021400'; // Serviços de construção de barragens
    case N101021500 = '101021500'; // Serviços de construção de redes de água e esgoto
    case N101021600 = '101021600'; // Serviços de construção de redes de energia e telecomunicações
    case N101021700 = '101021700'; // Serviços de construção de instalações industriais
    case N101021800 = '101021800'; // Serviços de construção de instalações esportivas
    case N101021900 = '101021900'; // Outros serviços de construção de obras de engenharia civil
    case N101031000 = '101031000'; // Serviços de demolição
    case N101032000 = '101032000'; // Serviços de preparação de terrenos
    case N101041100 = '101041100'; // Serviços de instalação elétrica
    case N101041200 = '101041200'; // Serviços de instalação hidráulica e de gás
    case N101041300 = '101041300'; // Serviços de instalação de ar condicionado e ventilação
    case N101041400 = '101041400'; // Outros serviços de instalação
    case N101051100 = '101051100'; // Serviços de acabamento de edifícios
    case N101051200 = '101051200'; // Serviços de pintura
    case N101051300 = '101051300'; // Serviços de vidraçaria
    case N101059000 = '101059000'; // Outros serviços de acabamento de edifícios
    case N101061000 = '101061000'; // Serviços de aluguel de equipamentos de construção com operador

    // Seção 1 - Serviços de transformação, manutenção e reparo
    case N102011000 = '102011000'; // Serviços de transformação de produtos alimentícios
    case N102012000 = '102012000'; // Serviços de transformação de bebidas
    case N102013000 = '102013000'; // Serviços de transformação de produtos de tabaco
    case N102014000 = '102014000'; // Serviços de transformação de têxteis
    case N102015000 = '102015000'; // Serviços de transformação de vestuário
    case N102016000 = '102016000'; // Serviços de transformação de couro e calçados
    case N102017000 = '102017000'; // Serviços de transformação de madeira e produtos de madeira
    case N102018000 = '102018000'; // Serviços de transformação de papel e produtos de papel
    case N102019100 = '102019100'; // Serviços de impressão
    case N102019200 = '102019200'; // Serviços de reprodução de mídias gravadas
    case N102021000 = '102021000'; // Serviços de refino de petróleo
    case N102022000 = '102022000'; // Serviços de transformação de produtos químicos
    case N102023000 = '102023000'; // Serviços de transformação de produtos farmacêuticos
    case N102024000 = '102024000'; // Serviços de transformação de produtos de borracha e plástico
    case N102025000 = '102025000'; // Serviços de transformação de produtos de minerais não metálicos
    case N102026000 = '102026000'; // Serviços de transformação de metais básicos
    case N102027000 = '102027000'; // Serviços de transformação de produtos de metal
    case N102028000 = '102028000'; // Serviços de transformação de equipamentos eletrônicos e ópticos
    case N102029000 = '102029000'; // Serviços de transformação de máquinas e equipamentos
    case N102031000 = '102031000'; // Serviços de transformação de veículos automotores
    case N102032000 = '102032000'; // Serviços de transformação de outros equipamentos de transporte
    case N102039000 = '102039000'; // Outros serviços de transformação
    case N103011000 = '103011000'; // Serviços de manutenção e reparo de produtos de metal
    case N103012000 = '103012000'; // Serviços de manutenção e reparo de máquinas de uso geral
    case N103013000 = '103013000'; // Serviços de manutenção e reparo de máquinas de uso específico
    case N103014000 = '103014000'; // Serviços de manutenção e reparo de equipamentos eletrônicos e ópticos
    case N103015000 = '103015000'; // Serviços de manutenção e reparo de equipamentos elétricos
    case N103016000 = '103016000'; // Serviços de manutenção e reparo de embarcações
    case N103017000 = '103017000'; // Serviços de manutenção e reparo de aeronaves e espaçonaves
    case N103018000 = '103018000'; // Serviços de manutenção e reparo de veículos ferroviários
    case N103019000 = '103019000'; // Serviços de manutenção e reparo de veículos automotores
    case N103021000 = '103021000'; // Serviços de manutenção e reparo de bens pessoais e domésticos

    // Seção 1 - Serviços de publicação, impressão e reprodução
    case N104011000 = '104011000'; // Serviços de publicação de livros, periódicos e outras publicações
    case N104012000 = '104012000'; // Serviços de publicação de software
    case N104019000 = '104019000'; // Outros serviços de publicação

    // Seção 1 - Serviços de comércio e distribuição
    case N105011000 = '105011000'; // Serviços de comércio por atacado por comissão ou por contrato
    case N105012000 = '105012000'; // Serviços de comércio por atacado de produtos agropecuários
    case N105013000 = '105013000'; // Serviços de comércio por atacado de alimentos, bebidas e tabaco
    case N105014000 = '105014000'; // Serviços de comércio por atacado de têxteis, vestuário e calçados
    case N105015000 = '105015000'; // Serviços de comércio por atacado de eletrodomésticos
    case N105016000 = '105016000'; // Serviços de comércio por atacado de máquinas e equipamentos
    case N105019000 = '105019000'; // Outros serviços de comércio por atacado
    case N105021000 = '105021000'; // Serviços de comércio varejista de alimentos, bebidas e tabaco
    case N105022000 = '105022000'; // Serviços de comércio varejista de têxteis, vestuário e calçados
    case N105023000 = '105023000'; // Serviços de comércio varejista de eletrodomésticos
    case N105024000 = '105024000'; // Serviços de comércio varejista de materiais de construção
    case N105029000 = '105029000'; // Outros serviços de comércio varejista

    // Seção 1 - Serviços de hospedagem e alimentação
    case N106011000 = '106011000'; // Serviços de hospedagem
    case N106021000 = '106021000'; // Serviços de alimentação
    case N106022000 = '106022000'; // Serviços de bebidas

    // Seção 1 - Serviços de transporte de passageiros
    case N107011100 = '107011100'; // Serviços de transporte ferroviário interurbano de passageiros
    case N107011200 = '107011200'; // Serviços de transporte ferroviário urbano e suburbano de passageiros
    case N107012100 = '107012100'; // Serviços de transporte rodoviário interurbano de passageiros
    case N107012200 = '107012200'; // Serviços de transporte rodoviário urbano e suburbano de passageiros
    case N107013000 = '107013000'; // Serviços de transporte aquaviário de passageiros
    case N107014000 = '107014000'; // Serviços de transporte aéreo de passageiros
    case N107015000 = '107015000'; // Serviços de transporte espacial de passageiros
    case N107019000 = '107019000'; // Outros serviços de transporte de passageiros

    // Seção 1 - Serviços de transporte de cargas
    case N108011000 = '108011000'; // Serviços de transporte ferroviário de cargas
    case N108012000 = '108012000'; // Serviços de transporte rodoviário de cargas
    case N108013000 = '108013000'; // Serviços de transporte aquaviário de cargas
    case N108014000 = '108014000'; // Serviços de transporte aéreo de cargas
    case N108015000 = '108015000'; // Serviços de transporte espacial de cargas
    case N108016000 = '108016000'; // Serviços de transporte dutoviário
    case N108019000 = '108019000'; // Outros serviços de transporte de cargas

    // Seção 1 - Serviços de apoio aos transportes
    case N109011000 = '109011000'; // Serviços de manuseio de cargas
    case N109012000 = '109012000'; // Serviços de armazenamento
    case N109013100 = '109013100'; // Serviços de estações ferroviárias de passageiros
    case N109013200 = '109013200'; // Serviços de estações rodoviárias de passageiros
    case N109013300 = '109013300'; // Serviços de terminais portuários de passageiros
    case N109013400 = '109013400'; // Serviços de terminais aeroportuários de passageiros
    case N109014100 = '109014100'; // Serviços de infraestrutura ferroviária de cargas
    case N109014200 = '109014200'; // Serviços de infraestrutura rodoviária de cargas
    case N109014300 = '109014300'; // Serviços de infraestrutura portuária de cargas
    case N109014400 = '109014400'; // Serviços de infraestrutura aeroportuária de cargas
    case N109015000 = '109015000'; // Serviços de reboque e empurração
    case N109016000 = '109016000'; // Serviços de navegação e pilotagem
    case N109017000 = '109017000'; // Serviços de controle de tráfego
    case N109019000 = '109019000'; // Outros serviços de apoio aos transportes

    // Seção 1 - Serviços postais e de remessa
    case N110011000 = '110011000'; // Serviços postais
    case N110012000 = '110012000'; // Serviços de remessa expressa

    // Seção 1 - Serviços de distribuição de eletricidade, gás e água
    case N111011000 = '111011000'; // Serviços de transmissão e distribuição de eletricidade
    case N111012000 = '111012000'; // Serviços de distribuição de gás por redes
    case N111013000 = '111013000'; // Serviços de distribuição de água por redes
    case N111014000 = '111014000'; // Serviços de distribuição de vapor e água quente
    case N111015000 = '111015000'; // Serviços de distribuição de gelo

    // Seção 1 - Serviços financeiros e relacionados
    case N112011100 = '112011100'; // Serviços de depósitos
    case N112011200 = '112011200'; // Serviços de concessão de crédito
    case N112012100 = '112012100'; // Serviços de arrendamento mercantil (leasing) financeiro
    case N112013100 = '112013100'; // Serviços de câmbio
    case N112013200 = '112013200'; // Serviços de transferência de dinheiro
    case N112014000 = '112014000'; // Serviços de intermediação de valores mobiliários
    case N112015000 = '112015000'; // Serviços de custódia de valores mobiliários
    case N112016000 = '112016000'; // Serviços de administração de ativos
    case N112017000 = '112017000'; // Serviços de assessoria e consultoria financeira
    case N112019000 = '112019000'; // Outros serviços financeiros

    // Seção 1 - Serviços imobiliários
    case N113011000 = '113011000'; // Serviços imobiliários relativos a imóveis próprios ou arrendados
    case N113012000 = '113012000'; // Serviços imobiliários por comissão ou por contrato

    // Seção 1 - Serviços de arrendamento mercantil sem operador
    case N114011000 = '114011000'; // Serviços de aluguel de veículos de transporte terrestre sem operador
    case N114012000 = '114012000'; // Serviços de aluguel de embarcações sem operador
    case N114013000 = '114013000'; // Serviços de aluguel de aeronaves sem operador
    case N114014000 = '114014000'; // Serviços de aluguel de máquinas e equipamentos sem operador
    case N114015000 = '114015000'; // Serviços de aluguel de bens pessoais e domésticos
    case N114016000 = '114016000'; // Serviços de aluguel de propriedade intelectual
    case N114019000 = '114019000'; // Outros serviços de arrendamento mercantil sem operador

    // Seção 1 - Serviços de pesquisa e desenvolvimento
    case N115011000 = '115011000'; // Serviços de pesquisa e desenvolvimento em ciências naturais e engenharia
    case N115012000 = '115012000'; // Serviços de pesquisa e desenvolvimento em ciências sociais e humanidades
    case N115013000 = '115013000'; // Serviços de pesquisa e desenvolvimento interdisciplinares
    case N115019000 = '115019000'; // Outros serviços de pesquisa e desenvolvimento

    // Seção 1 - Serviços jurídicos e contábeis
    case N116011000 = '116011000'; // Serviços jurídicos
    case N116012000 = '116012000'; // Serviços de arbitragem e mediação
    case N116021100 = '116021100'; // Serviços de contabilidade e auditoria
    case N116021200 = '116021200'; // Serviços de escrituração contábil
    case N116022000 = '116022000'; // Serviços de consultoria tributária
    case N116023000 = '116023000'; // Serviços de recuperação de empresas e gestão de falências

    // Seção 1 - Serviços de negócios e de gestão
    case N117011100 = '117011100'; // Serviços de consultoria em gestão geral
    case N117011200 = '117011200'; // Serviços de consultoria em gestão financeira
    case N117011300 = '117011300'; // Serviços de consultoria em gestão de recursos humanos
    case N117011400 = '117011400'; // Serviços de consultoria em gestão de marketing
    case N117011500 = '117011500'; // Serviços de consultoria em gestão de produção
    case N117011600 = '117011600'; // Serviços de consultoria em gestão de projetos
    case N117011700 = '117011700'; // Serviços de relações públicas
    case N117019000 = '117019000'; // Outros serviços de consultoria em gestão
    case N117021000 = '117021000'; // Serviços de administração de empresas
    case N117022000 = '117022000'; // Serviços de escritórios-sede
    case N117031000 = '117031000'; // Serviços de publicidade
    case N117032000 = '117032000'; // Serviços de pesquisa de mercado e de opinião pública
    case N117033000 = '117033000'; // Serviços de fotografia
    case N117039000 = '117039000'; // Outros serviços de publicidade e pesquisa de mercado

    // Seção 1 - Serviços de telecomunicações, transmissão e fornecimento de informações
    case N118011100 = '118011100'; // Serviços de telecomunicações de voz
    case N118011200 = '118011200'; // Serviços de telecomunicações de dados
    case N118011300 = '118011300'; // Serviços de telecomunicações de imagem
    case N118012000 = '118012000'; // Serviços de transmissão por cabo
    case N118013000 = '118013000'; // Serviços de transmissão por satélite
    case N118014000 = '118014000'; // Serviços de transmissão por rádio e televisão
    case N118019000 = '118019000'; // Outros serviços de telecomunicações
    case N118021100 = '118021100'; // Serviços de hospedagem de dados (hosting)
    case N118021200 = '118021200'; // Serviços de processamento de dados
    case N118021300 = '118021300'; // Serviços de portais web
    case N118022000 = '118022000'; // Serviços de agências de notícias
    case N118023000 = '118023000'; // Serviços de bibliotecas e arquivos
    case N118029000 = '118029000'; // Outros serviços de fornecimento de informações

    // Seção 1 - Serviços de suporte
    case N119011000 = '119011000'; // Serviços de fornecimento de pessoal
    case N119012000 = '119012000'; // Serviços de investigação e segurança
    case N119013000 = '119013000'; // Serviços de limpeza
    case N119014000 = '119014000'; // Serviços de embalagem
    case N119015000 = '119015000'; // Serviços de organização de eventos e feiras
    case N119016000 = '119016000'; // Serviços de agências de viagens e operadores turísticos
    case N119017000 = '119017000'; // Serviços de cobrança e de informações cadastrais
    case N119018000 = '119018000'; // Serviços de call center
    case N119019000 = '119019000'; // Outros serviços de suporte

    // Seção 1 - Serviços de manutenção, instalação e reparo
    case N120011000 = '120011000'; // Serviços de manutenção e reparo de computadores
    case N120012000 = '120012000'; // Serviços de manutenção e reparo de equipamentos de comunicação
    case N120013000 = '120013000'; // Serviços de manutenção e reparo de equipamentos de escritório
    case N120019000 = '120019000'; // Outros serviços de manutenção e reparo

    // Seção 1 - Serviços de informática (TI)
    case N121011000 = '121011000'; // Serviços de consultoria em tecnologia da informação
    case N121012100 = '121012100'; // Serviços de desenvolvimento de software sob encomenda
    case N121012200 = '121012200'; // Serviços de desenvolvimento de software de prateleira
    case N121013000 = '121013000'; // Serviços de licenciamento de software
    case N121014000 = '121014000'; // Serviços de análise e programação de sistemas
    case N121015000 = '121015000'; // Serviços de administração de sistemas de computação
    case N121016000 = '121016000'; // Serviços de processamento de dados
    case N121017000 = '121017000'; // Serviços de gerenciamento de instalações de informática
    case N121019000 = '121019000'; // Outros serviços de tecnologia da informação

    // Seção 1 - Serviços de seguros
    case N122011000 = '122011000'; // Serviços de seguros de vida
    case N122012000 = '122012000'; // Serviços de seguros de acidentes e saúde
    case N122013000 = '122013000'; // Serviços de seguros de veículos
    case N122014000 = '122014000'; // Serviços de seguros marítimos, aéreos e de transporte
    case N122015000 = '122015000'; // Serviços de seguros de incêndio e riscos diversos
    case N122016000 = '122016000'; // Serviços de resseguros
    case N122017000 = '122017000'; // Serviços de corretagem de seguros
    case N122018000 = '122018000'; // Serviços de previdência privada
    case N122019000 = '122019000'; // Outros serviços de seguros

    // Seção 1 - Serviços técnicos e profissionais
    case N123011000 = '123011000'; // Serviços de arquitetura
    case N123012000 = '123012000'; // Serviços de engenharia
    case N123013000 = '123013000'; // Serviços de agrimensura e cartografia
    case N123014000 = '123014000'; // Serviços de design e estilismo
    case N123015000 = '123015000'; // Serviços de assessoramento e consultoria em biotecnologia
    case N123016000 = '123016000'; // Serviços de testes e análises técnicas
    case N123019000 = '123019000'; // Outros serviços técnicos

    // Seção 1 - Serviços ambientais
    case N124011000 = '124011000'; // Serviços de tratamento de águas residuais
    case N124012000 = '124012000'; // Serviços de gestão de resíduos sólidos
    case N124013000 = '124013000'; // Serviços de saneamento e serviços similares
    case N124014000 = '124014000'; // Serviços de limpeza do ar e controle de ruídos
    case N124015000 = '124015000'; // Serviços de proteção à natureza e à paisagem
    case N124019000 = '124019000'; // Outros serviços ambientais

    // Seção 1 - Serviços agrícolas, mineração e processamento no local
    case N125011000 = '125011000'; // Serviços relacionados à agricultura e pecuária
    case N125012000 = '125012000'; // Serviços relacionados à silvicultura
    case N125013000 = '125013000'; // Serviços relacionados à pesca
    case N125021000 = '125021000'; // Serviços relacionados à mineração
    case N125022000 = '125022000'; // Serviços relacionados à extração de petróleo e gás
    case N125029000 = '125029000'; // Outros serviços relacionados à mineração

    // Seção 1 - Serviços pessoais, culturais e recreativos
    case N126011100 = '126011100'; // Produção de filmes cinematográficos e vídeos
    case N126011200 = '126011200'; // Serviços de distribuição de filmes cinematográficos e vídeos
    case N126011300 = '126011300'; // Serviços de projeção de filmes cinematográficos
    case N126012100 = '126012100'; // Serviços de gravação de som
    case N126012200 = '126012200'; // Serviços de edição de música
    case N126013000 = '126013000'; // Serviços de transmissão de rádio e televisão
    case N126014000 = '126014000'; // Serviços de programação de televisão
    case N126021000 = '126021000'; // Serviços de produção de espetáculos
    case N126022000 = '126022000'; // Serviços de promoção de espetáculos
    case N126023000 = '126023000'; // Serviços de apoio a espetáculos
    case N126024000 = '126024000'; // Serviços de artistas, atletas e entidades relacionadas
    case N126031000 = '126031000'; // Serviços de museus e preservação
    case N126032000 = '126032000'; // Serviços de jardins botânicos e zoológicos
    case N126033000 = '126033000'; // Serviços de reservas naturais
    case N126041000 = '126041000'; // Serviços de parques de diversão e temáticos
    case N126042000 = '126042000'; // Serviços de jogos de azar e apostas
    case N126043000 = '126043000'; // Serviços de instalações esportivas e recreativas
    case N126049000 = '126049000'; // Outros serviços recreativos

    // Seção 1 - Serviços de educação e treinamento
    case N127011000 = '127011000'; // Serviços de educação pré-escolar
    case N127012000 = '127012000'; // Serviços de educação fundamental
    case N127013000 = '127013000'; // Serviços de educação média
    case N127014000 = '127014000'; // Serviços de educação pós-secundária não superior
    case N127015000 = '127015000'; // Serviços de educação superior
    case N127016000 = '127016000'; // Serviços de educação para adultos
    case N127019000 = '127019000'; // Outros serviços de educação
    case N127021000 = '127021000'; // Serviços de treinamento educacional
    case N127022000 = '127022000'; // Serviços de treinamento profissional
    case N127029000 = '127029000'; // Outros serviços de treinamento

    // Seção 1 - Serviços de saúde
    case N128011000 = '128011000'; // Serviços hospitalares
    case N128012000 = '128012000'; // Serviços médicos
    case N128013000 = '128013000'; // Serviços odontológicos
    case N128014000 = '128014000'; // Serviços de enfermagem
    case N128015000 = '128015000'; // Serviços de fisioterapia
    case N128016000 = '128016000'; // Serviços de ambulância e paramédicos
    case N128017000 = '128017000'; // Serviços de laboratórios médicos e diagnóstico
    case N128018000 = '128018000'; // Serviços de banco de sangue e órgãos
    case N128019000 = '128019000'; // Outros serviços de saúde humana
    case N128021000 = '128021000'; // Serviços de saúde para animais

    // Seção 1 - Serviços sociais
    case N129011000 = '129011000'; // Serviços sociais com alojamento
    case N129012000 = '129012000'; // Serviços sociais sem alojamento

    // Seção 2 - Serviços de cessão de direitos de propriedade intelectual
    case N201011000 = '201011000'; // Licenciamento de uso de marcas
    case N201012000 = '201012000'; // Licenciamento de uso de patentes
    case N201013000 = '201013000'; // Licenciamento de uso de desenhos industriais
    case N201014000 = '201014000'; // Licenciamento de uso de direitos autorais
    case N201015000 = '201015000'; // Franquias
    case N201019000 = '201019000'; // Outros serviços de cessão de direitos de propriedade intelectual

    // Seção 2 - Outros intangíveis
    case N202011000 = '202011000'; // Cessão de direitos de exploração de recursos naturais
    case N202012000 = '202012000'; // Cessão de direitos de exploração de frequências de rádio
    case N202019000 = '202019000'; // Outros intangíveis

    /**
     * Retorna a descrição do serviço NBS
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            // Seção 1 - Construção
            self::N101011100 => 'Serviços de construção de edifícios residenciais',
            self::N101011200 => 'Serviços de construção de edifícios não residenciais',
            self::N101021100 => 'Serviços de construção de rodovias e ferrovias',
            self::N101021200 => 'Serviços de construção de pontes, viadutos e túneis',
            self::N101021300 => 'Serviços de construção de portos e canais',
            self::N101021400 => 'Serviços de construção de barragens',
            self::N101021500 => 'Serviços de construção de redes de água e esgoto',
            self::N101021600 => 'Serviços de construção de redes de energia e telecomunicações',
            self::N101021700 => 'Serviços de construção de instalações industriais',
            self::N101021800 => 'Serviços de construção de instalações esportivas',
            self::N101021900 => 'Outros serviços de construção de obras de engenharia civil',
            self::N101031000 => 'Serviços de demolição',
            self::N101032000 => 'Serviços de preparação de terrenos',
            self::N101041100 => 'Serviços de instalação elétrica',
            self::N101041200 => 'Serviços de instalação hidráulica e de gás',
            self::N101041300 => 'Serviços de instalação de ar condicionado e ventilação',
            self::N101041400 => 'Outros serviços de instalação',
            self::N101051100 => 'Serviços de acabamento de edifícios',
            self::N101051200 => 'Serviços de pintura',
            self::N101051300 => 'Serviços de vidraçaria',
            self::N101059000 => 'Outros serviços de acabamento de edifícios',
            self::N101061000 => 'Serviços de aluguel de equipamentos de construção com operador',

            // Seção 1 - Transformação, manutenção e reparo
            self::N102011000 => 'Serviços de transformação de produtos alimentícios',
            self::N102012000 => 'Serviços de transformação de bebidas',
            self::N102013000 => 'Serviços de transformação de produtos de tabaco',
            self::N102014000 => 'Serviços de transformação de têxteis',
            self::N102015000 => 'Serviços de transformação de vestuário',
            self::N102016000 => 'Serviços de transformação de couro e calçados',
            self::N102017000 => 'Serviços de transformação de madeira e produtos de madeira',
            self::N102018000 => 'Serviços de transformação de papel e produtos de papel',
            self::N102019100 => 'Serviços de impressão',
            self::N102019200 => 'Serviços de reprodução de mídias gravadas',
            self::N102021000 => 'Serviços de refino de petróleo',
            self::N102022000 => 'Serviços de transformação de produtos químicos',
            self::N102023000 => 'Serviços de transformação de produtos farmacêuticos',
            self::N102024000 => 'Serviços de transformação de produtos de borracha e plástico',
            self::N102025000 => 'Serviços de transformação de produtos de minerais não metálicos',
            self::N102026000 => 'Serviços de transformação de metais básicos',
            self::N102027000 => 'Serviços de transformação de produtos de metal',
            self::N102028000 => 'Serviços de transformação de equipamentos eletrônicos e ópticos',
            self::N102029000 => 'Serviços de transformação de máquinas e equipamentos',
            self::N102031000 => 'Serviços de transformação de veículos automotores',
            self::N102032000 => 'Serviços de transformação de outros equipamentos de transporte',
            self::N102039000 => 'Outros serviços de transformação',
            self::N103011000 => 'Serviços de manutenção e reparo de produtos de metal',
            self::N103012000 => 'Serviços de manutenção e reparo de máquinas de uso geral',
            self::N103013000 => 'Serviços de manutenção e reparo de máquinas de uso específico',
            self::N103014000 => 'Serviços de manutenção e reparo de equipamentos eletrônicos e ópticos',
            self::N103015000 => 'Serviços de manutenção e reparo de equipamentos elétricos',
            self::N103016000 => 'Serviços de manutenção e reparo de embarcações',
            self::N103017000 => 'Serviços de manutenção e reparo de aeronaves e espaçonaves',
            self::N103018000 => 'Serviços de manutenção e reparo de veículos ferroviários',
            self::N103019000 => 'Serviços de manutenção e reparo de veículos automotores',
            self::N103021000 => 'Serviços de manutenção e reparo de bens pessoais e domésticos',

            // Seção 1 - Publicação, impressão e reprodução
            self::N104011000 => 'Serviços de publicação de livros, periódicos e outras publicações',
            self::N104012000 => 'Serviços de publicação de software',
            self::N104019000 => 'Outros serviços de publicação',

            // Seção 1 - Comércio e distribuição
            self::N105011000 => 'Serviços de comércio por atacado por comissão ou por contrato',
            self::N105012000 => 'Serviços de comércio por atacado de produtos agropecuários',
            self::N105013000 => 'Serviços de comércio por atacado de alimentos, bebidas e tabaco',
            self::N105014000 => 'Serviços de comércio por atacado de têxteis, vestuário e calçados',
            self::N105015000 => 'Serviços de comércio por atacado de eletrodomésticos',
            self::N105016000 => 'Serviços de comércio por atacado de máquinas e equipamentos',
            self::N105019000 => 'Outros serviços de comércio por atacado',
            self::N105021000 => 'Serviços de comércio varejista de alimentos, bebidas e tabaco',
            self::N105022000 => 'Serviços de comércio varejista de têxteis, vestuário e calçados',
            self::N105023000 => 'Serviços de comércio varejista de eletrodomésticos',
            self::N105024000 => 'Serviços de comércio varejista de materiais de construção',
            self::N105029000 => 'Outros serviços de comércio varejista',

            // Seção 1 - Hospedagem e alimentação
            self::N106011000 => 'Serviços de hospedagem',
            self::N106021000 => 'Serviços de alimentação',
            self::N106022000 => 'Serviços de bebidas',

            // Seção 1 - Transporte de passageiros
            self::N107011100 => 'Serviços de transporte ferroviário interurbano de passageiros',
            self::N107011200 => 'Serviços de transporte ferroviário urbano e suburbano de passageiros',
            self::N107012100 => 'Serviços de transporte rodoviário interurbano de passageiros',
            self::N107012200 => 'Serviços de transporte rodoviário urbano e suburbano de passageiros',
            self::N107013000 => 'Serviços de transporte aquaviário de passageiros',
            self::N107014000 => 'Serviços de transporte aéreo de passageiros',
            self::N107015000 => 'Serviços de transporte espacial de passageiros',
            self::N107019000 => 'Outros serviços de transporte de passageiros',

            // Seção 1 - Transporte de cargas
            self::N108011000 => 'Serviços de transporte ferroviário de cargas',
            self::N108012000 => 'Serviços de transporte rodoviário de cargas',
            self::N108013000 => 'Serviços de transporte aquaviário de cargas',
            self::N108014000 => 'Serviços de transporte aéreo de cargas',
            self::N108015000 => 'Serviços de transporte espacial de cargas',
            self::N108016000 => 'Serviços de transporte dutoviário',
            self::N108019000 => 'Outros serviços de transporte de cargas',

            // Seção 1 - Apoio aos transportes
            self::N109011000 => 'Serviços de manuseio de cargas',
            self::N109012000 => 'Serviços de armazenamento',
            self::N109013100 => 'Serviços de estações ferroviárias de passageiros',
            self::N109013200 => 'Serviços de estações rodoviárias de passageiros',
            self::N109013300 => 'Serviços de terminais portuários de passageiros',
            self::N109013400 => 'Serviços de terminais aeroportuários de passageiros',
            self::N109014100 => 'Serviços de infraestrutura ferroviária de cargas',
            self::N109014200 => 'Serviços de infraestrutura rodoviária de cargas',
            self::N109014300 => 'Serviços de infraestrutura portuária de cargas',
            self::N109014400 => 'Serviços de infraestrutura aeroportuária de cargas',
            self::N109015000 => 'Serviços de reboque e empurração',
            self::N109016000 => 'Serviços de navegação e pilotagem',
            self::N109017000 => 'Serviços de controle de tráfego',
            self::N109019000 => 'Outros serviços de apoio aos transportes',

            // Seção 1 - Postais e remessa
            self::N110011000 => 'Serviços postais',
            self::N110012000 => 'Serviços de remessa expressa',

            // Seção 1 - Distribuição
            self::N111011000 => 'Serviços de transmissão e distribuição de eletricidade',
            self::N111012000 => 'Serviços de distribuição de gás por redes',
            self::N111013000 => 'Serviços de distribuição de água por redes',
            self::N111014000 => 'Serviços de distribuição de vapor e água quente',
            self::N111015000 => 'Serviços de distribuição de gelo',

            // Seção 1 - Financeiros
            self::N112011100 => 'Serviços de depósitos',
            self::N112011200 => 'Serviços de concessão de crédito',
            self::N112012100 => 'Serviços de arrendamento mercantil (leasing) financeiro',
            self::N112013100 => 'Serviços de câmbio',
            self::N112013200 => 'Serviços de transferência de dinheiro',
            self::N112014000 => 'Serviços de intermediação de valores mobiliários',
            self::N112015000 => 'Serviços de custódia de valores mobiliários',
            self::N112016000 => 'Serviços de administração de ativos',
            self::N112017000 => 'Serviços de assessoria e consultoria financeira',
            self::N112019000 => 'Outros serviços financeiros',

            // Seção 1 - Imobiliários
            self::N113011000 => 'Serviços imobiliários relativos a imóveis próprios ou arrendados',
            self::N113012000 => 'Serviços imobiliários por comissão ou por contrato',

            // Seção 1 - Arrendamento mercantil
            self::N114011000 => 'Serviços de aluguel de veículos de transporte terrestre sem operador',
            self::N114012000 => 'Serviços de aluguel de embarcações sem operador',
            self::N114013000 => 'Serviços de aluguel de aeronaves sem operador',
            self::N114014000 => 'Serviços de aluguel de máquinas e equipamentos sem operador',
            self::N114015000 => 'Serviços de aluguel de bens pessoais e domésticos',
            self::N114016000 => 'Serviços de aluguel de propriedade intelectual',
            self::N114019000 => 'Outros serviços de arrendamento mercantil sem operador',

            // Seção 1 - Pesquisa e desenvolvimento
            self::N115011000 => 'Serviços de pesquisa e desenvolvimento em ciências naturais e engenharia',
            self::N115012000 => 'Serviços de pesquisa e desenvolvimento em ciências sociais e humanidades',
            self::N115013000 => 'Serviços de pesquisa e desenvolvimento interdisciplinares',
            self::N115019000 => 'Outros serviços de pesquisa e desenvolvimento',

            // Seção 1 - Jurídicos e contábeis
            self::N116011000 => 'Serviços jurídicos',
            self::N116012000 => 'Serviços de arbitragem e mediação',
            self::N116021100 => 'Serviços de contabilidade e auditoria',
            self::N116021200 => 'Serviços de escrituração contábil',
            self::N116022000 => 'Serviços de consultoria tributária',
            self::N116023000 => 'Serviços de recuperação de empresas e gestão de falências',

            // Seção 1 - Negócios e gestão
            self::N117011100 => 'Serviços de consultoria em gestão geral',
            self::N117011200 => 'Serviços de consultoria em gestão financeira',
            self::N117011300 => 'Serviços de consultoria em gestão de recursos humanos',
            self::N117011400 => 'Serviços de consultoria em gestão de marketing',
            self::N117011500 => 'Serviços de consultoria em gestão de produção',
            self::N117011600 => 'Serviços de consultoria em gestão de projetos',
            self::N117011700 => 'Serviços de relações públicas',
            self::N117019000 => 'Outros serviços de consultoria em gestão',
            self::N117021000 => 'Serviços de administração de empresas',
            self::N117022000 => 'Serviços de escritórios-sede',
            self::N117031000 => 'Serviços de publicidade',
            self::N117032000 => 'Serviços de pesquisa de mercado e de opinião pública',
            self::N117033000 => 'Serviços de fotografia',
            self::N117039000 => 'Outros serviços de publicidade e pesquisa de mercado',

            // Seção 1 - Telecomunicações
            self::N118011100 => 'Serviços de telecomunicações de voz',
            self::N118011200 => 'Serviços de telecomunicações de dados',
            self::N118011300 => 'Serviços de telecomunicações de imagem',
            self::N118012000 => 'Serviços de transmissão por cabo',
            self::N118013000 => 'Serviços de transmissão por satélite',
            self::N118014000 => 'Serviços de transmissão por rádio e televisão',
            self::N118019000 => 'Outros serviços de telecomunicações',
            self::N118021100 => 'Serviços de hospedagem de dados (hosting)',
            self::N118021200 => 'Serviços de processamento de dados',
            self::N118021300 => 'Serviços de portais web',
            self::N118022000 => 'Serviços de agências de notícias',
            self::N118023000 => 'Serviços de bibliotecas e arquivos',
            self::N118029000 => 'Outros serviços de fornecimento de informações',

            // Seção 1 - Suporte
            self::N119011000 => 'Serviços de fornecimento de pessoal',
            self::N119012000 => 'Serviços de investigação e segurança',
            self::N119013000 => 'Serviços de limpeza',
            self::N119014000 => 'Serviços de embalagem',
            self::N119015000 => 'Serviços de organização de eventos e feiras',
            self::N119016000 => 'Serviços de agências de viagens e operadores turísticos',
            self::N119017000 => 'Serviços de cobrança e de informações cadastrais',
            self::N119018000 => 'Serviços de call center',
            self::N119019000 => 'Outros serviços de suporte',

            // Seção 1 - Manutenção TI
            self::N120011000 => 'Serviços de manutenção e reparo de computadores',
            self::N120012000 => 'Serviços de manutenção e reparo de equipamentos de comunicação',
            self::N120013000 => 'Serviços de manutenção e reparo de equipamentos de escritório',
            self::N120019000 => 'Outros serviços de manutenção e reparo',

            // Seção 1 - Informática
            self::N121011000 => 'Serviços de consultoria em tecnologia da informação',
            self::N121012100 => 'Serviços de desenvolvimento de software sob encomenda',
            self::N121012200 => 'Serviços de desenvolvimento de software de prateleira',
            self::N121013000 => 'Serviços de licenciamento de software',
            self::N121014000 => 'Serviços de análise e programação de sistemas',
            self::N121015000 => 'Serviços de administração de sistemas de computação',
            self::N121016000 => 'Serviços de processamento de dados',
            self::N121017000 => 'Serviços de gerenciamento de instalações de informática',
            self::N121019000 => 'Outros serviços de tecnologia da informação',

            // Seção 1 - Seguros
            self::N122011000 => 'Serviços de seguros de vida',
            self::N122012000 => 'Serviços de seguros de acidentes e saúde',
            self::N122013000 => 'Serviços de seguros de veículos',
            self::N122014000 => 'Serviços de seguros marítimos, aéreos e de transporte',
            self::N122015000 => 'Serviços de seguros de incêndio e riscos diversos',
            self::N122016000 => 'Serviços de resseguros',
            self::N122017000 => 'Serviços de corretagem de seguros',
            self::N122018000 => 'Serviços de previdência privada',
            self::N122019000 => 'Outros serviços de seguros',

            // Seção 1 - Técnicos
            self::N123011000 => 'Serviços de arquitetura',
            self::N123012000 => 'Serviços de engenharia',
            self::N123013000 => 'Serviços de agrimensura e cartografia',
            self::N123014000 => 'Serviços de design e estilismo',
            self::N123015000 => 'Serviços de assessoramento e consultoria em biotecnologia',
            self::N123016000 => 'Serviços de testes e análises técnicas',
            self::N123019000 => 'Outros serviços técnicos',

            // Seção 1 - Ambientais
            self::N124011000 => 'Serviços de tratamento de águas residuais',
            self::N124012000 => 'Serviços de gestão de resíduos sólidos',
            self::N124013000 => 'Serviços de saneamento e serviços similares',
            self::N124014000 => 'Serviços de limpeza do ar e controle de ruídos',
            self::N124015000 => 'Serviços de proteção à natureza e à paisagem',
            self::N124019000 => 'Outros serviços ambientais',

            // Seção 1 - Agrícolas e mineração
            self::N125011000 => 'Serviços relacionados à agricultura e pecuária',
            self::N125012000 => 'Serviços relacionados à silvicultura',
            self::N125013000 => 'Serviços relacionados à pesca',
            self::N125021000 => 'Serviços relacionados à mineração',
            self::N125022000 => 'Serviços relacionados à extração de petróleo e gás',
            self::N125029000 => 'Outros serviços relacionados à mineração',

            // Seção 1 - Culturais e recreativos
            self::N126011100 => 'Produção de filmes cinematográficos e vídeos',
            self::N126011200 => 'Serviços de distribuição de filmes cinematográficos e vídeos',
            self::N126011300 => 'Serviços de projeção de filmes cinematográficos',
            self::N126012100 => 'Serviços de gravação de som',
            self::N126012200 => 'Serviços de edição de música',
            self::N126013000 => 'Serviços de transmissão de rádio e televisão',
            self::N126014000 => 'Serviços de programação de televisão',
            self::N126021000 => 'Serviços de produção de espetáculos',
            self::N126022000 => 'Serviços de promoção de espetáculos',
            self::N126023000 => 'Serviços de apoio a espetáculos',
            self::N126024000 => 'Serviços de artistas, atletas e entidades relacionadas',
            self::N126031000 => 'Serviços de museus e preservação',
            self::N126032000 => 'Serviços de jardins botânicos e zoológicos',
            self::N126033000 => 'Serviços de reservas naturais',
            self::N126041000 => 'Serviços de parques de diversão e temáticos',
            self::N126042000 => 'Serviços de jogos de azar e apostas',
            self::N126043000 => 'Serviços de instalações esportivas e recreativas',
            self::N126049000 => 'Outros serviços recreativos',

            // Seção 1 - Educação
            self::N127011000 => 'Serviços de educação pré-escolar',
            self::N127012000 => 'Serviços de educação fundamental',
            self::N127013000 => 'Serviços de educação média',
            self::N127014000 => 'Serviços de educação pós-secundária não superior',
            self::N127015000 => 'Serviços de educação superior',
            self::N127016000 => 'Serviços de educação para adultos',
            self::N127019000 => 'Outros serviços de educação',
            self::N127021000 => 'Serviços de treinamento educacional',
            self::N127022000 => 'Serviços de treinamento profissional',
            self::N127029000 => 'Outros serviços de treinamento',

            // Seção 1 - Saúde
            self::N128011000 => 'Serviços hospitalares',
            self::N128012000 => 'Serviços médicos',
            self::N128013000 => 'Serviços odontológicos',
            self::N128014000 => 'Serviços de enfermagem',
            self::N128015000 => 'Serviços de fisioterapia',
            self::N128016000 => 'Serviços de ambulância e paramédicos',
            self::N128017000 => 'Serviços de laboratórios médicos e diagnóstico',
            self::N128018000 => 'Serviços de banco de sangue e órgãos',
            self::N128019000 => 'Outros serviços de saúde humana',
            self::N128021000 => 'Serviços de saúde para animais',

            // Seção 1 - Sociais
            self::N129011000 => 'Serviços sociais com alojamento',
            self::N129012000 => 'Serviços sociais sem alojamento',

            // Seção 2 - Propriedade intelectual
            self::N201011000 => 'Licenciamento de uso de marcas',
            self::N201012000 => 'Licenciamento de uso de patentes',
            self::N201013000 => 'Licenciamento de uso de desenhos industriais',
            self::N201014000 => 'Licenciamento de uso de direitos autorais',
            self::N201015000 => 'Franquias',
            self::N201019000 => 'Outros serviços de cessão de direitos de propriedade intelectual',

            // Seção 2 - Outros intangíveis
            self::N202011000 => 'Cessão de direitos de exploração de recursos naturais',
            self::N202012000 => 'Cessão de direitos de exploração de frequências de rádio',
            self::N202019000 => 'Outros intangíveis',
        };
    }

    /**
     * Retorna o código NBS
     *
     * @return string
     */
    public function codigo(): string
    {
        return $this->value;
    }

    /**
     * Retorna a seção do código NBS (1º dígito)
     *
     * @return string
     */
    public function secao(): string
    {
        return substr($this->value, 0, 1);
    }

    /**
     * Retorna a divisão do código NBS (dígitos 2-3)
     *
     * @return string
     */
    public function divisao(): string
    {
        return substr($this->value, 1, 2);
    }

    /**
     * Retorna o grupo do código NBS (dígitos 4-5)
     *
     * @return string
     */
    public function grupo(): string
    {
        return substr($this->value, 3, 2);
    }

    /**
     * Retorna a classe do código NBS (dígitos 6-7)
     *
     * @return string
     */
    public function classe(): string
    {
        return substr($this->value, 5, 2);
    }

    /**
     * Retorna a subclasse do código NBS (dígitos 8-9)
     *
     * @return string
     */
    public function subclasse(): string
    {
        return substr($this->value, 7, 2);
    }

    /**
     * Cria uma instância do enum a partir de um código string
     *
     * @param string $codigo
     * @return self|null
     */
    public static function fromCodigo(string $codigo): ?self
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
     * Retorna todos os serviços de uma determinada seção
     *
     * @param string $secao Código da seção (1 dígito)
     * @return array<self>
     */
    public static function bySecao(string $secao): array
    {
        return array_filter(
            self::cases(),
            fn(self $case) => $case->secao() === $secao
        );
    }

    /**
     * Retorna todos os serviços de uma determinada divisão
     *
     * @param string $divisao Código da divisão (2 dígitos)
     * @return array<self>
     */
    public static function byDivisao(string $divisao): array
    {
        return array_filter(
            self::cases(),
            fn(self $case) => $case->divisao() === $divisao
        );
    }
}
