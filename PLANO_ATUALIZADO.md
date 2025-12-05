# Plano Atualizado: Criar Vendor PHP para API NFS-e Nacional

## Objetivo
Desenvolver um pacote PHP 8.3+ para integração com a API NFS-e do Governo Federal, incluindo funcionalidades de emissão, consulta, cancelamento, substituição e manifestação, disponibilizado via Packagist para uso com Composer.

## Informações do Pacote
- **Repositório GitHub**: https://github.com/gildonei/nfse-nacional
- **Nome do pacote Packagist**: `nfse-nacional/nfse-nacional`
- **Autor**: Gildonei M A Junior <gildonei.mendes@gmail.com>
- **Licença**: MIT
- **Versão PHP mínima**: 8.3+

## Estrutura do Pacote

### Arquivos de Configuração
- `composer.json` - Configuração do pacote, dependências, autoload e metadados
- `README.md` - Documentação principal com exemplos de uso
- `.gitignore` - Arquivos a ignorar no controle de versão
- `LICENSE` - Licença MIT
- `phpunit.xml.dist` - Configuração do PHPUnit para testes

### Estrutura de Diretórios
```
src/
├── Client/
│   └── NfseClient.php          # Cliente principal para comunicação com a API
├── Models/
│   ├── DistribuicaoNSU.php     # Modelo para DistribuicaoNSU
│   ├── LoteDistribuicaoNSUResponse.php
│   ├── MensagemProcessamento.php
│   ├── DPS.php                  # Declaração de Prestação de Serviços (NOVO)
│   ├── Nfse.php                 # Modelo de NFS-e (NOVO)
│   ├── PedidoRegistroEvento.php # Modelo para pedido de registro de evento (NOVO)
│   ├── RascunhoDPS.php          # Modelo para rascunho de DPS (NOVO)
│   └── Enums/
│       ├── StatusProcessamentoDistribuicao.php
│       ├── TipoDocumentoRequisicao.php
│       ├── TipoEvento.php
│       ├── TipoAmbiente.php
│       ├── TipoManifestacao.php # Enum para manifestação (NOVO)
│       └── SituacaoNfse.php     # Enum para situação da NFS-e (NOVO)
├── Security/
│   └── CertificateHandler.php   # Gerenciamento de certificado digital
├── Utils/
│   ├── XmlHandler.php          # Manipulação e assinatura XML
│   └── CompressionHandler.php  # GZip e base64
└── Exceptions/
    ├── NfseException.php        # Exceção base
    ├── CertificateException.php
    ├── ApiException.php
    └── ValidationException.php

tests/
└── Unit/
    ├── Client/
    ├── Models/
    └── Utils/
```

## Funcionalidades Principais

### 1. Cliente HTTP (`NfseClient`)
- Configuração de URL base da API (ambiente produção/homologação)
- Suporte a certificado digital ICP-Brasil (A1/A3)
- Autenticação mútua TLS 1.2+
- Tratamento de requisições GET, POST, PUT, DELETE
- Suporte a múltiplos formatos de resposta (JSON, XML)
- Tratamento de erros HTTP com exceções customizadas

### 2. Endpoints Implementados

#### 2.1. Distribuição/Consulta (Já implementados)
- `GET /DFe/{NSU}` - Consulta documento fiscal por NSU
  - Parâmetros: `cnpjConsulta` (opcional), `lote` (boolean, default: true)
- `GET /NFSe/{ChaveAcesso}/Eventos` - Consulta eventos por chave de acesso

#### 2.2. Emissão de NFS-e (NOVOS)
- `POST /DPS` - Enviar Declaração de Prestação de Serviços (DPS) para emissão de NFS-e
  - Body: XML da DPS assinado e comprimido (GZip + base64)
  - Retorna: NFS-e gerada ou erros de validação
- `POST /DPS/Lote` - Enviar lote de DPS para processamento em lote
  - Body: Array de DPS
  - Retorna: Lote processado com status de cada DPS

#### 2.3. Consulta de NFS-e Emitidas (NOVOS)
- `GET /NFSe/Emitidas` - Consulta NFS-e emitidas pelo contribuinte
  - Parâmetros: `dataInicio`, `dataFim`, `numeroNfse` (opcional), `serie` (opcional)
  - Retorna: Lista de NFS-e emitidas
