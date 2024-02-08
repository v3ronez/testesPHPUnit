<?php

namespace Models;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{

    /**
     * @dataProvider geraLances $leilao
     */
    public function testeLeilaoDeveReceberLances(int $qtdLance, Leilao $leilao, array $valores)
    {
        $joao = new Usuario('joao');
        $maria = new Usuario('maria');
        $leilao = new Leilao('carro foda');

        $leilao->recebeLance(new Lance($maria, 1000));
        $leilao->recebeLance(new Lance($joao, 3000));

        $this->assertCount(2, $leilao->getLances());
        foreach ($valores as $idx => $valor) {
            self::assertEquals($valor, $leilao->getLances()[$idx]->getValor());
        }
    }

    public function testLeilaoSoPodeReceberUmLancePorUsuarioPorVez()
    {
        $leilao = new Leilao('variante');
        $ana = new Usuario('ana');
        $lance = new Lance($ana, 1000);
        $lance1 = new Lance($ana, 2000);
        $leilao->recebeLance($lance);
        $this->expectException(\DomainException::class);
        $leilao->recebeLance($lance1);
        self::assertCount(1, $leilao->getLances());
        self::assertEquals(1000, $lance->getValor());
    }

    public function testLeilaoNaoDeveTerMaisDeCincoLancesPorPessoa()
    {
        $leilao = new Leilao('carro daora');
        $joao = new Usuario('joao');
        $maria = new Usuario('maria');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 2000));
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 4000));
        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($maria, 6000));
        $leilao->recebeLance(new Lance($joao, 7000));
        $leilao->recebeLance(new Lance($maria, 8000));
        $leilao->recebeLance(new Lance($joao, 9000));
        $leilao->recebeLance(new Lance($maria, 10000));

        $this->expectException(\DomainException::class);
        $leilao->recebeLance(new Lance($joao, 11000));

        self::assertCount(10, $leilao->getLances());
        self::assertEquals(10000, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }


    public function geraLances()
    {
        $joao = new Usuario('joao');
        $maria = new Usuario('maria');

        $leilao = new Leilao('carro foda');
        $leilao->recebeLance(new Lance($maria, 1000));
        $leilao->recebeLance(new Lance($joao, 3000));

        $leilao2 = new Leilao('moto daora');
        $leilao2->recebeLance(new Lance($joao, 1000));

        return [
            'dois lances' => [2, $leilao, [1000, 3000]],
            'um lance'    => [1, $leilao2, [1000]]
        ];
    }
}
