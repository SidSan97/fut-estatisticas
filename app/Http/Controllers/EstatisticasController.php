<?php

namespace App\Http\Controllers;

use App\Repositories\EstatisticasRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EstatisticasController extends Controller
{
    protected $estatisticasRepository;

    public function __construct(EstatisticasRepository $estatisticasRepository)
    {
        $this->estatisticasRepository = $estatisticasRepository;
    }

    public function index()
    {
        try {
            $data = $this->estatisticasRepository->load();

            return  response()->json(['data' => $data], 200);

        } catch(Exception $e) {
            Log::error('Erro ao carregar dados gerais', [$e]);
            return  response()->json(['error' => "Não foi possivel carregar dados gerais."], 500);
        }
    }

    public function obterDadosUltimaPartida()
    {
        try {
            $dadosUltimoJogo = $this->estatisticasRepository->pegarJogoMaisRecente();
            $data = $this->estatisticasRepository->pegarEstatisticasJogoMaisRecente($dadosUltimoJogo['id']);

            return response()->json(['data' => $data], 200);

        } catch(Exception $e) {
            Log::error('Erro ao carregar dados do ultimo jogo', [$e]);
            return  response()->json(['error' => "Não foi possivel carregar dados do ultimo jogo."], 500);
        }
    }
}
