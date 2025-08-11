<?php

namespace Database\Seeders;

use App\Models\EstatisticaPorJogo;
use App\Models\Jogo;
use App\Models\Mensalista;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabasePopulatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crie 20 jogadores
        $mensalistas = Mensalista::factory()->count(20)->create();

        // 2. Crie 5 jogos
        $jogos = Jogo::factory()->count(5)->create();

        // 3. Para cada jogo, crie estatísticas para um número aleatório de jogadores (ex: 10 a 15)
        $jogos->each(function ($jogo) use ($mensalistas) {
            // Escolha um subconjunto de jogadores para este jogo
            $jogadoresDoJogo = $mensalistas->random(rand(10, 15));

            $jogadoresDoJogo->each(function ($jogador) use ($jogo) {
                EstatisticaPorJogo::factory()->create([
                    'jogo_id' => $jogo->id,
                    'mensalista_id' => $jogador->id,
                ]);
            });
        });
    }
}
