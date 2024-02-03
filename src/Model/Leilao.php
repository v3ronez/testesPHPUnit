<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->lanceRepetidoPorUsuario($lance)) {
            return;
        }
        $this->lances[] = $lance;
    }

    public function getLances(): array
    {
        return $this->lances;
    }

    public function lanceRepetidoPorUsuario(Lance $lance): bool
    {
        $ultimoUsuarioLance = array_slice($this->lances, -1)[0]->getUsuario();
        return $lance->getUsuario() === $ultimoUsuarioLance;
    }
}
