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
 * Quando o código de tributação nacional (cTribNac) está vazio na tabela oficial,
 * utiliza-se a concatenação dos campos Item + Subitem + Desdobro.
 * Foi utilizado o prefixo "S" na lista dos serviços devido ao Item ("grupo") poder iniciar com Zeros.
 *
 * @package NfseNacional\Domain\Enum
 */
enum ListaServicosNacional: string
{
    case S010101 = '010101'; // Análise e desenvolvimento de sistemas.
    case S010201 = '010201'; // Programação.
    case S010301 = '010301'; // Processamento de dados, textos, imagens, vídeos, páginas eletrônicas, aplicativos e sistemas de i...
    case S010302 = '010302'; // Armazenamento ou hospedagem de dados, textos, imagens, vídeos, páginas eletrônicas, aplicativos e...
    case S010401 = '010401'; // Elaboração de programas de computadores, inclusive de jogos eletrônicos, independentemente da arq...
    case S010501 = '010501'; // Licenciamento ou cessão de direito de uso de programas de computação.
    case S010601 = '010601'; // Assessoria e consultoria em informática.
    case S010701 = '010701'; // Suporte técnico em informática, inclusive instalação, configuração e manutenção de programas de c...
    case S010801 = '010801'; // Planejamento, confecção, manutenção e atualização de páginas eletrônicas.
    case S010901 = '010901'; // Disponibilização, sem cessão definitiva, de conteúdos de áudio por meio da internet (exceto a dis...
    case S010902 = '010902'; // Disponibilização, sem cessão definitiva, de conteúdos de vídeo, imagem e texto por meio da intern...
    case S020101 = '020101'; // Serviços de pesquisas e desenvolvimento de qualquer natureza.
    case S030201 = '030201'; // Cessão de direito de uso de marcas e de sinais de propaganda.
    case S030301 = '030301'; // Exploração de salões de festas, centro de convenções, stands e congêneres, para realização de eve...
    case S030302 = '030302'; // Exploração de escritórios virtuais e congêneres, para realização de eventos ou negócios de qualqu...
    case S030303 = '030303'; // Exploração de quadras esportivas, estádios, ginásios, canchas e congêneres, para realização de ev...
    case S030304 = '030304'; // Exploração de auditórios, casas de espetáculos e congêneres, para realização de eventos ou negóci...
    case S030305 = '030305'; // Exploração de parques de diversões e congêneres, para realização de eventos ou negócios de qualqu...
    case S030401 = '030401'; // Locação, sublocação, arrendamento, direito de passagem ou permissão de uso, compartilhado ou não,...
    case S030402 = '030402'; // Locação, sublocação, arrendamento, direito de passagem ou permissão de uso, compartilhado ou não,...
    case S030403 = '030403'; // Locação, sublocação, arrendamento, direito de passagem ou permissão de uso, compartilhado ou não,...
    case S030501 = '030501'; // Cessão de andaimes, palcos, coberturas e outras estruturas de uso temporário.
    case S040101 = '040101'; // Medicina.
    case S040102 = '040102'; // Biomedicina.
    case S040201 = '040201'; // Análises clínicas e congêneres.
    case S040202 = '040202'; // Patologia e congêneres.
    case S040203 = '040203'; // Eletricidade médica (eletroestimulação de nervos e musculos, cardioversão, etc) e congêneres.
    case S040204 = '040204'; // Radioterapia, quimioterapia e congêneres.
    case S040205 = '040205'; // Ultra-sonografia, ressonância magnética, radiologia, tomografia e congêneres.
    case S040301 = '040301'; // Hospitais e congêneres.
    case S040302 = '040302'; // Laboratórios e congêneres.
    case S040303 = '040303'; // Clínicas, sanatórios, manicômios, casas de saúde, prontos-socorros, ambulatórios e congêneres.
    case S040401 = '040401'; // Instrumentação cirúrgica.
    case S040501 = '040501'; // Acupuntura.
    case S040601 = '040601'; // Enfermagem, inclusive serviços auxiliares.
    case S040701 = '040701'; // Serviços farmacêuticos.
    case S040801 = '040801'; // Terapia ocupacional.
    case S040802 = '040802'; // Fisioterapia.
    case S040803 = '040803'; // Fonoaudiologia.
    case S040901 = '040901'; // Terapias de qualquer espécie destinadas ao tratamento físico, orgânico e mental.
    case S041001 = '041001'; // Nutrição.
    case S041101 = '041101'; // Obstetrícia.
    case S041201 = '041201'; // Odontologia.
    case S041301 = '041301'; // Ortóptica.
    case S041401 = '041401'; // Próteses sob encomenda.
    case S041501 = '041501'; // Psicanálise.
    case S041601 = '041601'; // Psicologia.
    case S041701 = '041701'; // Casas de repouso e congêneres.
    case S041702 = '041702'; // Casas de recuperação e congêneres.
    case S041703 = '041703'; // Creches e congêneres.
    case S041704 = '041704'; // Asilos e congêneres.
    case S041801 = '041801'; // Inseminação artificial, fertilização in vitro e congêneres.
    case S041901 = '041901'; // Bancos de sangue, leite, pele, olhos, óvulos, sêmen e congêneres.
    case S042001 = '042001'; // Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos de qualquer espécie.
    case S042101 = '042101'; // Unidade de atendimento, assistência ou tratamento móvel e congêneres.
    case S042201 = '042201'; // Planos de medicina de grupo ou individual e convênios para prestação de assistência médica, hospi...
    case S042301 = '042301'; // Outros planos de saúde que se cumpram através de serviços de terceiros contratados, credenciados,...
    case S050101 = '050101'; // Medicina veterinária
    case S050102 = '050102'; // Zootecnia.
    case S050201 = '050201'; // Hospitais e congêneres, na área veterinária.
    case S050202 = '050202'; // Clínicas, ambulatórios, prontos-socorros e congêneres, na área veterinária.
    case S050301 = '050301'; // Laboratórios de análise na área veterinária.
    case S050401 = '050401'; // Inseminação artificial, fertilização in vitro e congêneres.
    case S050501 = '050501'; // Bancos de sangue e de órgãos e congêneres.
    case S050601 = '050601'; // Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos de qualquer espécie.
    case S050701 = '050701'; // Unidade de atendimento, assistência ou tratamento móvel e congêneres.
    case S050801 = '050801'; // Guarda, tratamento, amestramento, embelezamento, alojamento e congêneres.
    case S050901 = '050901'; // Planos de atendimento e assistência médico-veterinária.
    case S060101 = '060101'; // Barbearia, cabeleireiros, manicuros, pedicuros e congêneres.
    case S060201 = '060201'; // Esteticistas, tratamento de pele, depilação e congêneres.
    case S060301 = '060301'; // Banhos, duchas, sauna, massagens e congêneres.
    case S060401 = '060401'; // Ginástica, dança, esportes, natação, artes marciais e demais atividades físicas.
    case S060501 = '060501'; // Centros de emagrecimento, spa e congêneres.
    case S060601 = '060601'; // Aplicação de tatuagens, piercings e congêneres.
    case S070101 = '070101'; // Engenharia e congêneres.
    case S070102 = '070102'; // Agronomia e congêneres.
    case S070103 = '070103'; // Agrimensura e congêneres.
    case S070104 = '070104'; // Arquitetura, urbanismo e congêneres.
    case S070105 = '070105'; // Geologia e congêneres.
    case S070106 = '070106'; // Paisagismo e congêneres.
    case S070201 = '070201'; // Execução, por administração, de obras de construção civil, hidráulica ou elétrica e de outras obr...
    case S070202 = '070202'; // Execução, por empreitada ou subempreitada, de obras de construção civil, hidráulica ou elétrica e...
    case S070301 = '070301'; // Elaboração de planos diretores, estudos de viabilidade, estudos organizacionais e outros, relacio...
    case S070302 = '070302'; // Elaboração de anteprojetos, projetos básicos e projetos executivos para trabalhos de engenharia.
    case S070401 = '070401'; // Demolição.
    case S070501 = '070501'; // Reparação, conservação e reforma de edifícios e congêneres (exceto o fornecimento de mercadorias ...
    case S070502 = '070502'; // Reparação, conservação e reforma de estradas, pontes, portos e congêneres (exceto o fornecimento ...
    case S070601 = '070601'; // Colocação e instalação de tapetes, carpetes, cortinas e congêneres, com material fornecido pelo t...
    case S070602 = '070602'; // Colocação e instalação de assoalhos, revestimentos de parede, vidros, divisórias, placas de gesso...
    case S070701 = '070701'; // Recuperação, raspagem, polimento e lustração de pisos e congêneres.
    case S070801 = '070801'; // Calafetação.
    case S070901 = '070901'; // Varrição, coleta e remoção de lixo, rejeitos e outros resíduos quaisquer.
    case S070902 = '070902'; // Incineração, tratamento, reciclagem, separação e destinação final de lixo, rejeitos e outros resí...
    case S071001 = '071001'; // Limpeza, manutenção e conservação de vias e logradouros públicos, parques, jardins e congêneres.
    case S071002 = '071002'; // Limpeza, manutenção e conservação de imóveis, chaminés, piscinas e congêneres.
    case S071101 = '071101'; // Decoração.
    case S071102 = '071102'; // Jardinagem, inclusive corte e poda de árvores.
    case S071201 = '071201'; // Controle e tratamento de efluentes de qualquer natureza e de agentes físicos, químicos e biológicos.
    case S071301 = '071301'; // Dedetização, desinfecção, desinsetização, imunização, higienização, desratização, pulverização e ...
    case S071601 = '071601'; // Florestamento, reflorestamento, semeadura, adubação, reparação de solo, plantio, silagem, colheit...
    case S071701 = '071701'; // Escoramento, contenção de encostas e serviços congêneres.
    case S071801 = '071801'; // Limpeza e dragagem de rios, portos, canais, baías, lagos, lagoas, represas, açudes e congêneres.
    case S071901 = '071901'; // Acompanhamento e fiscalização da execução de obras de engenharia, arquitetura e urbanismo.
    case S072001 = '072001'; // Aerofotogrametria (inclusive interpretação), cartografia, mapeamento e congêneres.
    case S072002 = '072002'; // Levantamentos batimétricos, geográficos, geodésicos, geológicos, geofísicos e congêneres.
    case S072003 = '072003'; // Levantamentos topográficos e congêneres.
    case S072101 = '072101'; // Pesquisa, perfuração, cimentação, mergulho, perfilagem, concretação, testemunhagem, pescaria, est...
    case S072201 = '072201'; // Nucleação e bombardeamento de nuvens e congêneres.
    case S080101 = '080101'; // Ensino regular pré-escolar, fundamental e médio.
    case S080102 = '080102'; // Ensino regular superior.
    case S080201 = '080201'; // Instrução, treinamento, orientação pedagógica e educacional, avaliação de conhecimentos de qualqu...
    case S090101 = '090101'; // Hospedagem em hotéis, hotelaria marítima e congêneres (o valor da alimentação e gorjeta, quando i...
    case S090102 = '090102'; // Hospedagem em pensões, albergues, pousadas, hospedarias, ocupação por temporada com fornecimento ...
    case S090103 = '090103'; // Hospedagem em motéis e congêneres (o valor da alimentação e gorjeta, quando incluído no preço da ...
    case S090104 = '090104'; // Hospedagem em apart-service condominiais, flat, apart-hotéis, hotéis residência, residence-servic...
    case S090201 = '090201'; // Agenciamento e intermediação de programas de turismo, passeios, viagens, excursões, hospedagens e...
    case S090202 = '090202'; // Organização, promoção e execução de programas de turismo, passeios, viagens, excursões, hospedage...
    case S090301 = '090301'; // Guias de turismo.
    case S100101 = '100101'; // Agenciamento, corretagem ou intermediação de câmbio.
    case S100102 = '100102'; // Agenciamento, corretagem ou intermediação de seguros.
    case S100103 = '100103'; // Agenciamento, corretagem ou intermediação de cartões de crédito.
    case S100104 = '100104'; // Agenciamento, corretagem ou intermediação de planos de saúde.
    case S100105 = '100105'; // Agenciamento, corretagem ou intermediação de planos de previdência privada.
    case S100201 = '100201'; // Agenciamento, corretagem ou intermediação de títulos em geral e valores mobiliários.
    case S100202 = '100202'; // Agenciamento, corretagem ou intermediação de contratos quaisquer.
    case S100301 = '100301'; // Agenciamento, corretagem ou intermediação de direitos de propriedade industrial, artística ou lit...
    case S100401 = '100401'; // Agenciamento, corretagem ou intermediação de contratos de arrendamento mercantil (leasing).
    case S100402 = '100402'; // Agenciamento, corretagem ou intermediação de contratos de franquia (franchising).
    case S100403 = '100403'; // Agenciamento, corretagem ou intermediação de faturização (factoring).
    case S100501 = '100501'; // Agenciamento, corretagem ou intermediação de bens móveis ou imóveis, não abrangidos em outros ite...
    case S100502 = '100502'; // Agenciamento, corretagem ou intermediação de bens móveis ou imóveis realizados no âmbito de Bolsa...
    case S100601 = '100601'; // Agenciamento marítimo.
    case S100701 = '100701'; // Agenciamento de notícias.
    case S100801 = '100801'; // Agenciamento de publicidade e propaganda, inclusive o agenciamento de veiculação por quaisquer me...
    case S100901 = '100901'; // Representação de qualquer natureza, inclusive comercial.
    case S101001 = '101001'; // Distribuição de bens de terceiros.
    case S110101 = '110101'; // Guarda e estacionamento de veículos terrestres automotores.
    case S110102 = '110102'; // Guarda e estacionamento de aeronaves e de embarcações.
    case S110201 = '110201'; // Vigilância, segurança ou monitoramento de bens, pessoas e semoventes.
    case S110301 = '110301'; // Escolta, inclusive de veículos e cargas.
    case S110401 = '110401'; // Armazenamento, depósito, guarda de bens de qualquer espécie.
    case S110402 = '110402'; // Carga, descarga, arrumação de bens de qualquer espécie.
    case S110501 = '110501'; // Serviços relacionados ao monitoramento e rastreamento a distância, em qualquer via ou local, de v...
    case S120101 = '120101'; // Espetáculos teatrais.
    case S120201 = '120201'; // Exibições cinematográficas.
    case S120301 = '120301'; // Espetáculos circenses.
    case S120401 = '120401'; // Programas de auditório.
    case S120501 = '120501'; // Parques de diversões, centros de lazer e congêneres.
    case S120601 = '120601'; // Boates, taxi-dancing e congêneres.
    case S120701 = '120701'; // Shows, ballet, danças, desfiles, bailes, óperas, concertos, recitais, festivais e congêneres.
    case S120801 = '120801'; // Feiras, exposições, congressos e congêneres.
    case S120901 = '120901'; // Bilhares.
    case S120902 = '120902'; // Boliches.
    case S120903 = '120903'; // Diversões eletrônicas ou não.
    case S121001 = '121001'; // Corridas e competições de animais.
    case S121101 = '121101'; // Competições esportivas ou de destreza física ou intelectual, com ou sem a participação do especta...
    case S121201 = '121201'; // Execução de música.
    case S121301 = '121301'; // Produção, mediante ou sem encomenda prévia, de eventos, espetáculos, entrevistas, shows, ballet, ...
    case S121401 = '121401'; // Fornecimento de música para ambientes fechados ou não, mediante transmissão por qualquer processo.
    case S121501 = '121501'; // Desfiles de blocos carnavalescos ou folclóricos, trios elétricos e congêneres.
    case S121601 = '121601'; // Exibição de filmes, entrevistas, musicais, espetáculos, shows, concertos, desfiles, óperas, compe...
    case S121701 = '121701'; // Recreação e animação, inclusive em festas e eventos de qualquer natureza.
    case S130201 = '130201'; // Fonografia ou gravação de sons, inclusive trucagem, dublagem, mixagem e congêneres.
    case S130301 = '130301'; // Fotografia e cinematografia, inclusive revelação, ampliação, cópia, reprodução, trucagem e congên...
    case S130401 = '130401'; // Reprografia, microfilmagem e digitalização.
    case S130501 = '130501'; // Composição gráfica, inclusive confecção de impressos gráficos, fotocomposição, clicheria, zincogr...
    case S140101 = '140101'; // Lubrificação, limpeza, lustração, revisão, carga e recarga, conserto, restauração, blindagem, man...
    case S140201 = '140201'; // Assistência técnica.
    case S140301 = '140301'; // Recondicionamento de motores (exceto peças e partes empregadas, que ficam sujeitas ao ICMS).
    case S140401 = '140401'; // Recauchutagem ou regeneração de pneus.
    case S140501 = '140501'; // Restauração, recondicionamento, acondicionamento, pintura, beneficiamento, lavagem, secagem, ting...
    case S140601 = '140601'; // Instalação e montagem de aparelhos, máquinas e equipamentos, inclusive montagem industrial, prest...
    case S140701 = '140701'; // Colocação de molduras e congêneres.
    case S140801 = '140801'; // Encadernação, gravação e douração de livros, revistas e congêneres.
    case S140901 = '140901'; // Alfaiataria e costura, quando o material for fornecido pelo usuário final, exceto aviamento.
    case S141001 = '141001'; // Tinturaria e lavanderia.
    case S141101 = '141101'; // Tapeçaria e reforma de estofamentos em geral.
    case S141201 = '141201'; // Funilaria e lanternagem.
    case S141301 = '141301'; // Carpintaria.
    case S141302 = '141302'; // Serralheria.
    case S141401 = '141401'; // Guincho intramunicipal.
    case S141402 = '141402'; // Guindaste e içamento.
    case S150101 = '150101'; // Administração de fundos quaisquer e congêneres.
    case S150102 = '150102'; // Administração de consórcio e congêneres.
    case S150103 = '150103'; // Administração de cartão de crédito ou débito e congêneres.
    case S150104 = '150104'; // Administração de carteira de clientes e congêneres.
    case S150105 = '150105'; // Administração de cheques pré-datados e congêneres.
    case S150201 = '150201'; // Abertura de conta-corrente no País, bem como a manutenção da referida conta ativa e inativa.
    case S150202 = '150202'; // Abertura de conta-corrente no exterior, bem como a manutenção da referida conta ativa e inativa.
    case S150203 = '150203'; // Abertura de conta de investimentos e aplicação no País, bem como a manutenção da referida conta a...
    case S150204 = '150204'; // Abertura de conta de investimentos e aplicação no exterior, bem como a manutenção da referida con...
    case S150205 = '150205'; // Abertura de caderneta de poupança no País, bem como a manutenção da referida conta ativa e inativa.
    case S150206 = '150206'; // Abertura de caderneta de poupança no exterior, bem como a manutenção da referida conta ativa e in...
    case S150207 = '150207'; // Abertura de contas em geral no País, não abrangida em outro subitem, bem como a manutenção das re...
    case S150208 = '150208'; // Abertura de contas em geral no exterior, não abrangida em outro subitem, bem como a manutenção da...
    case S150301 = '150301'; // Locação de cofres particulares.
    case S150302 = '150302'; // Manutenção de cofres particulares.
    case S150303 = '150303'; // Locação de terminais eletrônicos.
    case S150304 = '150304'; // Manutenção de terminais eletrônicos.
    case S150305 = '150305'; // Locação de terminais de atendimento.
    case S150306 = '150306'; // Manutenção de terminais de atendimento.
    case S150307 = '150307'; // Locação de bens e equipamentos em geral.
    case S150308 = '150308'; // Manutenção de bens e equipamentos em geral.
    case S150401 = '150401'; // Fornecimento ou emissão de atestados em geral, inclusive atestado de idoneidade, atestado de capa...
    case S150501 = '150501'; // Cadastro, elaboração de ficha cadastral, renovação cadastral e congêneres.
    case S150502 = '150502'; // Inclusão no Cadastro de Emitentes de Cheques sem Fundos - CCF.
    case S150503 = '150503'; // Exclusão no Cadastro de Emitentes de Cheques sem Fundos - CCF.
    case S150504 = '150504'; // Inclusão em quaisquer outros bancos cadastrais.
    case S150505 = '150505'; // Exclusão em quaisquer outros bancos cadastrais.
    case S150601 = '150601'; // Emissão, reemissão e fornecimento de avisos, comprovantes e documentos em geral
    case S150602 = '150602'; // Abono de firmas.
    case S150603 = '150603'; // Coleta e entrega de documentos, bens e valores.
    case S150604 = '150604'; // Comunicação com outra agência ou com a administração central.
    case S150605 = '150605'; // Licenciamento eletrônico de veículos.
    case S150606 = '150606'; // Transferência de veículos.
    case S150607 = '150607'; // Agenciamento fiduciário ou depositário.
    case S150608 = '150608'; // Devolução de bens em custódia.
    case S150701 = '150701'; // Acesso, movimentação, atendimento e consulta a contas em geral, por qualquer meio ou processo, in...
    case S150702 = '150702'; // Acesso a terminais de atendimento, inclusive vinte e quatro horas.
    case S150703 = '150703'; // Acesso a outro banco e à rede compartilhada.
    case S150704 = '150704'; // Fornecimento de saldo, extrato e demais informações relativas a contas em geral, por qualquer mei...
    case S150801 = '150801'; // Emissão, reemissão, alteração, cessão, substituição, cancelamento e registro de contrato de crédito.
    case S150802 = '150802'; // Estudo, análise e avaliação de operações de crédito.
    case S150803 = '150803'; // Emissão, concessão, alteração ou contratação de aval, fiança, anuência e congêneres.
    case S150804 = '150804'; // Serviços relativos à abertura de crédito, para quaisquer fins.
    case S150901 = '150901'; // Arrendamento mercantil (leasing) de quaisquer bens, inclusive cessão de direitos e obrigações, su...
    case S151001 = '151001'; // Serviços relacionados a cobranças em geral, de títulos quaisquer, de contas ou carnês, de câmbio,...
    case S151002 = '151002'; // Serviços relacionados a recebimentos em geral, de títulos quaisquer, de contas ou carnês, de câmb...
    case S151003 = '151003'; // Serviços relacionados a pagamentos em geral, de títulos quaisquer, de contas ou carnês, de câmbio...
    case S151004 = '151004'; // Serviços relacionados a fornecimento de posição de cobrança, recebimento ou pagamento.
    case S151005 = '151005'; // Serviços relacionados a emissão de carnês, fichas de compensação, impressos e documentos em geral.
    case S151101 = '151101'; // Devolução de títulos, protesto de títulos, sustação de protesto, manutenção de títulos, reapresen...
    case S151201 = '151201'; // Custódia em geral, inclusive de títulos e valores mobiliários.
    case S151301 = '151301'; // Serviços relacionados a operações de câmbio em geral, edição, alteração, prorrogação, cancelament...
    case S151302 = '151302'; // Serviços relacionados a emissão de registro de exportação ou de crédito.
    case S151303 = '151303'; // Serviços relacionados a cobrança ou depósito no exterior.
    case S151304 = '151304'; // Serviços relacionados a emissão, fornecimento e cancelamento de cheques de viagem.
    case S151305 = '151305'; // Serviços relacionados a fornecimento, transferência, cancelamento e demais serviços relativos a c...
    case S151306 = '151306'; // Serviços relacionados a envio e recebimento de mensagens em geral relacionadas a operações de câm...
    case S151401 = '151401'; // Fornecimento, emissão, reemissão de cartão magnético, cartão de crédito, cartão de débito, cartão...
    case S151402 = '151402'; // Renovação de cartão magnético, cartão de crédito, cartão de débito, cartão salário e congêneres.
    case S151403 = '151403'; // Manutenção de cartão magnético, cartão de crédito, cartão de débito, cartão salário e congêneres.
    case S151501 = '151501'; // Compensação de cheques e títulos quaisquer.
    case S151502 = '151502'; // Serviços relacionados a depósito, inclusive depósito identificado, a saque de contas quaisquer, p...
    case S151601 = '151601'; // Emissão, reemissão, liquidação, alteração, cancelamento e baixa de ordens de pagamento, ordens de...
    case S151602 = '151602'; // Serviços relacionados à transferência de valores, dados, fundos, pagamentos e similares, inclusiv...
    case S151701 = '151701'; // Emissão e fornecimento de cheques quaisquer, avulso ou por talão.
    case S151702 = '151702'; // Devolução de cheques quaisquer, avulso ou por talão.
    case S151703 = '151703'; // Sustação, cancelamento e oposição de cheques quaisquer, avulso ou por talão.
    case S151801 = '151801'; // Serviços relacionados a crédito imobiliário, de avaliação e vistoria de imóvel ou obra.
    case S151802 = '151802'; // Serviços relacionados a crédito imobiliário, de análise técnica e jurídica.
    case S151803 = '151803'; // Serviços relacionados a crédito imobiliário, de emissão, reemissão, alteração, transferência e re...
    case S151804 = '151804'; // Serviços relacionados a crédito imobiliário, de emissão e reemissão do termo de quitação.
    case S151805 = '151805'; // Demais serviços relacionados a crédito imobiliário.
    case S160101 = '160101'; // Serviços de transporte coletivo municipal rodoviário de passageiros.
    case S160102 = '160102'; // Serviços de transporte coletivo municipal metroviário de passageiros.
    case S160103 = '160103'; // Serviços de transporte coletivo municipal ferroviário de passageiros.
    case S160104 = '160104'; // Serviços de transporte coletivo municipal aquaviário de passageiros.
    case S160201 = '160201'; // Outros serviços de transporte de natureza municipal.
    case S170101 = '170101'; // Assessoria ou consultoria de qualquer natureza, não contida em outros itens desta lista.
    case S170102 = '170102'; // Análise, exame, pesquisa, coleta, compilação e fornecimento de dados e informações de qualquer na...
    case S170201 = '170201'; // Datilografia, digitação, estenografia e congêneres.
    case S170202 = '170202'; // Expediente, secretaria em geral, apoio e infra-estrutura administrativa e congêneres.
    case S170203 = '170203'; // Resposta audível e congêneres.
    case S170204 = '170204'; // Redação, edição, revisão e congêneres.
    case S170205 = '170205'; // Interpretação, tradução e congêneres.
    case S170301 = '170301'; // Planejamento, coordenação, programação ou organização técnica.
    case S170302 = '170302'; // Planejamento, coordenação, programação ou organização financeira.
    case S170303 = '170303'; // Planejamento, coordenação, programação ou organização administrativa.
    case S170401 = '170401'; // Recrutamento, agenciamento, seleção e colocação de mão-de-obra.
    case S170501 = '170501'; // Fornecimento de mão-de-obra, mesmo em caráter temporário, inclusive de empregados ou trabalhadore...
    case S170601 = '170601'; // Propaganda e publicidade, inclusive promoção de vendas, planejamento de campanhas ou sistemas de ...
    case S170801 = '170801'; // Franquia (franchising).
    case S170901 = '170901'; // Perícias, laudos, exames técnicos e análises técnicas.
    case S171001 = '171001'; // Planejamento, organização e administração de feiras, exposições, e congêneres.
    case S171002 = '171002'; // Planejamento, organização e administração de congressos e congêneres.
    case S171101 = '171101'; // Organização de festas e recepções.
    case S171102 = '171102'; // Bufê (exceto o fornecimento de alimentação e bebidas, que fica sujeito ao ICMS).
    case S171201 = '171201'; // Administração em geral, inclusive de bens e negócios de terceiros.
    case S171301 = '171301'; // Leilão e congêneres.
    case S171401 = '171401'; // Advocacia
    case S171501 = '171501'; // Arbitragem de qualquer espécie, inclusive jurídica.
    case S171601 = '171601'; // Auditoria.
    case S171701 = '171701'; // Análise de Organização e Métodos.
    case S171801 = '171801'; // Atuária e cálculos técnicos de qualquer natureza.
    case S171901 = '171901'; // Contabilidade, inclusive serviços técnicos e auxiliares.
    case S172001 = '172001'; // Consultoria e assessoria econômica ou financeira.
    case S172101 = '172101'; // Estatística.
    case S172201 = '172201'; // Cobrança em geral.
    case S172301 = '172301'; // Assessoria, análise, avaliação, atendimento, consulta, cadastro, seleção, gerenciamento de inform...
    case S172401 = '172401'; // Apresentação de palestras, conferências, seminários e congêneres.
    case S172501 = '172501'; // Inserção de textos, desenhos e outros materiais de propaganda e publicidade, em qualquer meio (ex...
    case S180101 = '180101'; // Serviços de regulação de sinistros vinculados a contratos de seguros e congêneres.
    case S180102 = '180102'; // Serviços de inspeção e avaliação de riscos para cobertura de contratos de seguros e congêneres.
    case S180103 = '180103'; // Serviços de prevenção e gerência de riscos seguráveis e congêneres.
    case S190101 = '190101'; // Serviços de distribuição e venda de bilhetes e demais produtos de loteria, cartões, pules ou cupo...
    case S190102 = '190102'; // Serviços de distribuição e venda de bingos e congêneres.
    case S200101 = '200101'; // Serviços portuários, ferroportuários, utilização de porto, movimentação de passageiros, reboque d...
    case S200201 = '200201'; // Serviços aeroportuários, utilização de aeroporto, movimentação de passageiros, armazenagem de qua...
    case S200301 = '200301'; // Serviços de terminais rodoviários, ferroviários, metroviários, movimentação de passageiros, merca...
    case S210101 = '210101'; // Serviços de registros públicos, cartorários e notariais.
    case S220101 = '220101'; // Serviços de exploração de rodovia mediante cobrança de preço ou pedágio dos usuários, envolvendo ...
    case S230101 = '230101'; // Serviços de programação e comunicação visual e congêneres.
    case S230102 = '230102'; // Serviços de desenho industrial e congêneres.
    case S240101 = '240101'; // Serviços de chaveiros, confecção de carimbos e congêneres.
    case S240102 = '240102'; // Serviços de placas, sinalização visual, banners, adesivos e congêneres.
    case S250101 = '250101'; // Funerais, inclusive fornecimento de caixão, urna ou esquifes; aluguel de capela; transporte do co...
    case S250201 = '250201'; // Translado intramunicipal de corpos e partes de corpos cadavéricos.
    case S250202 = '250202'; // Cremação de corpos e partes de corpos cadavéricos.
    case S250301 = '250301'; // Planos ou convênio funerários.
    case S250401 = '250401'; // Manutenção e conservação de jazigos e cemitérios.
    case S250501 = '250501'; // Cessão de uso de espaços em cemitérios para sepultamento.
    case S260101 = '260101'; // Serviços de coleta, remessa ou entrega de correspondências, documentos, objetos, bens ou valores,...
    case S260102 = '260102'; // Serviços de courrier e congêneres.
    case S270101 = '270101'; // Serviços de assistência social.
    case S280101 = '280101'; // Serviços de avaliação de bens e serviços de qualquer natureza.
    case S290101 = '290101'; // Serviços de biblioteconomia.
    case S300101 = '300101'; // Serviços de biologia e biotecnologia.
    case S300102 = '300102'; // Serviços de química.
    case S310101 = '310101'; // Serviços técnicos em edificações e congêneres.
    case S310102 = '310102'; // Serviços técnicos em eletrônica, eletrotécnica e congêneres.
    case S310103 = '310103'; // Serviços técnicos em mecânica e congêneres.
    case S310104 = '310104'; // Serviços técnicos em telecomunicações e congêneres.
    case S320101 = '320101'; // Serviços de desenhos técnicos.
    case S330101 = '330101'; // Serviços de desembaraço aduaneiro, comissários, despachantes e congêneres.
    case S340101 = '340101'; // Serviços de investigações particulares, detetives e congêneres.
    case S350101 = '350101'; // Serviços de reportagem e jornalismo.
    case S350102 = '350102'; // Serviços de assessoria de imprensa.
    case S350103 = '350103'; // Serviços de relações públicas.
    case S360101 = '360101'; // Serviços de meteorologia.
    case S370101 = '370101'; // Serviços de artistas, atletas, modelos e manequins.
    case S380101 = '380101'; // Serviços de museologia.
    case S390101 = '390101'; // Serviços de ourivesaria e lapidação (quando o material for fornecido pelo tomador do serviço).
    case S400101 = '400101'; // Obras de arte sob encomenda.
    case S990101 = '990101'; // Serviços sem a incidência de ISSQN e ICMS

