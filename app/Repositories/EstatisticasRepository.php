<?php

namespace App\Repositories;

use App\Models\EstatisticaPorJogo;
use App\Models\Jogo;
use App\Models\Mensalista;
use Illuminate\Support\Facades\DB;

class EstatisticasRepository {

    protected $jogo;
    protected $estatisticasPorJogo;
    protected $mensalista;

    public function __construct(
        Jogo $jogo,
        EstatisticaPorJogo $estatisticaPorJogo,
        Mensalista $mensalista
    )
    {
        $this->estatisticasPorJogo = $estatisticaPorJogo;
        $this->jogo = $jogo;
        $this->mensalista = $mensalista;
    }

    public function load(?int $ano = null)
    {
        $estatisticas = $this->mensalista::select([
            'mensalistas.nome', 'jogos.ano',
            DB::raw('SUM(estatisticas_por_jogo.gols) as total_gols'),
            DB::raw('SUM(estatisticas_por_jogo.assistencias) as total_assistencias'),
            DB::raw('SUM(estatisticas_por_jogo.cartao_amarelo) as total_amarelo'),
            DB::raw('SUM(estatisticas_por_jogo.cartao_vermelho) as total_vermelho'),
            DB::raw('SUM(estatisticas_por_jogo.cartao_azul) as total_azul'),
            DB::raw('SUM(estatisticas_por_jogo.gols_contra) as total_gols_contra'),
        ])
        ->join('estatisticas_por_jogo', 'mensalistas.id', '=', 'estatisticas_por_jogo.mensalista_id')
        ->join('jogos', 'estatisticas_por_jogo.jogo_id', '=', 'jogos.id')
        ->when($ano !== null, function ($query) use ($ano) {
            $query->where('jogos.ano', $ano);
        })
        ->groupBy('mensalistas.id', 'mensalistas.nome', 'jogos.ano')
        ->orderByDesc('total_gols')
        ->get();

        return $estatisticas;
    }

    public function pegarEstatisticasDoJogo(int $id)
    {
        $estatisticas = $this->mensalista::select([
            'mensalistas.nome', 'mensalistas.id as jogador_id', 'jogos.data_partida as data_partida',
            DB::raw('SUM(estatisticas_por_jogo.gols) as total_gols'),
            DB::raw('SUM(estatisticas_por_jogo.assistencias) as total_assistencias'),
            DB::raw('SUM(estatisticas_por_jogo.cartao_amarelo) as total_amarelo'),
            DB::raw('SUM(estatisticas_por_jogo.cartao_vermelho) as total_vermelho'),
            DB::raw('SUM(estatisticas_por_jogo.cartao_azul) as total_azul'),
            DB::raw('SUM(estatisticas_por_jogo.gols_contra) as total_gols_contra'),
        ])
        ->join('estatisticas_por_jogo', 'mensalistas.id', '=', 'estatisticas_por_jogo.mensalista_id')
        ->join('jogos', 'estatisticas_por_jogo.jogo_id', '=', 'jogos.id')
        ->where('jogos.id', $id)
        ->groupBy('mensalistas.id', 'mensalistas.nome', 'jogos.data_partida')
        ->orderByDesc('total_gols')
        ->get();

        return $estatisticas;
    }

    public function pegarJogoMaisRecente()
    {
        return $this->jogo::orderByDesc('id')->first();
    }

    public function inserirEstatisticas(array $data, int $jogoId)
    {
        foreach($data as $item) {
            $this->estatisticasPorJogo::create([
                'jogo_id' => $jogoId,
                'mensalista_id' => $item['jogador_id'],
                'gols' => $item['total_gols'] ?? 0,
                'gols_contra' => $item['total_gols_contra'] ?? 0,
                'assistencias' => $item['total_assistencias'] ?? 0,
                'cartao_amarelo' => $item['total_amarelo'] ?? 0,
                'cartao_vermelho' => $item['total_vermelho'] ?? 0,
                'cartao_azul' => $item['total_azul'] ?? 0,
            ]);
        }
    }

    public function atualizarEstatisticas(array $data, int $jogoId)
    {
        foreach($data as $item) {
            $existe = $this->estatisticasPorJogo::where('jogo_id', $jogoId)
                ->where('mensalista_id', $item['jogador_id'])
                ->exists();

            if ($existe) {
                $this->estatisticasPorJogo::where('jogo_id', $jogoId)
                    ->where('mensalista_id', $item['jogador_id'])
                    ->update([
                        'mensalista_id'   => $item['jogador_id'],
                        'gols'            => $item['total_gols'] ?? 0,
                        'gols_contra'     => $item['total_gols_contra'] ?? 0,
                        'assistencias'    => $item['total_assistencias'] ?? 0,
                        'cartao_amarelo'  => $item['total_amarelo'] ?? 0,
                        'cartao_vermelho' => $item['total_vermelho'] ?? 0,
                        'cartao_azul'     => $item['total_azul'] ?? 0,
                    ]);
            } else {
                $this->inserirEstatisticas([$item], $jogoId);
            }
        }
    }

    public function deletetarEstatistica(int $jogoId, int $mensalistaId)
    {
        $this->estatisticasPorJogo::where('jogo_id', $jogoId)
            ->where('mensalista_id', $mensalistaId)
            ->delete();
    }
}
