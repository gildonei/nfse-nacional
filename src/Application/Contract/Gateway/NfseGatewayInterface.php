<?php

declare(strict_types=1);

namespace NfseNacional\Application\Contract\Gateway;

use NfseNacional\Application\DTO\Response\LoteDistribuicaoDTO;
use NfseNacional\Application\DTO\Response\NfseResponseDTO;
use NfseNacional\Domain\Entity\Dps;

/**
 * Interface para o Gateway de comunicação com a API NFS-e Nacional
 */
interface NfseGatewayInterface
{
    /**
     * Emite uma NFS-e
     */
    public function emitir(Dps $dps): NfseResponseDTO;

    /**
     * Emite um lote de NFS-e
     *
     * @param Dps[] $lote
     */
    public function emitirLote(array $lote): NfseResponseDTO;

    /**
     * Consulta NFS-e por chave de acesso
     */
    public function consultarPorChave(string $chaveAcesso): NfseResponseDTO;

    /**
     * Consulta DFe por NSU
     */
    public function consultarPorNsu(int $nsu, ?string $cnpj = null, bool $lote = true): LoteDistribuicaoDTO;

    /**
     * Consulta eventos de uma NFS-e
     */
    public function consultarEventos(string $chaveAcesso): LoteDistribuicaoDTO;

    /**
     * Cancela uma NFS-e
     */
    public function cancelar(string $chaveAcesso, string $codigoCancelamento, string $motivo): NfseResponseDTO;

    /**
     * Substitui uma NFS-e
     */
    public function substituir(string $chaveAcessoOriginal, Dps $novaDps): NfseResponseDTO;

    /**
     * Manifesta sobre uma NFS-e (confirma/rejeita)
     */
    public function manifestar(string $chaveAcesso, string $tipoManifestacao, ?string $motivo = null): NfseResponseDTO;

    /**
     * Cria um rascunho de DPS
     */
    public function criarRascunho(Dps $dps): NfseResponseDTO;

    /**
     * Consulta um rascunho por ID
     */
    public function consultarRascunho(string $id): NfseResponseDTO;

    /**
     * Remove um rascunho
     */
    public function removerRascunho(string $id): bool;
}

