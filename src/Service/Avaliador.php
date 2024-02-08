<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;

class Avaliador
{
    private $maiorValor = 0;
    private $maioresLances = [];

    public function avalia(Leilao $leilao): void
    {
        if (empty($leilao->getLances())) {
            throw new \DomainException('Não há lances');
        }

        foreach ($leilao->getLances() as $lance) {
            if ($lance->getValor() > $this->maiorValor) {
                $this->maiorValor = $lance->getValor();
            }
        }
        $lances = $leilao->getLances();
        usort(
            $lances,
            fn(Lance $lance1, Lance $lance2) => $lance2->getValor() - $lance1->getValor()
        );
        $this->maioresLances = array_slice($lances, 0, 3);
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }

    /**
     * @return Lance[]
     */
    public function getMaioresLances(): array
    {
        return $this->maioresLances;
    }
}
