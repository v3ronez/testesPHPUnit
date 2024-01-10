<?php

namespace Models;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    public function testeLeilaoDeveReceberLances()
    {
        $joao = new Usuario('joao');
        $maria = new Usuario('maria');
        $leilao = new Leilao('carro foda');

        $leilao->recebeLance(new Lance($maria, 1000));
        $leilao->recebeLance(new Lance($joao, 3000));

        $this->assertCount(2, $leilao->getLances());
        $this->assertEquals(1000, $leilao->getLances()[0]->getValor());
        $this->assertEquals(3000, $leilao->getLances()[1]->getValor());
    }

    public function geraLances()
    {
        $joao = new Usuario('joao');
        $maria = new Usuario('maria');

        $leilao = new Leilao('carro foda');
        $leilao->recebeLance(new Lance($maria, 1000));
        $leilao->recebeLance(new Lance($joao, 3000));
    }
}