- `GET /NFSe/{ChaveAcesso}` - Consulta NFS-e específica por chave de acesso
  - Retorna: Dados completos da NFS-e
- `GET /NFSe/{ChaveAcesso}/XML` - Download do XML da NFS-e
- `GET /NFSe/{ChaveAcesso}/DANFSE` - Download da DANFSE (PDF)

#### 2.4. Consulta de NFS-e Recebidas (NOVOS)
- `GET /NFSe/Recebidas` - Consulta NFS-e recebidas pelo contribuinte
  - Parâmetros: `dataInicio`, `dataFim`, `cnpjPrestador` (opcional)
  - Retorna: Lista de NFS-e recebidas
- `GET /NFSe/Recebidas/{ChaveAcesso}` - Consulta NFS-e recebida específica
- `GET /NFSe/Recebidas/{ChaveAcesso}/XML` - Download do XML da NFS-e recebida
- `GET /NFSe/Recebidas/{ChaveAcesso}/DANFSE` - Download da DANFSE recebida

#### 2.5. Cancelamento de NFS-e (NOVOS)
- `POST /NFSe/{ChaveAcesso}/Cancelar` - Cancelar uma NFS-e
  - Body: Justificativa do cancelamento
  - Retorna: Confirmação ou erro
- `POST /NFSe/{ChaveAcesso}/SolicitarCancelamentoAnaliseFiscal` - Solicitar cancelamento por análise fiscal
  - Body: Justificativa e documentos comprobatórios
  - Retorna: Protocolo de solicitação

#### 2.6. Substituição de NFS-e (NOVOS)
- `POST /NFSe/{ChaveAcesso}/Substituir` - Substituir uma NFS-e
  - Body: Nova DPS que substitui a NFS-e original
  - Retorna: Nova NFS-e gerada

#### 2.7. Manifestação de NFS-e Recebidas (NOVOS)
- `POST /NFSe/Recebidas/{ChaveAcesso}/Manifestar` - Manifestar uma NFS-e recebida
  - Body: Tipo de manifestação (CONFIRMACAO_TOMADOR, REJEICAO_TOMADOR, etc.)
  - Retorna: Confirmação da manifestação

#### 2.8. Rascunho de DPS (NOVOS)
- `POST /DPS/Rascunho` - Salvar rascunho de DPS
  - Body: Dados da DPS (sem assinatura)
  - Retorna: ID do rascunho
- `GET /DPS/Rascunho` - Listar rascunhos salvos
  - Retorna: Lista de rascunhos
- `GET /DPS/Rascunho/{id}` - Obter rascunho específico
- `PUT /DPS/Rascunho/{id}` - Atualizar rascunho
- `DELETE /DPS/Rascunho/{id}` - Excluir rascunho

#### 2.9. Consulta Pública (NOVOS)
- `GET /NFSe/Publica/ChaveAcesso/{ChaveAcesso}` - Consulta pública por chave de acesso
  - Não requer autenticação
  - Retorna: Dados públicos da NFS-e
- `GET /NFSe/Publica/DPS` - Consulta pública por dados da DPS
  - Parâmetros: Dados da DPS (CNPJ, número, série, etc.)
  - Não requer autenticação

#### 2.10. Emissão por Decisão Administrativa/Judicial (NOVOS)
- `POST /NFSe/DecisaoAdministrativa` - Incluir NFS-e por decisão administrativa/judicial
  - Body: Dados da NFS-e e documento da decisão
  - Retorna: NFS-e incluída
- `GET /NFSe/DecisaoAdministrativa` - Consultar NFS-e emitidas por decisão
- `POST /NFSe/DecisaoAdministrativa/{ChaveAcesso}/Cancelar` - Cancelar NFS-e de decisão
- `POST /NFSe/DecisaoAdministrativa/{ChaveAcesso}/Substituir` - Substituir NFS-e de decisão

### 3. Modelos de Dados
- Classes PHP tipadas baseadas nos schemas do Swagger e documentação
- Propriedades com tipos estritos (PHP 8.3+)
- Validação de dados de entrada
- Serialização/deserialização JSON/XML
- Enums para tipos específicos

#### Novos Modelos:
- `DPS`: Modelo completo da Declaração de Prestação de Serviços
- `Nfse`: Modelo completo da NFS-e gerada
- `PedidoRegistroEvento`: Modelo para pedidos de eventos (cancelamento, substituição, etc.)
- `RascunhoDPS`: Modelo para rascunhos de DPS
- `ManifestacaoNfse`: Modelo para manifestações de NFS-e recebidas

