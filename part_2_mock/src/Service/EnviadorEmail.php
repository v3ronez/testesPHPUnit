<?php

declare(strict_types = 1);

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Leilao;

class EnviadorEmail
{


    public function notificarTerminoLeilao(Leilao $leilao)
    {
        $sucesso = mail(
            'usuario@email.com',
            'leilao finalizado',
            'o leilao '.$leilao->recuperarDescricao().' foi finalizado'
        );
        if (!$sucesso) {
            throw  new \DomainException('Erro ao enviado email');
        }
    }
}
