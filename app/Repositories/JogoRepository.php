<?php

namespace App\Repositories;

use App\Models\Jogo;

class JogoRepository {

    protected $jogo;

    public function __construct(Jogo $jogo)
    {
        $this->jogo = $jogo;
    }

    public function carregarListaJogos()
    {
        return $this->jogo->all();
    }

    public function registrarJogo(string $data)
    {
        $jogo = $this->jogo::create([
            'data_partida' => $data,
            'ano' => substr($data, 0, 4),
        ]);

        return $jogo->id;
    }
}