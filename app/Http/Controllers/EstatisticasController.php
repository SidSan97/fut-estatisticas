<?php

namespace App\Http\Controllers;

use App\Repositories\EstatisticasRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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
            $data = $this->estatisticasRepository->pegarEstatisticasDoJogo($dadosUltimoJogo['id']);

            return response()->json(['data' => $data], 200);

        } catch(Exception $e) {
            Log::error('Erro ao carregar dados do ultimo jogo', [$e]);
            return  response()->json(['error' => "Não foi possivel carregar dados do ultimo jogo."], 500);
        }
    }

    public function obterDadosPorAno(int $ano)
    {
        try {
            $data = $this->estatisticasRepository->load($ano);

            return response()->json(['data' => $data], 200);

        } catch(Exception $e) {
            Log::error('Erro ao carregar dados anual', [$e]);
            return  response()->json(['error' => "Não foi possivel carregar dados anual."], 500);
        }
    }

    public function obterDadosDaPartida(int $id)
    {
        try {
            $data = $this->estatisticasRepository->pegarEstatisticasDoJogo($id);

            return Inertia::render('auth/EditMatch', [
                'jogo' => $data
            ]);

        } catch(Exception $e) {
            Log::error('Erro ao carregar dados do jogo', [$e]);
            return  response()->json(['error' => "Não foi possivel carregar dados do jogo."], 500);
        }
    }

    public function filtrarPorAno(int $ano)
    {
        try {
            $data = $this->estatisticasRepository->load($ano);

            return response()->json(['data' => $data], 200);

        } catch(Exception $e) {
            Log::error('Erro ao carregar dados do ano filtrado', [$e]);
            return  response()->json(['error' => "Não foi possivel carregar dados do ano filtrado."], 500);
        }
    }

    public function store(array $data, int $jogoId)
    {
        try {
            $data = $this->estatisticasRepository->inserirEstatisticas($data, $jogoId);

            return response()->json(['message' => "Estatística registrada com  sucesso!"], 201);

        } catch(Exception $e) {
            Log::error('Erro ao registrar estatística.', [$e]);
            return  response()->json(['error' => "Não foi possivel registrar a estatística. Tente novamente ou chame o suporte."], 500);
        }
    }
}
