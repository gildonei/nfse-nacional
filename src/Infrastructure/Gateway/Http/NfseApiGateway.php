<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Gateway\Http;

use NfseNacional\Application\Contract\Gateway\NfseGatewayInterface;
use NfseNacional\Application\Contract\Security\CertificateHandlerInterface;
use NfseNacional\Application\DTO\Response\DistribuicaoItemDTO;
use NfseNacional\Application\DTO\Response\LoteDistribuicaoDTO;
use NfseNacional\Application\DTO\Response\MensagemProcessamentoDTO;
use NfseNacional\Application\DTO\Response\NfseResponseDTO;
use NfseNacional\Domain\Entity\Dps;
use NfseNacional\Domain\Entity\Nfse;
use NfseNacional\Infrastructure\Compression\GzipCompressionHandler;
use NfseNacional\Infrastructure\Http\GuzzleHttpClient;
use NfseNacional\Infrastructure\Xml\DomXmlHandler;
use NfseNacional\Infrastructure\Xml\XmlSecLibsSigner;
use NfseNacional\Shared\Enum\StatusProcessamento;
use NfseNacional\Shared\Enum\TipoAmbiente;
use NfseNacional\Shared\Exception\ApiException;

/**
 * Implementação do Gateway para a API NFS-e Nacional
 */
class NfseApiGateway implements NfseGatewayInterface
{
    private GuzzleHttpClient $httpClient;
    private CertificateHandlerInterface $certificateHandler;
    private XmlSecLibsSigner $xmlSigner;
    private DomXmlHandler $xmlHandler;
    private GzipCompressionHandler $compressionHandler;

    public function __construct(
        CertificateHandlerInterface $certificateHandler,
        TipoAmbiente $ambiente = TipoAmbiente::HOMOLOGACAO
    ) {
        $this->certificateHandler = $certificateHandler;
        $this->httpClient = new GuzzleHttpClient($certificateHandler, $ambiente);
        $this->xmlSigner = new XmlSecLibsSigner();
        $this->xmlHandler = new DomXmlHandler();
        $this->compressionHandler = new GzipCompressionHandler();
    }

