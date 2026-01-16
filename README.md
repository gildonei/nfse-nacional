# NFS-e Nacional - Emissor de Nota Fiscal de Serviço Eletrônica

Biblioteca PHP para emissão de Nota Fiscal de Serviço Eletrônica (NFS-e) Nacional, seguindo os padrões da Receita Federal do Brasil.

> ⚠️ **AVISO IMPORTANTE**
>
> - Todos os dados utilizados nos exemplos são **fictícios** e servem apenas para demonstração.
> - Para emissão real de NFS-e, é necessário utilizar **dados reais** (CNPJ, certificado digital ICP-Brasil válido, etc.).
> - Esta biblioteca é fornecida **"como está"**, sem garantias de qualquer tipo, expressas ou implícitas.
> - O desenvolvedor **não se responsabiliza** por quaisquer danos ou prejuízos decorrentes do uso desta biblioteca.
> - **Teste sempre em ambiente de homologação** antes de utilizar em produção.

## Características

- ✅ Clean Architecture
- ✅ PHP 8.3+
- ✅ Suporte a certificado digital ICP-Brasil (Value Object)
- ✅ Assinatura XML digital (XMLDSIG)
- ✅ Autenticação SSL/TLS com certificado digital
- ✅ Integração com API Sefin Nacional
- ✅ Compressão GZip e codificação Base64
- ✅ Validação de dados com Enums type-safe
- ✅ Type-safe com tipos estritos
- ✅ Geração automática de IDs conforme padrão NFS-e Nacional
- ✅ Validação de campos obrigatórios
- ✅ Suporte completo a CPF e CNPJ
- ✅ Timezone padrão: Brasília (America/Sao_Paulo)
- ✅ Encoding UTF-8 garantido
- ✅ XML otimizado (sem quebras de linha)

## Requisitos

- PHP 8.3 ou superior
- Certificado digital ICP-Brasil (A1 ou A3) com CNPJ (.pfx ou .p12)
- Extensão OpenSSL habilitada
- Extensão ZIP habilitada (para compressão GZip)
- Extensão DOM habilitada (para manipulação XML)
- Extensão mbstring habilitada (para validação de encoding)
- Guzzle HTTP Client (via Composer)

## Instalação

```bash
composer require gildonei/nfse-nacional
```

## Estrutura do Projeto

```
src/
├── Domain/                    # Regras de negócio
│   ├── Entity/               # Entidades do domínio
│   │   ├── Dps.php          # Documento de Prestação de Serviços
│   │   ├── Prestador.php    # Prestador de serviços
│   │   ├── Tomador.php      # Tomador de serviços
│   │   ├── Emitente.php     # Emitente da NFS-e
│   │   └── Pessoa.php       # Classe base para pessoas
│   │
│   ├── Enum/                 # Enumerações para validação
│   │   ├── AmbienteGeradorNfse.php
│   │   ├── ModoPrestacao.php
│   │   ├── MotivoNaoInformarNif.php
│   │   ├── OptanteSimplesNacional.php
│   │   ├── ProcessoEmissao.php
│   │   ├── RegimeEspecialTributacaoMunicipal.php
│   │   ├── RegimeTributacaoSimplesNacional.php
│   │   ├── SituacoesPossiveisNfse.php
│   │   ├── TipoBeneficioMunicipal.php
│   │   ├── TipoEmissaoNfse.php
│   │   ├── TipoEmitente.php
│   │   ├── TributacaoIssqn.php
│   │   └── VinculoEntrePartes.php
│   │
│   ├── ValueObject/          # Value Objects
│   │   ├── Certificado.php  # Certificado digital PKCS#12
│   │   ├── Cpf.php          # Validação e formatação de CPF
│   │   ├── Cnpj.php         # Validação e formatação de CNPJ
│   │   ├── Email.php        # Validação de e-mail
│   │   ├── Endereco.php     # Endereço completo
│   │   └── Telefone.php     # Telefone com DDD
│   │
│   ├── Xml/                  # Geração de XML
│   │   └── DpsXml.php       # Gerador de XML da DPS
│   │
│   ├── Contract/             # Interfaces de domínio
│   │   ├── AssinadorXmlInterface.php
│   │   └── HttpClientInterface.php
│   │
│   ├── Factory/              # Factories
│   └── Exception/            # Exceções de domínio
│
├── Application/              # Camada de aplicação
│   └── Service/             # Serviços de aplicação
│       └── SefinNacionalService.php  # Serviço de integração com API
│
└── Infrastructure/           # Implementações técnicas
    ├── Security/            # Segurança
    │   └── AssinadorXml.php # Assinatura XML digital
    └── Http/                # Comunicação HTTP
        └── HttpClient.php   # Cliente HTTP (Guzzle)

docs/
├── emissao-dps.php          # Exemplo completo de emissão de DPS
└── consulta-nfse.php        # Exemplo de consulta de NFS-e
```

