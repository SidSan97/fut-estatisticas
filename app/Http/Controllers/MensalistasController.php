<?php

namespace App\Http\Controllers;

use App\Repositories\MensalistasRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MensalistasController extends Controller
{
    protected $mensalistaRepository;

    public function __construct(MensalistasRepository $mensalistaRepository)
    {
        $this->mensalistaRepository = $mensalistaRepository;
    }

    public function index()
    {
        try {
            $data = $this->mensalistaRepository->obterMensalistas();
            return response()->json(['data' => $data], 200);
        } catch(Exception $e) {
            Log::error('Erro ao carregar lista de mensalistas', [$e]);
            return  response()->json(['error' => "Não foi possivel carregar lista de mensalistas."], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->mensalistaRepository->atualizarMensalistas($data);
            return response()->json(['message' => "Dados atualizados com sucesso!"], 200);

        } catch(Exception $e) {
            Log::error('Erro ao atualizar lista de mensalistas', [$e]);
            return  response()->json(['error' => "Não foi possivel atualizar lista de mensalistas."], 500);
        }
    }
}
