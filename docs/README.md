# Documentação e Exemplos

Este diretório contém exemplos de uso da biblioteca NFS-e Nacional.

## Arquivos Disponíveis

### `emissao-dps.php`

Exemplo completo de como criar e gerar o XML de uma DPS (Documento de Prestação de Serviços).

**Como executar:**

1. Certifique-se de que todas as dependências estão instaladas:
   ```bash
   composer install
   ```

2. Execute o arquivo através de um servidor web (Apache/Nginx) ou via CLI:
   ```bash
   php docs/emissao-dps.php
   ```

**O que o exemplo demonstra:**

- ✅ Criação de Prestador de Serviços com CNPJ
- ✅ Criação de Tomador com CNPJ ou CPF
- ✅ Uso de Value Objects (Cnpj, Cpf, Email, Endereco, Telefone)
- ✅ Uso de Enum para Regime de Tributação
- ✅ Criação e configuração de DPS
- ✅ Geração de XML da DPS
- ✅ Validações automáticas

**Estrutura do Exemplo:**

1. **Criar Prestador**: Demonstra como criar um prestador com todos os dados necessários
2. **Criar Tomador**: Demonstra como criar um tomador (pessoa jurídica ou física)
3. **Criar DPS**: Demonstra como configurar todos os campos da DPS
4. **Gerar XML**: Demonstra como gerar o XML a partir da entidade DPS
5. **Exemplos Alternativos**: Mostra variações de uso (CPF, diferentes regimes, etc.)

## Estrutura de Uso Básica

```php
use NfseNacional\Domain\Entity\Dps;
use NfseNacional\Domain\Entity\Prestador;
use NfseNacional\Domain\Entity\Tomador;
use NfseNacional\Domain\Xml\DpsXml;

// 1. Criar Prestador
$prestador = new Prestador(
    nome: 'Empresa XYZ',
    documento: new Cnpj('12345678000190'),
    // ... outros campos
);

// 2. Criar Tomador
$tomador = new Tomador(
    nome: 'Cliente ABC',
    documento: new Cnpj('98765432000111'),
    // ... outros campos
);

// 3. Criar DPS
$dps = new Dps();
$dps->definirPrestador($prestador)
    ->definirTomador($tomador)
    ->definirTipoAmbiente(2)
    // ... outros campos obrigatórios
    ->definirValorServico(1000.00);

// 4. Gerar XML
$dpsXml = new DpsXml($dps);
$xmlString = $dpsXml->render();
```

## Campos Obrigatórios

### Prestador
- Nome
- Documento (CPF ou CNPJ)
- Endereço (rua, número, bairro, cidade, estado, CEP, código da cidade)
- Optante Simples Nacional (0 ou 1)
- Regime Especial de Tributação

### Tomador
- Nome
- Documento (CPF ou CNPJ)
- Endereço (rua, número, bairro, cidade, estado, CEP, código da cidade)

### DPS
- Prestador
- Tomador
- Tipo de Ambiente (1 = Produção, 2 = Homologação)
- Data e Hora de Emissão
- Versão da Aplicação
- Série
- Número da DPS
- Data de Competência
- Tipo de Emitente
- Código do Local de Emissão
- Código do Local de Prestação
- Código de Tributação Nacional
- Descrição do Serviço
- Valor do Serviço
- Tributação ISSQN

## Validações Automáticas

A biblioteca realiza validações automáticas em:

- **CPF/CNPJ**: Validação de dígitos verificadores e formatação
- **Email**: Validação de formato de email
- **Telefone**: Validação de formato
- **Endereço**: Validação de estados brasileiros
- **Valores**: Não permitem valores negativos
- **Campos Obrigatórios**: Validação na geração do XML

## Próximos Passos

Após gerar o XML:

1. **Assinar o XML** com certificado digital ICP-Brasil
2. **Enviar para a API** da NFS-e Nacional
3. **Processar a resposta** e obter a chave de acesso da NFS-e

## Referências

- [Documentação Oficial NFS-e Nacional](https://www.gov.br/receitafederal/pt-br)
- [Código do Município (viaCEP)](https://www.viacep.com.br)
- [Códigos de Tributação](https://www.gov.br/receitafederal/pt-br)