## Uso Básico

### Exemplo Completo

Consulte o arquivo `docs/emissao-dps.php` para um exemplo completo e detalhado de como criar, assinar e enviar uma DPS para a API Sefin Nacional.

### Exemplo Simplificado

```php
<?php

use NfseNacional\Domain\Entity\Dps;
use NfseNacional\Domain\Entity\Prestador;
use NfseNacional\Domain\Entity\Tomador;
use NfseNacional\Domain\Entity\Emitente;
use NfseNacional\Domain\Enum\ProcessoEmissao;
use NfseNacional\Domain\Enum\TipoEmissaoNfse;
use NfseNacional\Domain\Enum\AmbienteGeradorNfse;
use NfseNacional\Domain\Enum\SituacoesPossiveisNfse;
use NfseNacional\Domain\Enum\OptanteSimplesNacional;
use NfseNacional\Domain\Enum\RegimeEspecialTributacaoMunicipal;
use NfseNacional\Domain\ValueObject\Certificado;
use NfseNacional\Domain\ValueObject\Cnpj;
use NfseNacional\Domain\ValueObject\Cpf;
use NfseNacional\Domain\ValueObject\Endereco;
use NfseNacional\Domain\ValueObject\Email;
use NfseNacional\Domain\ValueObject\Telefone;
use NfseNacional\Domain\Xml\DpsXml;
use NfseNacional\Application\Service\SefinNacionalService;
use NfseNacional\Infrastructure\Security\AssinadorXml;
use DateTime;
use DateTimeZone;

// 1. Criar Prestador
$prestador = new Prestador(
    nome: 'Empresa Prestadora LTDA',
    documento: new Cnpj('50600661000126'),
    endereco: new Endereco(
        logradouro: 'Rua Exemplo',
        numero: '123',
        bairro: 'Centro',
        codigoMunicipio: 4205407, // Florianópolis/SC
        uf: 'SC',
        cep: '88010000'
    ),
    optanteSimplesNacional: OptanteSimplesNacional::OptanteMEEPP,
    regimeEspecialTributacao: RegimeEspecialTributacaoMunicipal::Nenhum
);

// 2. Criar Tomador
$tomador = new Tomador(
    nome: 'Cliente Tomador LTDA',
    documento: new Cnpj('12345678000190'),
    endereco: new Endereco(
        logradouro: 'Av. Cliente',
        numero: '456',
        bairro: 'Bairro Cliente',
        codigoMunicipio: 4205407,
        uf: 'SC',
        cep: '88020000'
    )
);

// 3. Criar DPS
$dps = new Dps();
$dps->definirPrestador($prestador)
    ->definirTomador($tomador)
    ->definirTipoAmbiente(2) // Homologação
    ->definirVersaoAplicacao('1.0.0')
    ->definirSerie('1')
    ->definirNumeroDps('1')
    ->definirDataHoraEmissao(new DateTime('now', new DateTimeZone('America/Sao_Paulo')))
    ->definirDataCompetencia(new DateTime('now', new DateTimeZone('America/Sao_Paulo')))
    ->definirValorServico(1000.00)
    ->definirValorRecebido(1000.00);

// 4. Criar Certificado
$certificado = new Certificado('/caminho/certificado.pfx', 'senha123');

// 5. Criar Emitente
$emitente = new Emitente(
    nome: 'Empresa Emitente LTDA',
    documento: new Cnpj('50600661000126'),
    endereco: $prestador->obterEndereco(),
    telefone: new Telefone(codigoPais: 55, codigoArea: 48, numero: 33334444),
    email: 'emitente@empresa.com.br',
    certificado: $certificado
);

// 6. Gerar XML
$dpsXml = new DpsXml(
    dps: $dps,
    emitente: $emitente,
    nNFSe: 1,
    processoEmissao: ProcessoEmissao::AplicativoContribuinte,
    tipoEmissaoNfse: TipoEmissaoNfse::EmissaoNormal,
    ambienteGeradorNfse: AmbienteGeradorNfse::SefinNacionalNfse,
    situacaoPossivelNfse: SituacoesPossiveisNfse::NfseGerada
);

// 7. Assinar e Enviar para API
$assinador = new AssinadorXml();
$sefinService = new SefinNacionalService(
    emitente: $emitente,
    assinador: $assinador,
    tipoAmbiente: SefinNacionalService::AMBIENTE_HOMOLOGACAO
);

$resposta = $sefinService->enviarDps($dpsXml);
print_r($resposta);
```

