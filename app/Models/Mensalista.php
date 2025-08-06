<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensalista extends Model
{
    use HasFactory;

    protected $table = "mensalistas";

    protected $fillable = [
        'nome',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'integer',
        ];
    }
}
