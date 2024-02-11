<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;

class Encerrador
{
    private $dao;
    private EnviadorEmail $enviado;

    public function __construct(LeilaoDao $dao, EnviadorEmail $enviado)
    {
        $this->dao = $dao;
        $this->enviado = $enviado;
    }

    public function encerra()
    {
        $leiloes = $this->dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            try {
                if ($leilao->temMaisDeUmaSemana()) {
                    $leilao->finaliza();
                    $this->dao->atualiza($leilao);
                    $this->enviado->notificarTerminoLeilao($leilao);
                }
            } catch (\DomainException $e) {
                error_log($e->getMessage());
            }
        }
    }
}
