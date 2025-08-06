<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EstatisticaPorJogoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gols' => $this->faker->numberBetween(0, 5),
            'assistencias' => $this->faker->numberBetween(0, 5),
            'cartao_amarelo' => $this->faker->numberBetween(0, 2),
            'cartao_vermelho' => $this->faker->numberBetween(0, 1),
            'cartao_azul' => $this->faker->numberBetween(0, 1),
            'gols_contra' => $this->faker->numberBetween(0, 1),
        ];
    }
}
