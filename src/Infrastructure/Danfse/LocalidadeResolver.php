<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Danfse;

use NfseNacional\Domain\Danfse\DanfseException;

final class LocalidadeResolver
{
    private static ?array $municipios = null;
    private static ?array $paises = null;

    public function municipio(string $codigo): array
    {
        $dados = $this->municipios()[$codigo] ?? null;
        return is_array($dados) ? $dados : ['nome' => '', 'uf' => ''];
    }

    public function pais(string $codigo): string
    {
        return $this->paises()[strtoupper($codigo)] ?? '';
    }

    private function municipios(): array
    {
        return self::$municipios ??= $this->carregar('municipios-ibge.json');
    }

    private function paises(): array
    {
        return self::$paises ??= $this->carregar('paises-iso2.json');
    }

    private function carregar(string $arquivo): array
    {
        $caminho = dirname(__DIR__, 2) . '/Resources/danfse/' . $arquivo;
        $dados = is_file($caminho) ? json_decode((string) file_get_contents($caminho), true) : null;
        if (!is_array($dados)) {
            throw new DanfseException('Não foi possível carregar o domínio nacional ' . $arquivo . '.');
        }
        return $dados;
    }
}
