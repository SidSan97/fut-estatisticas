<?php

namespace App\Http\Controllers;

use App\Repositories\EstatisticasRepository;
use App\Repositories\JogoRepository;
use App\Repositories\MensalistasRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JogoController extends Controller
{
    protected $jogoRepository;
    protected $mensalistaRepository;
    protected $estatiscaRepository;

    public function __construct(
        JogoRepository $jogoRepository,
        MensalistasRepository $mensalistaRepository,
        EstatisticasRepository $estatiscaRepository,
    )
    {
        $this->jogoRepository = $jogoRepository;
        $this->mensalistaRepository = $mensalistaRepository;
        $this->estatiscaRepository = $estatiscaRepository;
    }

    public function store(Request $request)
    {
        try {
            $request = $request->all();
            $jogoId = $this->jogoRepository->registrarJogo($request['dataJogo']);

            foreach($request['jogo'] as &$item) {
                if($item['jogador_id'] === null) {
                    $mensalistaId = $this->mensalistaRepository->inserirJogador($item);
                    $item['jogador_id'] = $mensalistaId;
                }
            }

            $this->estatiscaRepository->inserirEstatisticas($request['jogo'], $jogoId);

            return response()->json(['message' => "Baba registrado com  sucesso!"], 201);

        } catch(Exception $e) {
            Log::error('Erro ao registrar baba.', [$e]);
            return  response()->json(['error' => "Não foi possivel registrar o jogo. Tente novamente ou chame o suporte."], 500);
        }
    }
}
