<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private array $lances;
    private string $descricao;
    public bool $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance): void
    {
        if (!empty($this->lances) && $this->lanceRepetidoPorUsuario($lance)) {
            throw  new \DomainException('Usuário nao pode enviar dois lances seguidos');
        }

        $totalLanceUsuario = $this->quantidadeDeLancesPorUsuario($lance->getUsuario());

        if ($totalLanceUsuario >= 5) {
            throw  new \DomainException('Usuário pode dar no maximo 5 (cinco) lances');
        }
        $this->lances[] = $lance;
    }

    public function finalizado(Leilao $leilao)
    {
        $this->finalizado = true;
    }

    public function getLances(): array
    {
        return $this->lances;
    }

    public function lanceRepetidoPorUsuario(Lance $lance): bool
    {
//        $ultimoUsuarioLance = array_slice($this->lances, -1)[0]->getUsuario();
        $ultimoUsuarioLance = $this->lances[array_key_last($this->lances)]->getUsuario();
        return $lance->getUsuario() === $ultimoUsuarioLance;
    }

    private function quantidadeDeLancesPorUsuario(Usuario $usuario): int
    {
        return array_reduce(
            $this->lances,
            function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() === $usuario) {
                    return $totalAcumulado + 1;
                }
                return $totalAcumulado;
            },
            0
        );
    }
}
