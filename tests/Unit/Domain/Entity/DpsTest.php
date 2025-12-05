<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Domain\Entity;

use NfseNacional\Domain\Entity\Dps;
use NfseNacional\Domain\Entity\Prestador;
use NfseNacional\Domain\Entity\Servico;
use NfseNacional\Domain\Entity\Tomador;
use NfseNacional\Domain\Exception\ValidationException;
use NfseNacional\Domain\ValueObject\Documento\Cnpj;
use PHPUnit\Framework\TestCase;

class DpsTest extends TestCase
{
    public function testCriarDpsValida(): void
    {
        $prestador = new Prestador(
            documento: new Cnpj('11222333000181'),
            razaoSocial: 'Empresa Prestadora LTDA'
        );

        $tomador = new Tomador(
            documento: new Cnpj('60701190000104'),
            razaoSocial: 'Cliente Tomador LTDA'
        );

        $servico = new Servico(
            itemListaServico: '1401',
            discriminacao: 'Serviço de desenvolvimento',
            valorServicos: 1000.00
        );

        $dps = new Dps(
            numero: '1',
            serie: '1',
            dataEmissao: new \DateTime(),
            prestador: $prestador,
            tomador: $tomador,
            servico: $servico
        );

        $this->assertEquals('1', $dps->numero);
        $this->assertEquals('1', $dps->serie);
        $this->assertInstanceOf(Prestador::class, $dps->prestador);
        $this->assertInstanceOf(Tomador::class, $dps->tomador);
        $this->assertEquals('DPS1', $dps->getId());
    }

    public function testValidarDpsValida(): void
    {
        $prestador = new Prestador(
            documento: new Cnpj('11222333000181'),
            razaoSocial: 'Empresa LTDA'
        );

        $tomador = new Tomador(
            documento: new Cnpj('60701190000104'),
            razaoSocial: 'Cliente LTDA'
        );

        $servico = new Servico(
            itemListaServico: '1401',
            discriminacao: 'Serviço',
            valorServicos: 1000.00
        );

        $dps = new Dps(
            numero: '1',
            serie: '1',
            dataEmissao: new \DateTime(),
            prestador: $prestador,
            tomador: $tomador,
            servico: $servico
        );

        $this->assertTrue($dps->validate());
    }

    public function testValidarDpsSemNumero(): void
    {
        $prestador = new Prestador(
            documento: new Cnpj('11222333000181'),
            razaoSocial: 'Empresa LTDA'
        );

        $tomador = new Tomador(
            documento: new Cnpj('60701190000104'),
            razaoSocial: 'Cliente LTDA'
        );

        $dps = new Dps(
            numero: '',
            serie: '1',
            dataEmissao: new \DateTime(),
            prestador: $prestador,
            tomador: $tomador,
            servico: ['itemListaServico' => '1401', 'valorServicos' => 1000]
        );

        $this->expectException(ValidationException::class);
        $dps->validate();
    }

    public function testToArray(): void
    {
        $prestador = new Prestador(
            documento: new Cnpj('11222333000181'),
            razaoSocial: 'Empresa LTDA'
        );

        $tomador = new Tomador(
            documento: new Cnpj('60701190000104'),
            razaoSocial: 'Cliente LTDA'
        );

        $servico = new Servico(
            itemListaServico: '1401',
            discriminacao: 'Serviço',
            valorServicos: 1000.00
        );

        $dps = new Dps(
            numero: '1',
            serie: '1',
            dataEmissao: new \DateTime('2024-01-01'),
            prestador: $prestador,
            tomador: $tomador,
            servico: $servico
        );

        $array = $dps->toArray();

        $this->assertArrayHasKey('numero', $array);
        $this->assertArrayHasKey('serie', $array);
        $this->assertArrayHasKey('prestador', $array);
        $this->assertArrayHasKey('tomador', $array);
        $this->assertArrayHasKey('servico', $array);
    }

    public function testFromArray(): void
    {
        $data = [
            'numero' => '1',
            'serie' => '1',
            'dataEmissao' => '2024-01-01',
            'prestador' => [
                'cnpj' => '11222333000181',
                'razaoSocial' => 'Empresa LTDA',
            ],
            'tomador' => [
                'cnpj' => '60701190000104',
                'razaoSocial' => 'Cliente LTDA',
            ],
            'servico' => [
                'itemListaServico' => '1401',
                'discriminacao' => 'Serviço',
                'valores' => ['valorServicos' => 1000.00],
            ],
        ];

        $dps = Dps::fromArray($data);

        $this->assertEquals('1', $dps->numero);
        $this->assertEquals('1', $dps->serie);
        $this->assertEquals('11222333000181', $dps->prestador->getCnpj());
    }
}

