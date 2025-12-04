<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Models;

use NfseNacional\Models\MensagemProcessamento;
use PHPUnit\Framework\TestCase;

class MensagemProcessamentoTest extends TestCase
{
    public function testFromArray(): void
    {
        $data = [
            'Mensagem' => 'Teste',
            'Parametros' => ['param1', 'param2'],
            'Codigo' => '001',
            'Descricao' => 'Descrição do erro',
            'Complemento' => 'Complemento adicional',
        ];

        $mensagem = MensagemProcessamento::fromArray($data);

        $this->assertEquals('Teste', $mensagem->mensagem);
        $this->assertEquals(['param1', 'param2'], $mensagem->parametros);
        $this->assertEquals('001', $mensagem->codigo);
        $this->assertEquals('Descrição do erro', $mensagem->descricao);
        $this->assertEquals('Complemento adicional', $mensagem->complemento);
    }

    public function testToArray(): void
    {
        $mensagem = new MensagemProcessamento(
            mensagem: 'Teste',
            parametros: ['param1'],
            codigo: '001',
            descricao: 'Descrição',
            complemento: 'Complemento'
        );

        $array = $mensagem->toArray();

        $this->assertArrayHasKey('Mensagem', $array);
        $this->assertArrayHasKey('Codigo', $array);
        $this->assertEquals('Teste', $array['Mensagem']);
    }
}

