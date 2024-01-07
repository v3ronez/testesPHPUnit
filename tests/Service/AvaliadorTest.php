<?php

    namespace Alura\Leilao\Tests\Service;

    use Alura\Leilao\Model\Lance;
    use Alura\Leilao\Model\Leilao;
    use Alura\Leilao\Model\Usuario;
    use Alura\Leilao\Service\Avaliador;
    use PHPUnit\Framework\TestCase;

    class AvaliadorTest extends TestCase
    {
        public function testAvaliadorDeveValidarUltimoLance()
        {
            // Arrange - Given
            $leilao = new Leilao('Fiat 147 0KM');

            $maria = new Usuario('Maria');
            $joao = new Usuario('João');

            $leilao->recebeLance(new Lance($joao, 2000));
            $leilao->recebeLance(new Lance($maria, 2500));

            $leiloeiro = new Avaliador();
            // Act - When
            $leiloeiro->avalia($leilao);

            $maiorValor = $leiloeiro->getMaiorValor();

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

            $leiloeiro = new Avaliador();
            // Act - When
            $leiloeiro->avalia($leilao);

            $maiorValor = $leiloeiro->getMaiorValor();

            // Assert - Then
            $this->assertEquals(2500, $maiorValor);
        }
    }