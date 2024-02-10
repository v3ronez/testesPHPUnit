<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use PHPUnit\Framework\TestCase;

//class LeilaoDaoMock extends LeilaoDao
//{
//    private $leiloes = [];
//
//    public function salva(Leilao $leilao): void
//    {
//        $this->leiloes[] = $leilao;
//    }
//
//    public function recuperarNaoFinalizados(): array
//    {
//        return array_filter($this->leiloes, function (Leilao $leilao) {
//            return !$leilao->estaFinalizado();
//        });
//    }
//
//    /**
//     * @return Leilao[]
//     */
//    public function recuperarFinalizados(): array
//    {
//        return array_filter($this->leiloes, function (Leilao $leilao) {
//            return $leilao->estaFinalizado();
//        });
//    }
//
//    public function atualiza(Leilao $leilao)
//    {
//    }
//}

class EncerradorTest extends TestCase
{
    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $fiat147 = new Leilao(
            'Fiat 147 0Km',
            new \DateTimeImmutable('8 days ago')
        );
        $variant = new Leilao(
            'Variant 1972 0Km',
            new \DateTimeImmutable('10 days ago')
        );
        $leilaoDao = $this->createMock(LeilaoDao::class);
        $leilaoDao->salva($fiat147);
        $leilaoDao->salva($variant);

        $leilaoDao->method('recuperarNaoFinalizados')->willReturn([$fiat147, $variant]);
        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        $leilaoDao->method('recuperarFinalizados')->willReturn([$fiat147, $variant]);
        $leiloes = $leilaoDao->recuperarFinalizados();
        self::assertCount(2, $leiloes);
        self::assertTrue($leiloes[0]->estaFinalizado());
        self::assertTrue($leiloes[1]->estaFinalizado());
    }
}
