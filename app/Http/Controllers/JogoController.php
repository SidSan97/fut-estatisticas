<?php

namespace App\Http\Controllers;

use App\Models\Jogo;
use App\Repositories\MensalistasRepository;
use Exception;
use Illuminate\Http\Request;

class JogoController extends Controller
{
    protected $jogo;
    protected $mensalistaRepository;

    public function __construct(
        Jogo $jogo,
        MensalistasRepository $mensalistaRepository
    )
    {
        $this->jogo = $jogo;
        $this->mensalistaRepository = $mensalistaRepository;
    }

    public function store(Request $request)
    {
        try {
            $this->jogo->create([

            ]);
        } catch(Exception $e) {
            
        }
    }
}
