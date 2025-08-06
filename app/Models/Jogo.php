<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    use HasFactory;

    protected $table = "jogos";

    protected $fillable = [
        'data_partida',
        'ano',
    ];

    protected function casts(): array
    {
        return [
            'data_partida' => 'date',
            'ano' => 'integer',
        ];
    }
}
