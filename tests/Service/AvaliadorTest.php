<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    public function testAvaliadorDeveValidarUltimoLance()
    {
        // Arrange - Given
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        $this->leiloeiro;
        // Act - When
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        // Assert - Then
        $this->assertEquals(2500, $maiorValor);
    }

    public function testAvaliadorDeveValidarLance()
    {
        // Arrange - Given
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));

        $this->leiloeiro;
        // Act - When
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        // Assert - Then
        $this->assertEquals(2500, $maiorValor);
    }

    public function testAvaliadorPega3ultimosLances()
    {
        $leilao = new Leilao('Fiat sla');
        $joao = new Usuario('joao');
        $maria = new Usuario('maria');
        $mario = new Usuario('mario');
        $jorge = new Usuario('jorge');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 500));
        $leilao->recebeLance(new Lance($mario, 3400));
        $leilao->recebeLance(new Lance($jorge, 4400));
        $avaliador = new Avaliador();

        $avaliador->avalia($leilao);
        $maiores = $avaliador->getMaioresLances();
        $this->assertCount(3, $maiores);
        $this->assertEquals(4400, $maiores[0]->getValor());
        $this->assertEquals(3400, $maiores[1]->getValor());
        $this->assertEquals(1000, $maiores[2]->getValor());
    }
}
