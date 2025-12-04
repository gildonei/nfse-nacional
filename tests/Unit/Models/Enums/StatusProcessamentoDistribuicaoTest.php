<?php

declare(strict_types=1);

namespace NfseNacional\Tests\Unit\Models\Enums;

use NfseNacional\Models\Enums\StatusProcessamentoDistribuicao;
use PHPUnit\Framework\TestCase;

class StatusProcessamentoDistribuicaoTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertEquals('REJEICAO', StatusProcessamentoDistribuicao::REJEICAO->value);
        $this->assertEquals('NENHUM_DOCUMENTO_LOCALIZADO', StatusProcessamentoDistribuicao::NENHUM_DOCUMENTO_LOCALIZADO->value);
        $this->assertEquals('DOCUMENTOS_LOCALIZADOS', StatusProcessamentoDistribuicao::DOCUMENTOS_LOCALIZADOS->value);
    }

    public function testEnumFrom(): void
    {
        $this->assertEquals(StatusProcessamentoDistribuicao::REJEICAO, StatusProcessamentoDistribuicao::from('REJEICAO'));
        $this->assertEquals(StatusProcessamentoDistribuicao::DOCUMENTOS_LOCALIZADOS, StatusProcessamentoDistribuicao::from('DOCUMENTOS_LOCALIZADOS'));
    }
}

