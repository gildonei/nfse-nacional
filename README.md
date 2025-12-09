# NFS-e Nacional

[![PHP Version](https://img.shields.io/badge/php-8.3%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Packagist](https://img.shields.io/packagist/v/gildonei/nfse-nacional.svg)](https://packagist.org/packages/gildonei/nfse-nacional)

Pacote PHP para integração com a API NFS-e (Nota Fiscal de Serviço Eletrônica) do Governo Federal.

**Arquitetura:** Clean Architecture com separação em camadas Domain, Application, Infrastructure e Shared.

## Requisitos

- PHP 8.3 ou superior
- Certificado digital ICP-Brasil (A1 ou A3) com CNPJ
- Extensão OpenSSL habilitada
- Extensão ZIP habilitada (para compressão GZip)

## Instalação

```bash
composer require gildonei/nfse-nacional
```

## Estrutura do Projeto (Clean Architecture)

```
src/
├── Domain/                    # Regras de negócio
│   ├── Entity/               # Entidades (Dps, Nfse, Prestador, Tomador)
│   ├── ValueObject/          # Value Objects (Cpf, Cnpj, Telefone, Email)
│   ├── Contract/             # Interfaces de domínio
│   ├── Factory/              # Factories
│   └── Exception/            # Exceções de domínio
│
├── Application/              # Casos de uso
│   ├── UseCase/              # Use Cases (EmitirNfse, ConsultarNfse, etc.)
│   ├── DTO/                  # Data Transfer Objects
│   ├── Contract/             # Interfaces (Ports)
│   └── Exception/            # Exceções de aplicação
│
├── Infrastructure/           # Implementações externas
│   ├── Gateway/              # Gateway para API
│   ├── Http/                 # Cliente HTTP
│   ├── Security/             # Certificados
│   ├── Xml/                  # Manipulação XML
│   └── Compression/          # Compressão
│
└── Shared/                   # Código compartilhado
    ├── Enum/                 # Enumerações
    └── Exception/            # Exceções base
```

## Uso com Clean Architecture

### Emitir NFS-e usando Use Case

```php
use NfseNacional\Application\UseCase\Emissao\EmitirNfseUseCase;
use NfseNacional\Application\UseCase\Emissao\EmitirNfseRequest;
use NfseNacional\Domain\Entity\Dps;
use NfseNacional\Domain\Entity\Prestador;
use NfseNacional\Domain\Entity\Tomador;
use NfseNacional\Domain\Entity\Servico;
use NfseNacional\Domain\ValueObject\Documento\Cnpj;
use NfseNacional\Infrastructure\Gateway\Http\NfseApiGateway;
use NfseNacional\Infrastructure\Security\OpenSslCertificateHandler;
use NfseNacional\Shared\Enum\TipoAmbiente;

// 1. Configurar infraestrutura
$certificateHandler = new OpenSslCertificateHandler(
    '/caminho/para/certificado.pfx',
    'senha_do_certificado'
);

$gateway = new NfseApiGateway(
    $certificateHandler,
    TipoAmbiente::HOMOLOGACAO
);

// 2. Criar Use Case
$useCase = new EmitirNfseUseCase($gateway);

// 3. Criar entidades de domínio
$prestador = new Prestador(
    documento: new Cnpj('11222333000181'),
    razaoSocial: 'Empresa Prestadora LTDA'
);

$tomador = new Tomador(
    documento: '44555666000199', // String também funciona
    razaoSocial: 'Cliente Tomador LTDA'
);

$servico = new Servico(
    itemListaServico: '1401',
    discriminacao: 'Serviço de desenvolvimento de software',
    valorServicos: 1000.00,
    codigoMunicipio: '3550308'
);

$dps = new Dps(
    numero: '1',
    serie: '1',
    dataEmissao: new \DateTime(),
    prestador: $prestador,
    tomador: $tomador,
    servico: $servico
);

// 4. Executar Use Case
$request = new EmitirNfseRequest($dps);
$response = $useCase->execute($request);

// 5. Processar resposta
if ($response->sucesso) {
    echo "NFS-e emitida com sucesso!\n";
    echo "Chave de Acesso: " . $response->nfse->getChaveAcessoString() . "\n";
    echo "Número: " . $response->nfse->numero . "\n";
    echo "Protocolo: " . $response->protocolo . "\n";
} else {
    foreach ($response->erros as $erro) {
        echo "Erro: " . $erro . "\n";
    }
}
```

### Consultar NFS-e por Chave de Acesso

```php
use NfseNacional\Application\UseCase\Consulta\ConsultarNfsePorChaveUseCase;
use NfseNacional\Application\UseCase\Consulta\ConsultarNfsePorChaveRequest;

$useCase = new ConsultarNfsePorChaveUseCase($gateway);

$request = new ConsultarNfsePorChaveRequest(
    chaveAcesso: '12345678901234567890123456789012345678901234567890'
);

$response = $useCase->execute($request);

if ($response->encontrada) {
    $nfse = $response->nfse;
    echo "NFS-e encontrada: " . $nfse->numero . "\n";
    echo "Situação: " . $nfse->situacao->getDescricao() . "\n";
}
```

### Cancelar NFS-e

```php
use NfseNacional\Application\UseCase\Cancelamento\CancelarNfseUseCase;
use NfseNacional\Application\UseCase\Cancelamento\CancelarNfseRequest;

$useCase = new CancelarNfseUseCase($gateway);

$request = new CancelarNfseRequest(
    chaveAcesso: '12345678901234567890123456789012345678901234567890',
    codigoCancelamento: '1',
    motivo: 'Erro de digitação nos dados do tomador'
);

$response = $useCase->execute($request);

if ($response->sucesso) {
    echo "NFS-e cancelada com sucesso!\n";
    echo "Protocolo: " . $response->protocolo . "\n";
}
```

### Consultar DFe por NSU

```php
use NfseNacional\Application\UseCase\Consulta\ConsultarDfePorNsuUseCase;
use NfseNacional\Application\UseCase\Consulta\ConsultarDfePorNsuRequest;

$useCase = new ConsultarDfePorNsuUseCase($gateway);

$request = new ConsultarDfePorNsuRequest(
    nsu: 123456,
    cnpj: '11222333000181',
    lote: true
);

$response = $useCase->execute($request);

echo "Status: " . $response->status->getDescricao() . "\n";
echo "Ambiente: " . $response->ambiente->getDescricao() . "\n";

foreach ($response->itens as $item) {
    echo "NSU: " . $item->nsu . "\n";
    echo "Chave: " . $item->chaveAcesso . "\n";
}

// Verificar se há mais documentos
if ($response->hasMore()) {
    echo "Há mais documentos. Último NSU: " . $response->ultimoNsu . "\n";
}
```

## Trabalhando com Documentos (CPF/CNPJ)

```php
use NfseNacional\Domain\ValueObject\Documento\Cpf;
use NfseNacional\Domain\ValueObject\Documento\Cnpj;
use NfseNacional\Domain\Factory\DocumentoFactory;

// Criar CPF diretamente
$cpf = new Cpf('11144477735');
echo $cpf->getFormatado();      // 111.444.777-35
echo $cpf->getSemFormatacao();  // 11144477735
echo $cpf->getTipo();           // CPF

// Criar CNPJ diretamente
$cnpj = new Cnpj('11222333000181');
echo $cnpj->getFormatado();     // 11.222.333/0001-81
echo $cnpj->getSemFormatacao(); // 11222333000181
echo $cnpj->getTipo();          // CNPJ

// Usar DocumentoFactory para criar automaticamente
$documento1 = DocumentoFactory::criar('11144477735');    // Cria CPF (11 dígitos)
$documento2 = DocumentoFactory::criar('11222333000181'); // Cria CNPJ (14 dígitos)

// Usar em Prestador ou Tomador
$prestador = new Prestador(
    documento: '11222333000181', // String: cria automaticamente
    razaoSocial: 'Empresa LTDA'
);

// Validação automática
try {
    $cpf = new Cpf('11111111111'); // CPF inválido
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage(); // CPF inválido: sequência repetida
}
```

## Injeção de Dependência

A arquitetura é preparada para containers de DI:

```php
// Exemplo com um container simples
$container->bind(
    CertificateHandlerInterface::class,
    fn() => new OpenSslCertificateHandler($certPath, $certPassword)
);

$container->bind(
    NfseGatewayInterface::class,
    fn($c) => new NfseApiGateway(
        $c->get(CertificateHandlerInterface::class),
        TipoAmbiente::HOMOLOGACAO
    )
);

$container->bind(
    EmitirNfseUseCase::class,
    fn($c) => new EmitirNfseUseCase(
        $c->get(NfseGatewayInterface::class)
    )
);

// Uso
$useCase = $container->get(EmitirNfseUseCase::class);
```

## Enumerações Disponíveis

```php
use NfseNacional\Shared\Enum\TipoAmbiente;
use NfseNacional\Shared\Enum\SituacaoNfse;
use NfseNacional\Shared\Enum\TipoEvento;
use NfseNacional\Shared\Enum\TipoManifestacao;
use NfseNacional\Shared\Enum\StatusProcessamento;

// Ambiente
TipoAmbiente::PRODUCAO;     // Produção
TipoAmbiente::HOMOLOGACAO;  // Homologação

// Situação da NFS-e
SituacaoNfse::NORMAL;
SituacaoNfse::CANCELADA;
SituacaoNfse::SUBSTITUIDA;

// Tipos de evento
TipoEvento::CANCELAMENTO;
TipoEvento::SUBSTITUICAO;
TipoEvento::MANIFESTACAO_CONFIRMACAO;
TipoEvento::MANIFESTACAO_REJEICAO;
```

## Tratamento de Erros

```php
use NfseNacional\Application\Exception\ApplicationException;
use NfseNacional\Domain\Exception\ValidationException;
use NfseNacional\Shared\Exception\ApiException;
use NfseNacional\Shared\Exception\CertificateException;

try {
    $response = $useCase->execute($request);
} catch (ValidationException $e) {
    // Erros de validação de domínio
    foreach ($e->getErrors() as $field => $messages) {
        echo "Campo {$field}: " . implode(', ', $messages) . "\n";
    }
} catch (CertificateException $e) {
    // Problemas com o certificado digital
    echo "Erro no certificado: " . $e->getMessage() . "\n";
} catch (ApiException $e) {
    // Erros na comunicação com a API
    echo "Erro na API: " . $e->getMessage() . "\n";
    echo "Status HTTP: " . $e->getStatusCode() . "\n";
} catch (ApplicationException $e) {
    // Outros erros de aplicação
    echo "Erro: " . $e->getMessage() . "\n";
}
```

## Testes

```bash
composer test
```

## Segurança

- Mantenha seu certificado digital em local seguro
- Nunca versione o arquivo do certificado
- Use variáveis de ambiente para senhas
- Utilize HTTPS em todas as comunicações
- Valide todos os dados de entrada

## Licença

MIT License - veja [LICENSE](LICENSE) para detalhes.

## Autor

Gildonei M A Junior
Email: gildonei.mendes@gmail.com

## Changelog

### 2.0.0

- Refatoração completa para Clean Architecture
- Separação em camadas: Domain, Application, Infrastructure, Shared
- Criação de Use Cases para cada operação
- Value Objects para CPF, CNPJ, Telefone, Email, Endereco
- Interfaces (Ports) para inversão de dependência
- DTOs para Request/Response
- Suporte completo a injeção de dependência
