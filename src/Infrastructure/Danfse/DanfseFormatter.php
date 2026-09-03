<?php

declare(strict_types=1);

namespace NfseNacional\Infrastructure\Danfse;

use DateTimeImmutable;
use Exception;

final class DanfseFormatter
{
    public static function documento(string $valor): string
    {
        $numero = preg_replace('/\D+/', '', $valor) ?? '';

        return match (strlen($numero)) {
            11 => preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $numero) ?? $valor,
            14 => preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $numero) ?? $valor,
            default => $valor,
        };
    }

    public static function cep(string $valor): string
    {
        $numero = preg_replace('/\D+/', '', $valor) ?? '';
        return strlen($numero) === 8
            ? substr($numero, 0, 2) . '.' . substr($numero, 2, 3) . '-' . substr($numero, 5)
            : $valor;
    }

    public static function data(string $valor, bool $comHora = false): string
    {
        if ($valor === '') {
            return '-';
        }
        try {
            $data = new DateTimeImmutable($valor);
        } catch (Exception) {
            return $valor;
        }

        return $data->format($comHora ? 'd/m/Y H:i:s' : 'd/m/Y');
    }

    public static function moeda(string|int|float|null $valor): string
    {
        return $valor === null || $valor === '' ? '-' : 'R$ ' . number_format((float) $valor, 2, ',', '.');
    }

    public static function percentual(string|int|float|null $valor): string
    {
        return $valor === null || $valor === '' ? '-' : number_format((float) $valor, 2, ',', '.') . '%';
    }

    public static function texto(?string $valor, int $limite = 0): string
    {
        $valor = trim((string) $valor);
        if ($valor === '') {
            return '-';
        }
        if ($limite <= 0 || mb_strlen($valor) <= $limite) {
            return $valor;
        }

        return rtrim(mb_substr($valor, 0, max(0, $limite - 3))) . '...';
    }

    public static function endereco(array $pessoa): string
    {
        $partes = array_filter([
            $pessoa['logradouro'] ?? '',
            $pessoa['numero'] ?? '',
            $pessoa['complemento'] ?? '',
            $pessoa['bairro'] ?? '',
        ], static fn (string $valor): bool => trim($valor) !== '');

        return self::texto(implode(', ', $partes), 80);
    }

    public static function telefone(string $valor): string
    {
        $numero = preg_replace('/\D+/', '', $valor) ?? '';
        if (strlen($numero) === 10) {
            return sprintf('(%s) %s-%s', substr($numero, 0, 2), substr($numero, 2, 4), substr($numero, 6));
        }
        if (strlen($numero) === 11) {
            return sprintf('(%s) %s-%s', substr($numero, 0, 2), substr($numero, 2, 5), substr($numero, 7));
        }

        return $valor === '' ? '-' : $valor;
    }
}
