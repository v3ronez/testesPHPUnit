<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use Alura\Leilao\Service\EnviadorEmail;
use PHPUnit\Framework\MockObject\MockObject;
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
    private $encerrador;
    private $leilaoFiat;
    private $leilaoVariant;
    private $leilaoDao;

    /**
     * @var MockObject
     */
    private $enviadoDeEmail;

    protected function setUp(): void
    {
        $this->leilaoFiat = new Leilao(
            'Fiat 147 0Km',
            new \DateTimeImmutable('8 days ago')
        );
        $this->leilaoVariant = new Leilao(
            'Variant 1972 0Km',
            new \DateTimeImmutable('10 days ago')
        );
//        $leilaoDao = $this->createMock(\Alura\Leilao\Dao\Leilao::class);

        //mock tem que executar o contructor
        $this->leilaoDao = $this->getMockBuilder(\Alura\Leilao\Dao\Leilao::class)
            ->setConstructorArgs([new \PDO('sqlite::momery:')])->getMock();

        //mock tem que executar o contructor
        $this->leilaoDao->salva($this->leilaoFiat);
        $this->leilaoDao->salva($this->leilaoVariant);
        $this->leilaoDao->method('recuperarNaoFinalizados')
            ->willReturn([$this->leilaoFiat, $this->leilaoVariant]);
        $this->enviadoDeEmail = $this->createMock(EnviadorEmail::class);
        $this->encerrador = new Encerrador($this->leilaoDao, $this->enviadoDeEmail);
    }

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $this->encerrador->encerra();
        $this->leilaoDao->method('recuperarFinalizados')->willReturn([$this->leilaoFiat, $this->leilaoVariant]);
        $leiloes = $this->leilaoDao->recuperarFinalizados();
        self::assertCount(2, $leiloes);
        self::assertTrue($leiloes[0]->estaFinalizado());
        self::assertTrue($leiloes[1]->estaFinalizado());
    }

    public function testEncerradorDeveContinuarMesmoComErroEnvioDeEmail()
    {
        $e = new \DomainException('Erro ao enviado email');
        $this->enviadoDeEmail->expects($this->exactly(2))
            ->method('notificarTerminoLeilao')
            ->willThrowException($e);
        $this->encerrador->encerra();
    }
}
