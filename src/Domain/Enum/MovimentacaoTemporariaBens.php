<?php

declare(strict_types=1);

namespace NfseNacional\Domain\Enum;

/**
 * Enum Vínculo da operação à movimentação temporária de bens
 *
 * Usado em `comExt/movTempBens`. Exportação de serviço que não movimenta
 * bem nenhum — software, consultoria — informa `Nao`.
 *
 * Valores conforme `TSMovTempBens` do schema da NFS-e Nacional.
 *
 * @package NfseNacional\Domain\Enum
 */
enum MovimentacaoTemporariaBens: string
{
    case Desconhecido = '0';
    case Nao = '1';
    case VinculadaDeclaracaoImportacao = '2';
    case VinculadaDeclaracaoExportacao = '3';

    /**
     * Retorna a descrição
     *
     * @return string
     */
    public function descricao(): string
    {
        return match ($this) {
            self::Desconhecido => 'Desconhecido (tipo não informado na nota de origem)',
            self::Nao => 'Não',
            self::VinculadaDeclaracaoImportacao => 'Vinculada - Declaração de Importação',
            self::VinculadaDeclaracaoExportacao => 'Vinculada - Declaração de Exportação',
        };
    }

    /**
     * Retorna o valor do enum
     *
     * @return string
     */
    public function valor(): string
    {
        return $this->value;
    }

    /**
     * Cria uma instância do enum a partir de um valor string
     *
     * @param string $valor
     * @return self
     * @throws \ValueError
     */
    public static function fromString(string $valor): self
    {
        return self::from($valor);
    }

    /**
     * Tenta criar uma instância do enum a partir de um valor string
     *
     * @param string $valor
     * @return self|null
     */
    public static function tryFromString(string $valor): ?self
    {
        return self::tryFrom($valor);
    }
}
