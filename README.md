# NFS-e Nacional

[![PHP Version](https://img.shields.io/badge/php-8.3%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Packagist](https://img.shields.io/packagist/v/nfse-nacional/nfse-nacional.svg)](https://packagist.org/packages/nfse-nacional/nfse-nacional)

Pacote PHP para integração com a API NFS-e (Nota Fiscal de Serviço Eletrônica) do Governo Federal.

## Requisitos

- PHP 8.3 ou superior
- Certificado digital ICP-Brasil (A1 ou A3) com CNPJ
- Extensão OpenSSL habilitada
- Extensão ZIP habilitada (para compressão GZip)

## Instalação

Instale o pacote via Composer:

```bash
composer require nfse-nacional/nfse-nacional
```

## Configuração

### Certificado Digital

O pacote requer um certificado digital ICP-Brasil (tipo A1 ou A3) com CNPJ. O certificado deve estar no formato PKCS#12 (.pfx ou .p12).

### Ambiente

O pacote suporta dois ambientes:
- **HOMOLOGACAO**: Ambiente de testes
- **PRODUCAO**: Ambiente de produção

## Uso Básico

### Inicialização do Cliente

```php
use NfseNacional\Client\NfseClient;
use NfseNacional\Models\Enums\TipoAmbiente;

// Configuração do cliente
$client = new NfseClient(
    certificatePath: '/caminho/para/certificado.pfx',
    certificatePassword: 'senha_do_certificado',
    ambiente: TipoAmbiente::HOMOLOGACAO
);
```

### Consultar Documento Fiscal por NSU

```php
use NfseNacional\Client\NfseClient;
use NfseNacional\Models\Enums\TipoAmbiente;

$client = new NfseClient(
    certificatePath: '/caminho/para/certificado.pfx',
    certificatePassword: 'senha_do_certificado',
    ambiente: TipoAmbiente::HOMOLOGACAO
);

try {
    // Consulta por NSU
    $response = $client->consultarDFePorNSU(
        nsu: 123456,
        cnpjConsulta: '12345678000190', // Opcional
        lote: true // Default: true
    );

    // Verifica o status
    echo "Status: " . $response->statusProcessamento->value . "\n";
    echo "Ambiente: " . $response->tipoAmbiente->value . "\n";
    echo "Data/Hora: " . $response->dataHoraProcessamento->format('d/m/Y H:i:s') . "\n";

    // Processa os documentos encontrados
    if ($response->loteDFe) {
        foreach ($response->loteDFe as $dfe) {
            echo "NSU: " . $dfe->nsu . "\n";
            echo "Chave de Acesso: " . $dfe->chaveAcesso . "\n";
            echo "Tipo Documento: " . $dfe->tipoDocumento?->value . "\n";
        }
    }

    // Verifica alertas
    if ($response->alertas) {
        foreach ($response->alertas as $alerta) {
            echo "Alerta: " . $alerta->descricao . "\n";
        }
    }

    // Verifica erros
    if ($response->erros) {
        foreach ($response->erros as $erro) {
            echo "Erro: " . $erro->descricao . "\n";
        }
    }
} catch (\NfseNacional\Exceptions\ApiException $e) {
    echo "Erro na API: " . $e->getMessage() . "\n";
    echo "Status HTTP: " . $e->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
```

### Consultar Eventos por Chave de Acesso

```php
use NfseNacional\Client\NfseClient;
use NfseNacional\Models\Enums\TipoAmbiente;

$client = new NfseClient(
    certificatePath: '/caminho/para/certificado.pfx',
    certificatePassword: 'senha_do_certificado',
    ambiente: TipoAmbiente::HOMOLOGACAO
);

try {
    $chaveAcesso = '12345678901234567890123456789012345678901234';

    $response = $client->consultarEventosPorChaveAcesso($chaveAcesso);

    if ($response->loteDFe) {
        foreach ($response->loteDFe as $evento) {
            echo "Tipo Evento: " . $evento->tipoEvento?->value . "\n";
            echo "Data/Hora: " . $evento->dataHoraGeracao?->format('d/m/Y H:i:s') . "\n";

            // XML do evento (se disponível)
            if ($evento->arquivoXml) {
                echo "XML: " . $evento->arquivoXml . "\n";
            }
        }
    }
} catch (\NfseNacional\Exceptions\ApiException $e) {
    echo "Erro na API: " . $e->getMessage() . "\n";
}
```

### Emitir NFS-e

```php
use NfseNacional\Client\NfseClient;
use NfseNacional\Models\Enums\TipoAmbiente;
use NfseNacional\Utils\XmlHandler;
use NfseNacional\Utils\CompressionHandler;

$client = new NfseClient(
    certificatePath: '/caminho/para/certificado.pfx',
    certificatePassword: 'senha_do_certificado',
    ambiente: TipoAmbiente::HOMOLOGACAO
);

try {
    // 1. Preparar o XML da DPS (Declaração de Prestação de Serviços)
    // Nota: Este é um exemplo simplificado. O XML real deve seguir o schema oficial da NFS-e
    $dpsXml = '<?xml version="1.0" encoding="UTF-8"?>
<DPS xmlns="http://www.abrasf.org.br/nfse.xsd">
    <InfDPS Id="DPS001">
        <IdentificacaoDPS>
            <Numero>1</Numero>
            <Serie>1</Serie>
        </IdentificacaoDPS>
        <DataEmissao>2025-01-15T10:00:00</DataEmissao>
        <Prestador>
            <CpfCnpj>
                <Cnpj>12345678000190</Cnpj>
            </CpfCnpj>
            <RazaoSocial>Empresa Prestadora de Serviços LTDA</RazaoSocial>
        </Prestador>
        <Tomador>
            <CpfCnpj>
                <Cnpj>98765432000111</Cnpj>
            </CpfCnpj>
            <RazaoSocial>Cliente Tomador de Serviços LTDA</RazaoSocial>
        </Tomador>
        <Servico>
            <Valores>
                <ValorServicos>1000.00</ValorServicos>
            </Valores>
            <ItemListaServico>1401</ItemListaServico>
            <Discriminacao>Serviço de desenvolvimento de software</Discriminacao>
        </Servico>
    </InfDPS>
</DPS>';

    // 2. Validar o XML da DPS
    if (!XmlHandler::isValid($dpsXml)) {
        throw new \Exception("XML da DPS inválido");
    }

    // 3. Assinar o XML com certificado digital (XMLDSIG)
    // Nota: A assinatura XMLDSIG deve ser implementada usando robrichards/xmlseclibs
    // Este é um exemplo conceitual - a implementação real requer configuração adequada
    $dpsAssinado = $dpsXml; // Em produção, aqui seria a DPS assinada

    // 4. Comprimir e codificar em base64 (conforme padrão da API)
    $dpsComprimido = CompressionHandler::compressAndEncode($dpsAssinado);

    // 5. Enviar para emissão
    $nfse = $client->emitirNfse($dpsComprimido);

    // 6. Processar resultado
    echo "NFS-e emitida com sucesso!\n";
    echo "Chave de Acesso: " . $nfse->chaveAcesso . "\n";
    echo "Número: " . $nfse->numero . "\n";
    echo "Série: " . $nfse->serie . "\n";
    echo "Código de Verificação: " . $nfse->codigoVerificacao . "\n";
    echo "Data de Emissão: " . $nfse->dataEmissao->format('d/m/Y H:i:s') . "\n";
    echo "Situação: " . $nfse->situacao->value . "\n";

    // 7. Salvar XML da NFS-e (se disponível)
    if ($nfse->xml) {
        file_put_contents('nfse_' . $nfse->numero . '.xml', $nfse->xml);
        echo "XML salvo com sucesso\n";
    }

} catch (\NfseNacional\Exceptions\ApiException $e) {
    echo "Erro na API: " . $e->getMessage() . "\n";
    echo "Status HTTP: " . $e->getStatusCode() . "\n";
} catch (\NfseNacional\Exceptions\CertificateException $e) {
    echo "Erro no certificado: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
```

### Emitir Lote de NFS-e

```php
use NfseNacional\Client\NfseClient;
use NfseNacional\Models\Enums\TipoAmbiente;
use NfseNacional\Utils\CompressionHandler;

$client = new NfseClient(
    certificatePath: '/caminho/para/certificado.pfx',
    certificatePassword: 'senha_do_certificado',
    ambiente: TipoAmbiente::HOMOLOGACAO
);

try {
    // Preparar múltiplas DPS
    $dpsList = [];

    // DPS 1
    $dps1Xml = '<?xml version="1.0" encoding="UTF-8"?>...'; // XML da primeira DPS
    $dps1Assinado = $dps1Xml; // Assinar com certificado
    $dpsList[] = CompressionHandler::compressAndEncode($dps1Assinado);

    // DPS 2
    $dps2Xml = '<?xml version="1.0" encoding="UTF-8"?>...'; // XML da segunda DPS
    $dps2Assinado = $dps2Xml; // Assinar com certificado
    $dpsList[] = CompressionHandler::compressAndEncode($dps2Assinado);

    // Enviar lote
    $nfseList = $client->emitirLoteNfse($dpsList);

    // Processar resultados
    echo "Lote processado: " . count($nfseList) . " NFS-e emitidas\n";

    foreach ($nfseList as $nfse) {
        echo "NFS-e #" . $nfse->numero . " - Chave: " . $nfse->chaveAcesso . "\n";
    }

} catch (\NfseNacional\Exceptions\ApiException $e) {
    echo "Erro na API: " . $e->getMessage() . "\n";
}
```

### Informações do Certificado

```php
use NfseNacional\Client\NfseClient;
use NfseNacional\Models\Enums\TipoAmbiente;

$client = new NfseClient(
    certificatePath: '/caminho/para/certificado.pfx',
    certificatePassword: 'senha_do_certificado',
    ambiente: TipoAmbiente::HOMOLOGACAO
);

$certHandler = $client->getCertificateHandler();

// Verifica se o certificado é válido
if ($certHandler->isValid()) {
    echo "Certificado válido\n";
} else {
    echo "Certificado expirado\n";
}

// Obtém o CNPJ do certificado
$cnpj = $certHandler->getCnpj();
echo "CNPJ: " . $cnpj . "\n";

// Obtém informações completas
$info = $certHandler->getCertificateInfo();
print_r($info);
```

## Utilitários

### Manipulação de XML

```php
use NfseNacional\Utils\XmlHandler;

$xml = '<?xml version="1.0"?><root><item>test</item></root>';

// Valida XML
if (XmlHandler::isValid($xml)) {
    echo "XML válido\n";
}

// Carrega XML
$doc = XmlHandler::load($xml);

// Converte para string
$xmlString = XmlHandler::toString($doc, formatOutput: true);

// Remove declaração XML
$content = XmlHandler::removeDeclaration($xml);
```

### Compressão e Codificação

```php
use NfseNacional\Utils\CompressionHandler;

$data = 'Conteúdo para comprimir';

// Comprime e codifica em base64
$compressed = CompressionHandler::compressAndEncode($data);

// Decodifica e descomprime
$decompressed = CompressionHandler::decodeAndDecompress($compressed);

// Apenas codificação base64
$encoded = CompressionHandler::encodeBase64($data);
$decoded = CompressionHandler::decodeBase64($encoded);
```

## Modelos de Dados

### Enums

O pacote inclui os seguintes enums:

- `StatusProcessamentoDistribuicao`: Status do processamento
- `TipoDocumentoRequisicao`: Tipo de documento
- `TipoEvento`: Tipo de evento da NFS-e
- `TipoAmbiente`: Ambiente (PRODUCAO ou HOMOLOGACAO)

### Classes de Modelo

- `LoteDistribuicaoNSUResponse`: Resposta da API
- `DistribuicaoNSU`: Documento fiscal distribuído
- `MensagemProcessamento`: Mensagens de alerta ou erro

## Tratamento de Erros

O pacote utiliza uma hierarquia de exceções:

- `NfseException`: Exceção base
- `CertificateException`: Erros relacionados ao certificado
- `ApiException`: Erros na comunicação com a API
- `ValidationException`: Erros de validação

```php
try {
    $response = $client->consultarDFePorNSU(123456);
} catch (\NfseNacional\Exceptions\CertificateException $e) {
    // Erro no certificado
    echo "Erro no certificado: " . $e->getMessage();
} catch (\NfseNacional\Exceptions\ApiException $e) {
    // Erro na API
    echo "Erro na API: " . $e->getMessage();
    echo "Status HTTP: " . $e->getStatusCode();
} catch (\NfseNacional\Exceptions\NfseException $e) {
    // Outros erros
    echo "Erro: " . $e->getMessage();
}
```

## Testes

Execute os testes com PHPUnit:

```bash
vendor/bin/phpunit
```

## Configuração Avançada

### URL Base Customizada

```php
$client = new NfseClient(
    certificatePath: '/caminho/para/certificado.pfx',
    certificatePassword: 'senha_do_certificado',
    ambiente: TipoAmbiente::HOMOLOGACAO,
    baseUrl: 'https://api-customizada.nfse.gov.br'
);
```

### Opções HTTP Adicionais

```php
$client = new NfseClient(
    certificatePath: '/caminho/para/certificado.pfx',
    certificatePassword: 'senha_do_certificado',
    ambiente: TipoAmbiente::HOMOLOGACAO,
    httpOptions: [
        'timeout' => 60,
        'verify' => true,
        'headers' => [
            'User-Agent' => 'NfseNacional/1.0',
        ],
    ]
);
```

## Segurança

- O pacote utiliza autenticação mútua TLS 1.2+
- Certificados devem ser ICP-Brasil válidos
- Certificados são validados automaticamente
- Comunicação é realizada via HTTPS

## Suporte

Para questões, bugs ou sugestões, abra uma issue no [GitHub](https://github.com/gildonei/nfse-nacional).

## Licença

Este pacote está licenciado sob a [Licença MIT](LICENSE).

## Autor

**Gildonei M A Junior**
Email: gildonei.mendes@gmail.com

## Changelog

### 1.0.0
- Versão inicial
- Suporte a consulta por NSU
- Suporte a consulta de eventos por chave de acesso
- Gerenciamento de certificado digital ICP-Brasil
- Utilitários para XML e compressão