## Enums Disponíveis

A biblioteca utiliza enums para garantir type-safety e validação de campos:

### ProcessoEmissao
- `AplicativoContribuinte` (1) - Emissão com aplicativo do contribuinte (via Web Service)
- `AplicativoFiscoWeb` (2) - Emissão com aplicativo disponibilizado pelo fisco (Web)
- `AplicativoFiscoApp` (3) - Emissão com aplicativo disponibilizado pelo fisco (App)

### TipoEmissaoNfse
- `EmissaoNormal` (1) - Emissão normal no modelo da NFS-e Nacional
- `EmissaoOriginalLeiauteProprio` (2) - Emissão original em leiaute próprio do município

### AmbienteGeradorNfse
- `SistemaProprioMunicipio` (1) - Sistema Próprio do Município
- `SefinNacionalNfse` (2) - Sefin Nacional NFS-e

### TipoBeneficioMunicipal
- `Isencao` (1) - Isenção
- `ReducaoBCPercentual` (2) - Redução da BC em 'ppBM' %
- `ReducaoBCValor` (3) - Redução da BC em R$ 'vInfoBM'
- `AliquotaDiferenciada` (4) - Alíquota Diferenciada de 'aliqDifBM' %

### TipoEmitente
- `Prestador` (1) - Prestador
- `Tomador` (2) - Tomador
- `Intermediario` (3) - Intermediário

### OptanteSimplesNacional
- `NaoOptante` (1) - Não Optante
- `OptanteMEI` (2) - Optante - Microempreendedor Individual (MEI)
- `OptanteMEEPP` (3) - Optante - Microempresa ou Empresa de Pequeno Porte (ME/EPP)

### RegimeTributacaoSimplesNacional
- `RegimeApuracaoTributosFederaisMunicipalSN` (1) - Regime de apuração dos tributos federais e municipal pelo SN
- `RegimeApuracaoTributosFederaisSNISSQNNfse` (2) - Regime de apuração dos tributos federais pelo SN e o ISSQN pela NFS-e
- `RegimeApuracaoTributosFederaisMunicipalNfse` (3) - Regime de apuração dos tributos federais e municipal pela NFS-e

### RegimeEspecialTributacaoMunicipal
- `Nenhum` (0) - Nenhum
- `AtoCooperado` (1) - Ato Cooperado
- `Estimativa` (2) - Estimativa
- `MicroempresaMunicipal` (3) - Microempresa Municipal
- `NotarioOuRegistrador` (4) - Notário ou Registrador
- `ProfissionalAutonomo` (5) - Profissional Autônomo
- `SociedadeDeProfissionais` (6) - Sociedade de Profissionais

