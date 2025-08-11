<?php

namespace App\Repositories;

use App\Models\Mensalista;

class MensalistasRepository {

    protected $mensalista;

    public function __construct (Mensalista $mensalista)
    {
        $this->mensalista = $mensalista;
    }

    public function obterMensalistas()
    {
        return $this->mensalista->all();
    }

    public function inserirJogador(array $data) {

    }

    public function atualizarMensalistas(array $data)
    {
        foreach($data as $item) {
            if(!isset($item['id'])) {

            }
        }
    }
}