    /**
     * Retorna a descrição do serviço
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::S010101 => 'Análise e desenvolvimento de sistemas.',
            self::S010201 => 'Programação.',
            self::S010301 => 'Processamento de dados, textos, imagens, vídeos, páginas eletrônicas, aplicativos e sistemas de informação, entre outros formatos, e congêneres.',
            self::S010302 => 'Armazenamento ou hospedagem de dados, textos, imagens, vídeos, páginas eletrônicas, aplicativos e sistemas de informação, entre outros formatos, e congêneres.',
            self::S010401 => 'Elaboração de programas de computadores, inclusive de jogos eletrônicos, independentemente da arquitetura construtiva da máquina em que o programa será executado, incluindo tablets, smartphones e congêneres.',
            self::S010501 => 'Licenciamento ou cessão de direito de uso de programas de computação.',
            self::S010601 => 'Assessoria e consultoria em informática.',
            self::S010701 => 'Suporte técnico em informática, inclusive instalação, configuração e manutenção de programas de computação e bancos de dados.',
            self::S010801 => 'Planejamento, confecção, manutenção e atualização de páginas eletrônicas.',
            self::S010901 => 'Disponibilização, sem cessão definitiva, de conteúdos de áudio por meio da internet (exceto a distribuição de conteúdos pelas prestadoras de Serviço de Acesso Condicionado, de que trata a Lei nº 12.485, de 12 de setembro de 2011, sujeita ao ICMS).',
            self::S010902 => 'Disponibilização, sem cessão definitiva, de conteúdos de vídeo, imagem e texto por meio da internet, respeitada a imunidade de livros, jornais e periódicos (exceto a distribuição de conteúdos pelas prestadoras de Serviço de Acesso Condicionado, de que trata a Lei nº 12.485, de 12 de setembro de 2011, sujeita ao ICMS).',
            self::S020101 => 'Serviços de pesquisas e desenvolvimento de qualquer natureza.',
            self::S030201 => 'Cessão de direito de uso de marcas e de sinais de propaganda.',
            self::S030301 => 'Exploração de salões de festas, centro de convenções, stands e congêneres, para realização de eventos ou negócios de qualquer natureza.',
            self::S030302 => 'Exploração de escritórios virtuais e congêneres, para realização de eventos ou negócios de qualquer natureza.',
            self::S030303 => 'Exploração de quadras esportivas, estádios, ginásios, canchas e congêneres, para realização de eventos ou negócios de qualquer natureza.',
            self::S030304 => 'Exploração de auditórios, casas de espetáculos e congêneres, para realização de eventos ou negócios de qualquer natureza.',
            self::S030305 => 'Exploração de parques de diversões e congêneres, para realização de eventos ou negócios de qualquer natureza.',
            self::S030401 => 'Locação, sublocação, arrendamento, direito de passagem ou permissão de uso, compartilhado ou não, de ferrovia.',
            self::S030402 => 'Locação, sublocação, arrendamento, direito de passagem ou permissão de uso, compartilhado ou não, de rodovia.',
            self::S030403 => 'Locação, sublocação, arrendamento, direito de passagem ou permissão de uso, compartilhado ou não, de postes, cabos, dutos e condutos de qualquer natureza.',
            self::S030501 => 'Cessão de andaimes, palcos, coberturas e outras estruturas de uso temporário.',
            self::S040101 => 'Medicina.',
            self::S040102 => 'Biomedicina.',
            self::S040201 => 'Análises clínicas e congêneres.',
            self::S040202 => 'Patologia e congêneres.',
            self::S040203 => 'Eletricidade médica (eletroestimulação de nervos e musculos, cardioversão, etc) e congêneres.',
            self::S040204 => 'Radioterapia, quimioterapia e congêneres.',
            self::S040205 => 'Ultra-sonografia, ressonância magnética, radiologia, tomografia e congêneres.',
            self::S040301 => 'Hospitais e congêneres.',
            self::S040302 => 'Laboratórios e congêneres.',
            self::S040303 => 'Clínicas, sanatórios, manicômios, casas de saúde, prontos-socorros, ambulatórios e congêneres.',
            self::S040401 => 'Instrumentação cirúrgica.',
            self::S040501 => 'Acupuntura.',
            self::S040601 => 'Enfermagem, inclusive serviços auxiliares.',
            self::S040701 => 'Serviços farmacêuticos.',
            self::S040801 => 'Terapia ocupacional.',
            self::S040802 => 'Fisioterapia.',
            self::S040803 => 'Fonoaudiologia.',
            self::S040901 => 'Terapias de qualquer espécie destinadas ao tratamento físico, orgânico e mental.',
            self::S041001 => 'Nutrição.',
            self::S041101 => 'Obstetrícia.',
            self::S041201 => 'Odontologia.',
            self::S041301 => 'Ortóptica.',
            self::S041401 => 'Próteses sob encomenda.',
            self::S041501 => 'Psicanálise.',
            self::S041601 => 'Psicologia.',
            self::S041701 => 'Casas de repouso e congêneres.',
            self::S041702 => 'Casas de recuperação e congêneres.',
            self::S041703 => 'Creches e congêneres.',
            self::S041704 => 'Asilos e congêneres.',
            self::S041801 => 'Inseminação artificial, fertilização in vitro e congêneres.',
            self::S041901 => 'Bancos de sangue, leite, pele, olhos, óvulos, sêmen e congêneres.',
            self::S042001 => 'Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos de qualquer espécie.',
            self::S042101 => 'Unidade de atendimento, assistência ou tratamento móvel e congêneres.',
            self::S042201 => 'Planos de medicina de grupo ou individual e convênios para prestação de assistência médica, hospitalar, odontológica e congêneres.',
            self::S042301 => 'Outros planos de saúde que se cumpram através de serviços de terceiros contratados, credenciados, cooperados ou apenas pagos pelo operador do plano mediante indicação do beneficiário.',
            self::S050101 => 'Medicina veterinária',
            self::S050102 => 'Zootecnia.',
            self::S050201 => 'Hospitais e congêneres, na área veterinária.',
            self::S050202 => 'Clínicas, ambulatórios, prontos-socorros e congêneres, na área veterinária.',
            self::S050301 => 'Laboratórios de análise na área veterinária.',
            self::S050401 => 'Inseminação artificial, fertilização in vitro e congêneres.',
            self::S050501 => 'Bancos de sangue e de órgãos e congêneres.',
            self::S050601 => 'Coleta de sangue, leite, tecidos, sêmen, órgãos e materiais biológicos de qualquer espécie.',
            self::S050701 => 'Unidade de atendimento, assistência ou tratamento móvel e congêneres.',
            self::S050801 => 'Guarda, tratamento, amestramento, embelezamento, alojamento e congêneres.',
            self::S050901 => 'Planos de atendimento e assistência médico-veterinária.',
            self::S060101 => 'Barbearia, cabeleireiros, manicuros, pedicuros e congêneres.',
            self::S060201 => 'Esteticistas, tratamento de pele, depilação e congêneres.',
            self::S060301 => 'Banhos, duchas, sauna, massagens e congêneres.',
            self::S060401 => 'Ginástica, dança, esportes, natação, artes marciais e demais atividades físicas.',
            self::S060501 => 'Centros de emagrecimento, spa e congêneres.',
            self::S060601 => 'Aplicação de tatuagens, piercings e congêneres.',
            self::S070101 => 'Engenharia e congêneres.',
            self::S070102 => 'Agronomia e congêneres.',
            self::S070103 => 'Agrimensura e congêneres.',
            self::S070104 => 'Arquitetura, urbanismo e congêneres.',
            self::S070105 => 'Geologia e congêneres.',
            self::S070106 => 'Paisagismo e congêneres.',
            self::S070201 => 'Execução, por administração, de obras de construção civil, hidráulica ou elétrica e de outras obras semelhantes, inclusive sondagem, perfuração de poços, escavação, drenagem e irrigação, terraplanagem, pavimentação, concretagem e a instalação e montagem de produtos, peças e equipamentos (exceto o fornecimento de mercadorias produzidas pelo prestador de serviços fora do local da prestação dos serviços, que fica sujeito ao ICMS).',
            self::S070202 => 'Execução, por empreitada ou subempreitada, de obras de construção civil, hidráulica ou elétrica e de outras obras semelhantes, inclusive sondagem, perfuração de poços, escavação, drenagem e irrigação, terraplanagem, pavimentação, concretagem e a instalação e montagem de produtos, peças e equipamentos (exceto o fornecimento de mercadorias produzidas pelo prestador de serviços fora do local da prestação dos serviços, que fica sujeito ao ICMS).',
            self::S070301 => 'Elaboração de planos diretores, estudos de viabilidade, estudos organizacionais e outros, relacionados com obras e serviços de engenharia.',
            self::S070302 => 'Elaboração de anteprojetos, projetos básicos e projetos executivos para trabalhos de engenharia.',
            self::S070401 => 'Demolição.',
            self::S070501 => 'Reparação, conservação e reforma de edifícios e congêneres (exceto o fornecimento de mercadorias produzidas pelo prestador dos serviços, fora do local da prestação dos serviços, que fica sujeito ao ICMS).',
            self::S070502 => 'Reparação, conservação e reforma de estradas, pontes, portos e congêneres (exceto o fornecimento de mercadorias produzidas pelo prestador dos serviços, fora do local da prestação dos serviços, que fica sujeito ao ICMS).',
            self::S070601 => 'Colocação e instalação de tapetes, carpetes, cortinas e congêneres, com material fornecido pelo tomador do serviço.',
            self::S070602 => 'Colocação e instalação de assoalhos, revestimentos de parede, vidros, divisórias, placas de gesso e congêneres, com material fornecido pelo tomador do serviço.',
            self::S070701 => 'Recuperação, raspagem, polimento e lustração de pisos e congêneres.',
            self::S070801 => 'Calafetação.',
            self::S070901 => 'Varrição, coleta e remoção de lixo, rejeitos e outros resíduos quaisquer.',
            self::S070902 => 'Incineração, tratamento, reciclagem, separação e destinação final de lixo, rejeitos e outros resíduos quaisquer.',
            self::S071001 => 'Limpeza, manutenção e conservação de vias e logradouros públicos, parques, jardins e congêneres.',
            self::S071002 => 'Limpeza, manutenção e conservação de imóveis, chaminés, piscinas e congêneres.',
            self::S071101 => 'Decoração.',
            self::S071102 => 'Jardinagem, inclusive corte e poda de árvores.',
            self::S071201 => 'Controle e tratamento de efluentes de qualquer natureza e de agentes físicos, químicos e biológicos.',
            self::S071301 => 'Dedetização, desinfecção, desinsetização, imunização, higienização, desratização, pulverização e congêneres.',
            self::S071601 => 'Florestamento, reflorestamento, semeadura, adubação, reparação de solo, plantio, silagem, colheita, corte e descascamento de árvores, silvicultura, exploração florestal e dos serviços congêneres indissociáveis da formação, manutenção e colheita de florestas, para quaisquer fins e por quaisquer meios.',
            self::S071701 => 'Escoramento, contenção de encostas e serviços congêneres.',
            self::S071801 => 'Limpeza e dragagem de rios, portos, canais, baías, lagos, lagoas, represas, açudes e congêneres.',
            self::S071901 => 'Acompanhamento e fiscalização da execução de obras de engenharia, arquitetura e urbanismo.',
            self::S072001 => 'Aerofotogrametria (inclusive interpretação), cartografia, mapeamento e congêneres.',
            self::S072002 => 'Levantamentos batimétricos, geográficos, geodésicos, geológicos, geofísicos e congêneres.',
            self::S072003 => 'Levantamentos topográficos e congêneres.',
            self::S072101 => 'Pesquisa, perfuração, cimentação, mergulho, perfilagem, concretação, testemunhagem, pescaria, estimulação e outros serviços relacionados com a exploração e explotação de petróleo, gás natural e de outros recursos minerais.',
            self::S072201 => 'Nucleação e bombardeamento de nuvens e congêneres.',
            self::S080101 => 'Ensino regular pré-escolar, fundamental e médio.',
            self::S080102 => 'Ensino regular superior.',
            self::S080201 => 'Instrução, treinamento, orientação pedagógica e educacional, avaliação de conhecimentos de qualquer natureza.',
            self::S090101 => 'Hospedagem em hotéis, hotelaria marítima e congêneres (o valor da alimentação e gorjeta, quando incluído no preço da diária, fica sujeito ao Imposto Sobre Serviços).',
            self::S090102 => 'Hospedagem em pensões, albergues, pousadas, hospedarias, ocupação por temporada com fornecimento de serviços e congêneres (o valor da alimentação e gorjeta, quando incluído no preço da diária, fica sujeito ao Imposto Sobre Serviços).',
            self::S090103 => 'Hospedagem em motéis e congêneres (o valor da alimentação e gorjeta, quando incluído no preço da diária, fica sujeito ao Imposto Sobre Serviços).',
            self::S090104 => 'Hospedagem em apart-service condominiais, flat, apart-hotéis, hotéis residência, residence-service, suite service e congêneres (o valor da alimentação e gorjeta, quando incluído no preço da diária, fica sujeito ao Imposto Sobre Serviços).',
            self::S090201 => 'Agenciamento e intermediação de programas de turismo, passeios, viagens, excursões, hospedagens e congêneres.',
            self::S090202 => 'Organização, promoção e execução de programas de turismo, passeios, viagens, excursões, hospedagens e congêneres.',
            self::S090301 => 'Guias de turismo.',
            self::S100101 => 'Agenciamento, corretagem ou intermediação de câmbio.',
            self::S100102 => 'Agenciamento, corretagem ou intermediação de seguros.',
            self::S100103 => 'Agenciamento, corretagem ou intermediação de cartões de crédito.',
            self::S100104 => 'Agenciamento, corretagem ou intermediação de planos de saúde.',
            self::S100105 => 'Agenciamento, corretagem ou intermediação de planos de previdência privada.',
            self::S100201 => 'Agenciamento, corretagem ou intermediação de títulos em geral e valores mobiliários.',
            self::S100202 => 'Agenciamento, corretagem ou intermediação de contratos quaisquer.',
            self::S100301 => 'Agenciamento, corretagem ou intermediação de direitos de propriedade industrial, artística ou literária.',
            self::S100401 => 'Agenciamento, corretagem ou intermediação de contratos de arrendamento mercantil (leasing).',
            self::S100402 => 'Agenciamento, corretagem ou intermediação de contratos de franquia (franchising).',
            self::S100403 => 'Agenciamento, corretagem ou intermediação de faturização (factoring).',
            self::S100501 => 'Agenciamento, corretagem ou intermediação de bens móveis ou imóveis, não abrangidos em outros itens ou subitens, por quaisquer meios.',
            self::S100502 => 'Agenciamento, corretagem ou intermediação de bens móveis ou imóveis realizados no âmbito de Bolsas de Mercadorias e Futuros, por quaisquer meios.',
            self::S100601 => 'Agenciamento marítimo.',
            self::S100701 => 'Agenciamento de notícias.',
            self::S100801 => 'Agenciamento de publicidade e propaganda, inclusive o agenciamento de veiculação por quaisquer meios.',
            self::S100901 => 'Representação de qualquer natureza, inclusive comercial.',
            self::S101001 => 'Distribuição de bens de terceiros.',
            self::S110101 => 'Guarda e estacionamento de veículos terrestres automotores.',
            self::S110102 => 'Guarda e estacionamento de aeronaves e de embarcações.',
            self::S110201 => 'Vigilância, segurança ou monitoramento de bens, pessoas e semoventes.',
            self::S110301 => 'Escolta, inclusive de veículos e cargas.',
            self::S110401 => 'Armazenamento, depósito, guarda de bens de qualquer espécie.',
            self::S110402 => 'Carga, descarga, arrumação de bens de qualquer espécie.',
            self::S110501 => 'Serviços relacionados ao monitoramento e rastreamento a distância, em qualquer via ou local, de veículos, cargas, pessoas e semoventes em circulação ou movimento, realizados por meio de telefonia móvel, transmissão de satélites, rádio ou qualquer outro meio, inclusive pelas empresas de Tecnologia da Informação Veicular, independentemente de o prestador de serviços ser proprietário ou não da infraestrutura de telecomunicações que utiliza.',
            self::S120101 => 'Espetáculos teatrais.',
            self::S120201 => 'Exibições cinematográficas.',
            self::S120301 => 'Espetáculos circenses.',
            self::S120401 => 'Programas de auditório.',
            self::S120501 => 'Parques de diversões, centros de lazer e congêneres.',
            self::S120601 => 'Boates, taxi-dancing e congêneres.',
            self::S120701 => 'Shows, ballet, danças, desfiles, bailes, óperas, concertos, recitais, festivais e congêneres.',
            self::S120801 => 'Feiras, exposições, congressos e congêneres.',
            self::S120901 => 'Bilhares.',
            self::S120902 => 'Boliches.',
            self::S120903 => 'Diversões eletrônicas ou não.',
            self::S121001 => 'Corridas e competições de animais.',
            self::S121101 => 'Competições esportivas ou de destreza física ou intelectual, com ou sem a participação do espectador.',
            self::S121201 => 'Execução de música.',
            self::S121301 => 'Produção, mediante ou sem encomenda prévia, de eventos, espetáculos, entrevistas, shows, ballet, danças, desfiles, bailes, teatros, óperas, concertos, recitais, festivais e congêneres.',
            self::S121401 => 'Fornecimento de música para ambientes fechados ou não, mediante transmissão por qualquer processo.',
            self::S121501 => 'Desfiles de blocos carnavalescos ou folclóricos, trios elétricos e congêneres.',
            self::S121601 => 'Exibição de filmes, entrevistas, musicais, espetáculos, shows, concertos, desfiles, óperas, competições esportivas, de destreza intelectual ou congêneres.',
            self::S121701 => 'Recreação e animação, inclusive em festas e eventos de qualquer natureza.',
            self::S130201 => 'Fonografia ou gravação de sons, inclusive trucagem, dublagem, mixagem e congêneres.',
            self::S130301 => 'Fotografia e cinematografia, inclusive revelação, ampliação, cópia, reprodução, trucagem e congêneres.',
            self::S130401 => 'Reprografia, microfilmagem e digitalização.',
            self::S130501 => 'Composição gráfica, inclusive confecção de impressos gráficos, fotocomposição, clicheria, zincografia, litografia e fotolitografia, exceto se destinados a posterior operação de comercialização ou industrialização, ainda que incorporados, de qualquer forma, a outra mercadoria que deva ser objeto de posterior circulação, tais como bulas, rótulos, etiquetas, caixas, cartuchos, embalagens e manuais técnicos e de instrução, quando ficarão sujeitos ao ICMS.',
            self::S140101 => 'Lubrificação, limpeza, lustração, revisão, carga e recarga, conserto, restauração, blindagem, manutenção e conservação de máquinas, veículos, aparelhos, equipamentos, motores, elevadores ou de qualquer objeto (exceto peças e partes empregadas, que ficam sujeitas ao ICMS).',
            self::S140201 => 'Assistência técnica.',
            self::S140301 => 'Recondicionamento de motores (exceto peças e partes empregadas, que ficam sujeitas ao ICMS).',
            self::S140401 => 'Recauchutagem ou regeneração de pneus.',
            self::S140501 => 'Restauração, recondicionamento, acondicionamento, pintura, beneficiamento, lavagem, secagem, tingimento, galvanoplastia, anodização, corte, recorte, plastificação, costura, acabamento, polimento e congêneres de objetos quaisquer.',
            self::S140601 => 'Instalação e montagem de aparelhos, máquinas e equipamentos, inclusive montagem industrial, prestados ao usuário final, exclusivamente com material por ele fornecido.',
            self::S140701 => 'Colocação de molduras e congêneres.',
            self::S140801 => 'Encadernação, gravação e douração de livros, revistas e congêneres.',
            self::S140901 => 'Alfaiataria e costura, quando o material for fornecido pelo usuário final, exceto aviamento.',
            self::S141001 => 'Tinturaria e lavanderia.',
            self::S141101 => 'Tapeçaria e reforma de estofamentos em geral.',
            self::S141201 => 'Funilaria e lanternagem.',
            self::S141301 => 'Carpintaria.',
            self::S141302 => 'Serralheria.',
            self::S141401 => 'Guincho intramunicipal.',
            self::S141402 => 'Guindaste e içamento.',
            self::S150101 => 'Administração de fundos quaisquer e congêneres.',
            self::S150102 => 'Administração de consórcio e congêneres.',
            self::S150103 => 'Administração de cartão de crédito ou débito e congêneres.',
            self::S150104 => 'Administração de carteira de clientes e congêneres.',
            self::S150105 => 'Administração de cheques pré-datados e congêneres.',
            self::S150201 => 'Abertura de conta-corrente no País, bem como a manutenção da referida conta ativa e inativa.',
            self::S150202 => 'Abertura de conta-corrente no exterior, bem como a manutenção da referida conta ativa e inativa.',
            self::S150203 => 'Abertura de conta de investimentos e aplicação no País, bem como a manutenção da referida conta ativa e inativa.',
            self::S150204 => 'Abertura de conta de investimentos e aplicação no exterior, bem como a manutenção da referida conta ativa e inativa.',
            self::S150205 => 'Abertura de caderneta de poupança no País, bem como a manutenção da referida conta ativa e inativa.',
            self::S150206 => 'Abertura de caderneta de poupança no exterior, bem como a manutenção da referida conta ativa e inativa.',
            self::S150207 => 'Abertura de contas em geral no País, não abrangida em outro subitem, bem como a manutenção das referidas contas ativas e inativas.',
            self::S150208 => 'Abertura de contas em geral no exterior, não abrangida em outro subitem, bem como a manutenção das referidas contas ativas e inativas.',
            self::S150301 => 'Locação de cofres particulares.',
            self::S150302 => 'Manutenção de cofres particulares.',
            self::S150303 => 'Locação de terminais eletrônicos.',
            self::S150304 => 'Manutenção de terminais eletrônicos.',
            self::S150305 => 'Locação de terminais de atendimento.',
            self::S150306 => 'Manutenção de terminais de atendimento.',
            self::S150307 => 'Locação de bens e equipamentos em geral.',
            self::S150308 => 'Manutenção de bens e equipamentos em geral.',
            self::S150401 => 'Fornecimento ou emissão de atestados em geral, inclusive atestado de idoneidade, atestado de capacidade financeira e congêneres.',
            self::S150501 => 'Cadastro, elaboração de ficha cadastral, renovação cadastral e congêneres.',
            self::S150502 => 'Inclusão no Cadastro de Emitentes de Cheques sem Fundos - CCF.',
            self::S150503 => 'Exclusão no Cadastro de Emitentes de Cheques sem Fundos - CCF.',
            self::S150504 => 'Inclusão em quaisquer outros bancos cadastrais.',
            self::S150505 => 'Exclusão em quaisquer outros bancos cadastrais.',
            self::S150601 => 'Emissão, reemissão e fornecimento de avisos, comprovantes e documentos em geral',
            self::S150602 => 'Abono de firmas.',
            self::S150603 => 'Coleta e entrega de documentos, bens e valores.',
            self::S150604 => 'Comunicação com outra agência ou com a administração central.',
            self::S150605 => 'Licenciamento eletrônico de veículos.',
            self::S150606 => 'Transferência de veículos.',
            self::S150607 => 'Agenciamento fiduciário ou depositário.',
            self::S150608 => 'Devolução de bens em custódia.',
            self::S150701 => 'Acesso, movimentação, atendimento e consulta a contas em geral, por qualquer meio ou processo, inclusive por telefone, fac-símile, internet e telex.',
            self::S150702 => 'Acesso a terminais de atendimento, inclusive vinte e quatro horas.',
            self::S150703 => 'Acesso a outro banco e à rede compartilhada.',
            self::S150704 => 'Fornecimento de saldo, extrato e demais informações relativas a contas em geral, por qualquer meio ou processo.',
            self::S150801 => 'Emissão, reemissão, alteração, cessão, substituição, cancelamento e registro de contrato de crédito.',
            self::S150802 => 'Estudo, análise e avaliação de operações de crédito.',
            self::S150803 => 'Emissão, concessão, alteração ou contratação de aval, fiança, anuência e congêneres.',
            self::S150804 => 'Serviços relativos à abertura de crédito, para quaisquer fins.',
            self::S150901 => 'Arrendamento mercantil (leasing) de quaisquer bens, inclusive cessão de direitos e obrigações, substituição de garantia, alteração, cancelamento e registro de contrato, e demais serviços relacionados ao arrendamento mercantil (leasing).',
            self::S151001 => 'Serviços relacionados a cobranças em geral, de títulos quaisquer, de contas ou carnês, de câmbio, de tributos e por conta de terceiros, inclusive os efetuados por meio eletrônico, automático ou por máquinas de atendimento.',
            self::S151002 => 'Serviços relacionados a recebimentos em geral, de títulos quaisquer, de contas ou carnês, de câmbio, de tributos e por conta de terceiros, inclusive os efetuados por meio eletrônico, automático ou por máquinas de atendimento.',
            self::S151003 => 'Serviços relacionados a pagamentos em geral, de títulos quaisquer, de contas ou carnês, de câmbio, de tributos e por conta de terceiros, inclusive os efetuados por meio eletrônico, automático ou por máquinas de atendimento.',
            self::S151004 => 'Serviços relacionados a fornecimento de posição de cobrança, recebimento ou pagamento.',
            self::S151005 => 'Serviços relacionados a emissão de carnês, fichas de compensação, impressos e documentos em geral.',
            self::S151101 => 'Devolução de títulos, protesto de títulos, sustação de protesto, manutenção de títulos, reapresentação de títulos, e demais serviços a eles relacionados.',
            self::S151201 => 'Custódia em geral, inclusive de títulos e valores mobiliários.',
            self::S151301 => 'Serviços relacionados a operações de câmbio em geral, edição, alteração, prorrogação, cancelamento e baixa de contrato de câmbio.',
            self::S151302 => 'Serviços relacionados a emissão de registro de exportação ou de crédito.',
            self::S151303 => 'Serviços relacionados a cobrança ou depósito no exterior.',
            self::S151304 => 'Serviços relacionados a emissão, fornecimento e cancelamento de cheques de viagem.',
            self::S151305 => 'Serviços relacionados a fornecimento, transferência, cancelamento e demais serviços relativos a carta de crédito de importação, exportação e garantias recebidas.',
            self::S151306 => 'Serviços relacionados a envio e recebimento de mensagens em geral relacionadas a operações de câmbio.',
            self::S151401 => 'Fornecimento, emissão, reemissão de cartão magnético, cartão de crédito, cartão de débito, cartão salário e congêneres.',
            self::S151402 => 'Renovação de cartão magnético, cartão de crédito, cartão de débito, cartão salário e congêneres.',
            self::S151403 => 'Manutenção de cartão magnético, cartão de crédito, cartão de débito, cartão salário e congêneres.',
            self::S151501 => 'Compensação de cheques e títulos quaisquer.',
            self::S151502 => 'Serviços relacionados a depósito, inclusive depósito identificado, a saque de contas quaisquer, por qualquer meio ou processo, inclusive em terminais eletrônicos e de atendimento.',
            self::S151601 => 'Emissão, reemissão, liquidação, alteração, cancelamento e baixa de ordens de pagamento, ordens de crédito e similares, por qualquer meio ou processo.',
            self::S151602 => 'Serviços relacionados à transferência de valores, dados, fundos, pagamentos e similares, inclusive entre contas em geral.',
            self::S151701 => 'Emissão e fornecimento de cheques quaisquer, avulso ou por talão.',
            self::S151702 => 'Devolução de cheques quaisquer, avulso ou por talão.',
            self::S151703 => 'Sustação, cancelamento e oposição de cheques quaisquer, avulso ou por talão.',
            self::S151801 => 'Serviços relacionados a crédito imobiliário, de avaliação e vistoria de imóvel ou obra.',
            self::S151802 => 'Serviços relacionados a crédito imobiliário, de análise técnica e jurídica.',
            self::S151803 => 'Serviços relacionados a crédito imobiliário, de emissão, reemissão, alteração, transferência e renegociação de contrato.',
            self::S151804 => 'Serviços relacionados a crédito imobiliário, de emissão e reemissão do termo de quitação.',
            self::S151805 => 'Demais serviços relacionados a crédito imobiliário.',
            self::S160101 => 'Serviços de transporte coletivo municipal rodoviário de passageiros.',
            self::S160102 => 'Serviços de transporte coletivo municipal metroviário de passageiros.',
            self::S160103 => 'Serviços de transporte coletivo municipal ferroviário de passageiros.',
            self::S160104 => 'Serviços de transporte coletivo municipal aquaviário de passageiros.',
            self::S160201 => 'Outros serviços de transporte de natureza municipal.',
            self::S170101 => 'Assessoria ou consultoria de qualquer natureza, não contida em outros itens desta lista.',
            self::S170102 => 'Análise, exame, pesquisa, coleta, compilação e fornecimento de dados e informações de qualquer natureza, inclusive cadastro e similares.',
            self::S170201 => 'Datilografia, digitação, estenografia e congêneres.',
            self::S170202 => 'Expediente, secretaria em geral, apoio e infra-estrutura administrativa e congêneres.',
            self::S170203 => 'Resposta audível e congêneres.',
            self::S170204 => 'Redação, edição, revisão e congêneres.',
            self::S170205 => 'Interpretação, tradução e congêneres.',
            self::S170301 => 'Planejamento, coordenação, programação ou organização técnica.',
            self::S170302 => 'Planejamento, coordenação, programação ou organização financeira.',
            self::S170303 => 'Planejamento, coordenação, programação ou organização administrativa.',
            self::S170401 => 'Recrutamento, agenciamento, seleção e colocação de mão-de-obra.',
            self::S170501 => 'Fornecimento de mão-de-obra, mesmo em caráter temporário, inclusive de empregados ou trabalhadores, avulsos ou temporários, contratados pelo prestador de serviço.',
            self::S170601 => 'Propaganda e publicidade, inclusive promoção de vendas, planejamento de campanhas ou sistemas de publicidade, elaboração de desenhos, textos e demais materiais publicitários.',
            self::S170801 => 'Franquia (franchising).',
            self::S170901 => 'Perícias, laudos, exames técnicos e análises técnicas.',
            self::S171001 => 'Planejamento, organização e administração de feiras, exposições, e congêneres.',
            self::S171002 => 'Planejamento, organização e administração de congressos e congêneres.',
            self::S171101 => 'Organização de festas e recepções.',
            self::S171102 => 'Bufê (exceto o fornecimento de alimentação e bebidas, que fica sujeito ao ICMS).',
            self::S171201 => 'Administração em geral, inclusive de bens e negócios de terceiros.',
            self::S171301 => 'Leilão e congêneres.',
            self::S171401 => 'Advocacia',
            self::S171501 => 'Arbitragem de qualquer espécie, inclusive jurídica.',
            self::S171601 => 'Auditoria.',
            self::S171701 => 'Análise de Organização e Métodos.',
            self::S171801 => 'Atuária e cálculos técnicos de qualquer natureza.',
            self::S171901 => 'Contabilidade, inclusive serviços técnicos e auxiliares.',
            self::S172001 => 'Consultoria e assessoria econômica ou financeira.',
            self::S172101 => 'Estatística.',
            self::S172201 => 'Cobrança em geral.',
            self::S172301 => 'Assessoria, análise, avaliação, atendimento, consulta, cadastro, seleção, gerenciamento de informações, administração de contas a receber ou a pagar e em geral, relacionados a operações de faturização (factoring).',
            self::S172401 => 'Apresentação de palestras, conferências, seminários e congêneres.',
            self::S172501 => 'Inserção de textos, desenhos e outros materiais de propaganda e publicidade, em qualquer meio (exceto em livros, jornais, periódicos e nas modalidades de serviços de radiodifusão sonora e de sons e imagens de recepção livre e gratuita).',
            self::S180101 => 'Serviços de regulação de sinistros vinculados a contratos de seguros e congêneres.',
            self::S180102 => 'Serviços de inspeção e avaliação de riscos para cobertura de contratos de seguros e congêneres.',
            self::S180103 => 'Serviços de prevenção e gerência de riscos seguráveis e congêneres.',
            self::S190101 => 'Serviços de distribuição e venda de bilhetes e demais produtos de loteria, cartões, pules ou cupons de apostas, sorteios, prêmios, inclusive os decorrentes de títulos de capitalização e congêneres.',
            self::S190102 => 'Serviços de distribuição e venda de bingos e congêneres.',
            self::S200101 => 'Serviços portuários, ferroportuários, utilização de porto, movimentação de passageiros, reboque de embarcações, rebocador escoteiro, atracação, desatracação, serviços de praticagem, capatazia, armazenagem de qualquer natureza, serviços acessórios, movimentação de mercadorias, serviços de apoio marítimo, de movimentação ao largo, serviços de armadores, estiva, conferência, logística e congêneres.',
            self::S200201 => 'Serviços aeroportuários, utilização de aeroporto, movimentação de passageiros, armazenagem de qualquer natureza, capatazia, movimentação de aeronaves, serviços de apoio aeroportuários, serviços acessórios, movimentação de mercadorias, logística e congêneres.',
            self::S200301 => 'Serviços de terminais rodoviários, ferroviários, metroviários, movimentação de passageiros, mercadorias, inclusive suas operações, logística e congêneres.',
            self::S210101 => 'Serviços de registros públicos, cartorários e notariais.',
            self::S220101 => 'Serviços de exploração de rodovia mediante cobrança de preço ou pedágio dos usuários, envolvendo execução de serviços de conservação, manutenção, melhoramentos para adequação de capacidade e segurança de trânsito, operação, monitoração, assistência aos usuários e outros serviços definidos em contratos, atos de concessão ou de permissão ou em normas oficiais.',
            self::S230101 => 'Serviços de programação e comunicação visual e congêneres.',
            self::S230102 => 'Serviços de desenho industrial e congêneres.',
            self::S240101 => 'Serviços de chaveiros, confecção de carimbos e congêneres.',
            self::S240102 => 'Serviços de placas, sinalização visual, banners, adesivos e congêneres.',
            self::S250101 => 'Funerais, inclusive fornecimento de caixão, urna ou esquifes; aluguel de capela; transporte do corpo cadavérico; fornecimento de flores, coroas e outros paramentos; desembaraço de certidão de óbito; fornecimento de véu, essa e outros adornos; embalsamento, embelezamento, conservação ou restauração de cadáveres.',
            self::S250201 => 'Translado intramunicipal de corpos e partes de corpos cadavéricos.',
            self::S250202 => 'Cremação de corpos e partes de corpos cadavéricos.',
            self::S250301 => 'Planos ou convênio funerários.',
            self::S250401 => 'Manutenção e conservação de jazigos e cemitérios.',
            self::S250501 => 'Cessão de uso de espaços em cemitérios para sepultamento.',
            self::S260101 => 'Serviços de coleta, remessa ou entrega de correspondências, documentos, objetos, bens ou valores, inclusive pelos correios e suas agências franqueadas.',
            self::S260102 => 'Serviços de courrier e congêneres.',
            self::S270101 => 'Serviços de assistência social.',
            self::S280101 => 'Serviços de avaliação de bens e serviços de qualquer natureza.',
            self::S290101 => 'Serviços de biblioteconomia.',
            self::S300101 => 'Serviços de biologia e biotecnologia.',
            self::S300102 => 'Serviços de química.',
            self::S310101 => 'Serviços técnicos em edificações e congêneres.',
            self::S310102 => 'Serviços técnicos em eletrônica, eletrotécnica e congêneres.',
            self::S310103 => 'Serviços técnicos em mecânica e congêneres.',
            self::S310104 => 'Serviços técnicos em telecomunicações e congêneres.',
            self::S320101 => 'Serviços de desenhos técnicos.',
            self::S330101 => 'Serviços de desembaraço aduaneiro, comissários, despachantes e congêneres.',
            self::S340101 => 'Serviços de investigações particulares, detetives e congêneres.',
            self::S350101 => 'Serviços de reportagem e jornalismo.',
            self::S350102 => 'Serviços de assessoria de imprensa.',
            self::S350103 => 'Serviços de relações públicas.',
            self::S360101 => 'Serviços de meteorologia.',
            self::S370101 => 'Serviços de artistas, atletas, modelos e manequins.',
            self::S380101 => 'Serviços de museologia.',
            self::S390101 => 'Serviços de ourivesaria e lapidação (quando o material for fornecido pelo tomador do serviço).',
            self::S400101 => 'Obras de arte sob encomenda.',
            self::S990101 => 'Serviços sem a incidência de ISSQN e ICMS',
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