### MotivoNaoInformarNif
- `NaoInformadoNotaOrigem` (0) - Não informado na nota de origem
- `DispensadoNIF` (1) - Dispensado do NIF
- `NaoExigenciaNIF` (2) - Não exigência do NIF

### ModoPrestacao
- `Desconhecido` (0) - Desconhecido (tipo não informado na nota de origem)
- `Transfronteirico` (1) - Transfronteiriço
- `ConsumoNoBrasil` (2) - Consumo no Brasil
- `PresencaComercialExterior` (3) - Presença Comercial no Exterior
- `MovimentoTemporarioPessoasFisicas` (4) - Movimento Temporário de Pessoas Físicas

### VinculoEntrePartes
- `SemVinculo` (0) - Sem vínculo com o tomador/Prestador
- `Controlada` (1) - Controlada
- `Controladora` (2) - Controladora
- `Coligada` (3) - Coligada
- `Matriz` (4) - Matriz
- `FilialOuSucursal` (5) - Filial ou sucursal
- `OutroVinculo` (6) - Outro vínculo

### TributacaoIssqn
- `OperacaoTributavel` (1) - Operação tributável
- `Imunidade` (2) - Imunidade
- `ExportacaoServico` (3) - Exportação de serviço
- `NaoIncidencia` (4) - Não Incidência

### SituacoesPossiveisNfse
- `NfseGerada` (100) - NFS-e Gerada
- `NfseSubstituicaoGerada` (101) - NFS-e de Substituição Gerada
- `NfseDecisaoJudicial` (102) - NFS-e de Decisão Judicial
- `NfseAvulsa` (103) - NFS-e Avulsa

## Geração de IDs

A biblioteca gera automaticamente os IDs conforme o padrão NFS-e Nacional:

### ID do infNFSe (53 caracteres)
Formato: `NFS` + Cód.Mun. (7) + Amb.Ger. (1) + Tipo de Inscrição Federal (1) + Inscrição Federal (14) + nNFSe (13) + AnoMes Emis. (4) + Valor do node nNFSe com 9 dígitos + DV (1)

### ID do infDPS (45 caracteres)
Formato: `DPS` + Cód.Mun. (7) + Tipo de Inscrição Federal (1) + Inscrição Federal (14) + Série DPS (5) + Núm. DPS (15)

### Chave de Acesso da NFS-e (50 dígitos)
A chave de acesso é composta por:
- Código do Município (7 dígitos)
- Ambiente Gerador (1 dígito)
- Tipo de Inscrição Federal (1 dígito)
- Inscrição Federal (14 dígitos)
- Número da NFS-e (13 dígitos)
- Ano/Mês de Emissão (4 dígitos)
- Valor do nNFSe (9 dígitos)
- Dígito Verificador (1 dígito)

## Determinação Automática de TipoEmitente

O campo `tpEmit` é determinado automaticamente pela comparação dos documentos:
- Se o documento do Emitente for igual ao do Prestador → `TipoEmitente::Prestador` (1)
- Se o documento do Emitente for igual ao do Tomador → `TipoEmitente::Tomador` (2)
- Caso contrário → `TipoEmitente::Intermediario` (3)

## Timezone

O timezone padrão utilizado é **Brasília (America/Sao_Paulo)** para todos os campos de data/hora.

## Certificado Digital

O certificado digital é encapsulado em um Value Object `Certificado` que:
- Valida o certificado PKCS#12
- Gerencia o conteúdo e a senha de forma segura
- Pode ser criado a partir de caminho de arquivo ou conteúdo

```php
// Criar certificado a partir de arquivo
$certificado = new Certificado('/caminho/certificado.pfx', 'senha123');

// Validar certificado
$certificado->validar();
```