### 4. Segurança
- Gerenciamento de certificado digital (arquivo .pfx/.p12)
- Validação de certificado ICP-Brasil
- Suporte a autenticação mútua via Guzzle
- Validação de CNPJ no certificado
- Assinatura XML com XMLDSIG para DPS

### 5. Utilitários
- Manipulação de XML (leitura, escrita, validação)
- Assinatura XML com XMLDSIG (usando biblioteca externa)
- Compressão/descompressão GZip
- Codificação/decodificação base64
- Geração de chave de acesso da NFS-e
- Validação de CNPJ/CPF

## Dependências Externas
- `guzzlehttp/guzzle` (^7.0) - Cliente HTTP com suporte a certificados
- `symfony/options-resolver` (^6.0 ou ^7.0) - Validação de configurações
- `psr/http-client` (^1.0) - Interface PSR para cliente HTTP
- `psr/http-message` (^1.0 ou ^2.0) - Interfaces PSR para mensagens HTTP
- `robrichards/xmlseclibs` (^3.1) - Assinatura XMLDSIG
- `respect/validation` (^2.0) - Validação de dados (CNPJ, CPF, etc.) - NOVO

## Dependências de Desenvolvimento
- `phpunit/phpunit` (^10.0) - Framework de testes
- `phpstan/phpstan` (^1.10) - Análise estática (opcional)
- `php-cs-fixer` (^3.0) - Formatação de código (opcional)

## Testes
- Testes unitários para classes principais
- Mocks para chamadas HTTP
- Validação de modelos e enums
- Testes de integração (opcional, com ambiente de homologação)
- Testes para novos endpoints

## Documentação
- README.md com:
  - Instalação via Composer
  - Exemplos de uso básicos e avançados
  - Configuração de certificado digital
  - Documentação da API completa
  - Exemplos para cada endpoint
  - Badges (Packagist, GitHub, PHP version)
- PHPDoc completo em todas as classes públicas
- Exemplos de código para cada funcionalidade

## Observações
- O Swagger original continha apenas endpoints de consulta/distribuição
- Baseado no Guia do Emissor Público Nacional Web, foram identificadas funcionalidades adicionais
- URLs base da API serão configuráveis (produção/homologação)
- O pacote seguirá as convenções PSR-4 para autoload
- Todas as classes serão namespaced como `NfseNacional\*`
- Endpoints de emissão requerem DPS assinada digitalmente
- Alguns endpoints podem não estar disponíveis na API REST e podem ser apenas via web/mobile

## Tarefas de Implementação

### Fase 1: Endpoints Básicos (Já implementado)
- [x] Consulta por NSU
- [x] Consulta de eventos por chave de acesso

### Fase 2: Emissão de NFS-e
- [ ] Criar modelo DPS completo
- [ ] Implementar método de emissão de DPS única
- [ ] Implementar método de emissão de lote de DPS
- [ ] Implementar assinatura XML da DPS
- [ ] Implementar compressão e codificação da DPS

### Fase 3: Consultas Avançadas
- [ ] Implementar consulta de NFS-e emitidas
- [ ] Implementar consulta de NFS-e recebidas
- [ ] Implementar download de XML
- [ ] Implementar download de DANFSE (PDF)

### Fase 4: Cancelamento e Substituição
- [ ] Implementar cancelamento de NFS-e
- [ ] Implementar solicitação de cancelamento por análise fiscal
- [ ] Implementar substituição de NFS-e

### Fase 5: Manifestação
- [ ] Implementar manifestação de NFS-e recebidas
- [ ] Criar enum TipoManifestacao

### Fase 6: Rascunhos
- [ ] Implementar CRUD de rascunhos de DPS

### Fase 7: Consulta Pública
- [ ] Implementar consulta pública por chave de acesso
- [ ] Implementar consulta pública por dados da DPS

### Fase 8: Decisão Administrativa/Judicial
- [ ] Implementar inclusão de NFS-e por decisão
- [ ] Implementar consulta de NFS-e de decisão
- [ ] Implementar cancelamento/substituição de NFS-e de decisão

### Fase 9: Testes e Documentação
- [ ] Criar testes para novos endpoints
- [ ] Atualizar README com novos exemplos
- [ ] Documentar todos os métodos públicos