    /**
     * {@inheritdoc}
     */
    public function emitir(Dps $dps): NfseResponseDTO
    {
        try {
            // Converte DPS para XML (simplificado - na prática seria mais complexo)
            $xmlContent = $this->dpsToXml($dps);

            // Assina o XML
            $doc = $this->xmlHandler->fromString($xmlContent);
            $signedXml = $this->xmlSigner->sign(
                $doc,
                $this->certificateHandler->getCertificate(),
                $this->certificateHandler->getPrivateKey()
            );

            // Comprime e codifica
            $compressedXml = $this->compressionHandler->compressAndEncode($signedXml);

            // Envia para a API
            $response = $this->httpClient->post('NFSe', [
                'dpsXmlGZipB64' => $compressedXml,
            ]);

            return $this->parseNfseResponse($response);
        } catch (ApiException $e) {
            return NfseResponseDTO::error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function emitirLote(array $lote): NfseResponseDTO
    {
        try {
            $dpsXmlList = [];

            foreach ($lote as $dps) {
                $xmlContent = $this->dpsToXml($dps);
                $doc = $this->xmlHandler->fromString($xmlContent);
                $signedXml = $this->xmlSigner->sign(
                    $doc,
                    $this->certificateHandler->getCertificate(),
                    $this->certificateHandler->getPrivateKey()
                );
                $dpsXmlList[] = $this->compressionHandler->compressAndEncode($signedXml);
            }

            $response = $this->httpClient->post('NFSe/lote', [
                'dpsXmlGZipB64List' => $dpsXmlList,
            ]);

            return $this->parseNfseResponse($response);
        } catch (ApiException $e) {
            return NfseResponseDTO::error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function consultarPorChave(string $chaveAcesso): NfseResponseDTO
    {
        try {
            $response = $this->httpClient->get("NFSe/{$chaveAcesso}");
            return $this->parseNfseResponse($response);
        } catch (ApiException $e) {
            return NfseResponseDTO::error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function consultarPorNsu(int $nsu, ?string $cnpj = null, bool $lote = true): LoteDistribuicaoDTO
    {
        try {
            $queryParams = ['lote' => $lote ? 'true' : 'false'];
            if ($cnpj !== null) {
                $queryParams['cnpj'] = $cnpj;
            }

            $response = $this->httpClient->get("DFe/{$nsu}", $queryParams);
            return $this->parseLoteDistribuicaoResponse($response);
        } catch (ApiException $e) {
            return new LoteDistribuicaoDTO(
                status: StatusProcessamento::ERRO,
                ambiente: $this->httpClient->getAmbiente(),
                dataHoraProcessamento: new \DateTime(),
                erros: [new MensagemProcessamentoDTO('API_ERROR', $e->getMessage())]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function consultarEventos(string $chaveAcesso): LoteDistribuicaoDTO
    {
        try {
            $response = $this->httpClient->get("NFSe/{$chaveAcesso}/Eventos");
            return $this->parseLoteDistribuicaoResponse($response);
        } catch (ApiException $e) {
            return new LoteDistribuicaoDTO(
                status: StatusProcessamento::ERRO,
                ambiente: $this->httpClient->getAmbiente(),
                dataHoraProcessamento: new \DateTime(),
                erros: [new MensagemProcessamentoDTO('API_ERROR', $e->getMessage())]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cancelar(string $chaveAcesso, string $codigoCancelamento, string $motivo): NfseResponseDTO
    {
        try {
            $response = $this->httpClient->post("NFSe/{$chaveAcesso}/cancelar", [
                'codigoCancelamento' => $codigoCancelamento,
                'motivo' => $motivo,
            ]);

            return $this->parseNfseResponse($response);
        } catch (ApiException $e) {
            return NfseResponseDTO::error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function substituir(string $chaveAcessoOriginal, Dps $novaDps): NfseResponseDTO
    {
        try {
            $xmlContent = $this->dpsToXml($novaDps);
            $doc = $this->xmlHandler->fromString($xmlContent);
            $signedXml = $this->xmlSigner->sign(
                $doc,
                $this->certificateHandler->getCertificate(),
                $this->certificateHandler->getPrivateKey()
            );
            $compressedXml = $this->compressionHandler->compressAndEncode($signedXml);

            $response = $this->httpClient->post("NFSe/{$chaveAcessoOriginal}/substituir", [
                'dpsXmlGZipB64' => $compressedXml,
            ]);

            return $this->parseNfseResponse($response);
        } catch (ApiException $e) {
            return NfseResponseDTO::error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function manifestar(string $chaveAcesso, string $tipoManifestacao, ?string $motivo = null): NfseResponseDTO
    {
        try {
            $data = ['tipoManifestacao' => $tipoManifestacao];
            if ($motivo !== null) {
                $data['motivo'] = $motivo;
            }

            $response = $this->httpClient->post("NFSe/{$chaveAcesso}/manifestar", $data);
            return $this->parseNfseResponse($response);
        } catch (ApiException $e) {
            return NfseResponseDTO::error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function criarRascunho(Dps $dps): NfseResponseDTO
    {
        try {
            $response = $this->httpClient->post('rascunhos/DPS', $dps->toArray());
            return $this->parseNfseResponse($response);
        } catch (ApiException $e) {
            return NfseResponseDTO::error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function consultarRascunho(string $id): NfseResponseDTO
    {
        try {
            $response = $this->httpClient->get("rascunhos/DPS/{$id}");
            return $this->parseNfseResponse($response);
        } catch (ApiException $e) {
            return NfseResponseDTO::error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removerRascunho(string $id): bool
    {
        try {
            $this->httpClient->delete("rascunhos/DPS/{$id}");
            return true;
        } catch (ApiException) {
            return false;
        }
    }

    /**
     * Converte DPS para XML (implementação simplificada)
     */
    private function dpsToXml(Dps $dps): string
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = false;

        $dpsElement = $doc->createElement('DPS');
        $dpsElement->setAttribute('xmlns', 'http://www.abrasf.org.br/nfse.xsd');
        $doc->appendChild($dpsElement);

        $infDps = $doc->createElement('InfDPS');
        $infDps->setAttribute('Id', $dps->getId());
        $dpsElement->appendChild($infDps);

        // Identificação
        $identificacao = $doc->createElement('IdentificacaoDPS');
        $identificacao->appendChild($doc->createElement('Numero', $dps->numero));
        $identificacao->appendChild($doc->createElement('Serie', $dps->serie));
        $infDps->appendChild($identificacao);

        // Data de emissão
        $infDps->appendChild($doc->createElement('DataEmissao', $dps->dataEmissao->format('Y-m-d\TH:i:s')));

        return $doc->saveXML() ?: '';
    }

    /**
     * Parseia resposta de NFS-e
     *
     * @param array<string, mixed> $response
     */
    private function parseNfseResponse(array $response): NfseResponseDTO
    {
        $sucesso = isset($response['ChaveAcesso']) || ($response['sucesso'] ?? false);

        $nfse = null;
        if ($sucesso && isset($response['ChaveAcesso'])) {
            $nfse = Nfse::fromArray($response);
        }

        $alertas = [];
        if (isset($response['alertas'])) {
            foreach ($response['alertas'] as $alerta) {
                $alertas[] = MensagemProcessamentoDTO::fromArray($alerta);
            }
        }

        $erros = [];
        if (isset($response['erros'])) {
            foreach ($response['erros'] as $erro) {
                $erros[] = MensagemProcessamentoDTO::fromArray($erro);
            }
        }

        return new NfseResponseDTO(
            sucesso: $sucesso,
            nfse: $nfse,
            protocolo: $response['protocolo'] ?? null,
            alertas: $alertas,
            erros: $erros,
            dados: $response
        );
    }

    /**
     * Parseia resposta de distribuição
     *
     * @param array<string, mixed> $response
     */
    private function parseLoteDistribuicaoResponse(array $response): LoteDistribuicaoDTO
    {
        $status = StatusProcessamento::tryFrom($response['StatusProcessamento'] ?? 0)
            ?? StatusProcessamento::ERRO;

        $ambiente = TipoAmbiente::tryFrom($response['TipoAmbiente'] ?? 2)
            ?? TipoAmbiente::HOMOLOGACAO;

        $itens = [];
        if (isset($response['LoteDFe'])) {
            foreach ($response['LoteDFe'] as $item) {
                $itens[] = DistribuicaoItemDTO::fromArray($item);
            }
        }

        $alertas = [];
        if (isset($response['Alertas'])) {
            foreach ($response['Alertas'] as $alerta) {
                $alertas[] = MensagemProcessamentoDTO::fromArray($alerta);
            }
        }

        $erros = [];
        if (isset($response['Erros'])) {
            foreach ($response['Erros'] as $erro) {
                $erros[] = MensagemProcessamentoDTO::fromArray($erro);
            }
        }

        return new LoteDistribuicaoDTO(
            status: $status,
            ambiente: $ambiente,
            dataHoraProcessamento: isset($response['DataHoraProcessamento'])
                ? new \DateTime($response['DataHoraProcessamento'])
                : new \DateTime(),
            ultimoNsu: $response['UltimoNSU'] ?? null,
            maxNsu: $response['MaxNSU'] ?? null,
            itens: $itens,
            alertas: $alertas,
            erros: $erros
        );
    }
}