## Integração com API Sefin Nacional

O `SefinNacionalService` fornece métodos para integração com a API:

### Métodos Disponíveis

- `enviarDps(DpsXml $dpsXml)` - Envia DPS e gera NFS-e
- `consultarDps(string $idDps)` - Consulta chave de acesso pelo ID do DPS
- `verificarDps(string $idDps)` - Verifica se NFS-e foi emitida
- `consultarNfse(string $chaveAcesso)` - Consulta NFS-e pela chave de acesso
- `enviarNfseDecisaoJudicial(string $xmlNfseAssinado)` - Envia NFS-e com decisão judicial
- `registrarEvento(string $chaveAcesso, string $xmlEventoAssinado)` - Registra evento na NFS-e
- `consultarEvento(string $chaveAcesso, int $tipoEvento, int $numSeqEvento)` - Consulta evento específico

### Ambientes

- `SefinNacionalService::AMBIENTE_PRODUCAO` (1) - Ambiente de produção
- `SefinNacionalService::AMBIENTE_HOMOLOGACAO` (2) - Ambiente de homologação

### Autenticação SSL/TLS

O serviço utiliza automaticamente o certificado do emitente para autenticação SSL/TLS em todas as requisições HTTP.

### Resposta da API

Quando a emissão da DPS é bem-sucedida (statusCode 201), a resposta contém o campo `nfseXmlGZipB64` com o XML da NFS-e comprimido em GZip e codificado em Base64.

```php
// Verificar se a emissão foi bem-sucedida
if ($resposta['statusCode'] === 201) {
    $body = $resposta['body'];

    if (isset($body['nfseXmlGZipB64'])) {
        // Decodificar Base64 e descomprimir GZip
        $xmlDecodificado = base64_decode($body['nfseXmlGZipB64'], true);
        $xmlNfse = gzdecode($xmlDecodificado);

        // $xmlNfse contém o XML completo da NFS-e
        echo $xmlNfse;
    }

    // Outros campos disponíveis na resposta:
    // $body['tipoAmbiente'] - 1 = Produção, 2 = Homologação
    // $body['versaoAplicativo'] - Versão do aplicativo Sefin Nacional
    // $body['dataHoraProcessamento'] - Data/hora do processamento
    // $body['chaveAcesso'] - Chave de acesso da NFS-e (50 dígitos)
    // $body['idDPS'] - ID do DPS
}
```

## Processamento de XML

O XML gerado é processado antes do envio:
- ✅ Encoding UTF-8 garantido
- ✅ Quebras de linha removidas
- ✅ Espaços em branco otimizados
- ✅ Compressão GZip
- ✅ Codificação Base64

## Exemplos

### Emissão de DPS

O arquivo `docs/emissao-dps.php` contém um exemplo completo de:
- Criação de Certificado, Emitente, Prestador e Tomador
- Criação e configuração da DPS
- Geração do XML
- Assinatura digital
- Envio para a API Sefin Nacional
- Exibição do XML da NFS-e emitida (quando statusCode 201)

```bash
# Executar o exemplo de emissão
php docs/emissao-dps.php
```

### Consulta de NFS-e

O arquivo `docs/consulta-nfse.php` contém um exemplo de:
- Consulta de NFS-e pela chave de acesso (50 dígitos)
- Decodificação do XML da NFS-e (Base64 + GZip)
- Exibição formatada do XML

```bash
# Executar o exemplo de consulta
php docs/consulta-nfse.php
```

## Desenvolvimento

```bash
# Instalar dependências
composer install

# Executar testes
composer test
```

## Licença

MIT License

**ISENÇÃO DE RESPONSABILIDADE:** Esta biblioteca é fornecida "como está", sem garantias de qualquer tipo. O uso é de inteira responsabilidade do usuário. O desenvolvedor não se responsabiliza por erros, falhas, perdas financeiras ou quaisquer outros danos decorrentes do uso desta biblioteca.

## Autor

Gildonei M A Junior
