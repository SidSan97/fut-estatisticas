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
        if($data['nome'] != '') {
            $mensalista = $this->mensalista->create([
                'nome'  => $data['nome'],
                'ativo' => $data['ativo'] ?? 0,
            ]);

            return $mensalista->id;
        }
    }

    public function atualizarMensalistas(array $data)
    {
        foreach($data['mensalistas'] as $item) {
            if(!isset($item['id'])) {
                $this->inserirJogador($item);

            } else {
                if($item['nome'] != '') {
                    $dadosMensalista = $this->mensalista->findOrFail($item['id']);

                    $dadosMensalista->update([
                        'nome'  => $item['nome'],
                        'ativo' => $item['ativo'],
                    ]);
                }
            }
        }
    }
}
