<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstatisticaPorJogo extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'estatisticas_por_jogo';

    /**
     * Indica se o modelo deve ser timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * A chave primária da tabela, se não for "id".
     *
     * @var string|array
     */
    protected $primaryKey = ['jogo_id', 'jogador_id'];

    /**
     * Indica que a chave primária não é auto-increment.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Os atributos que são preenchíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'jogo_id',
        'mensalista_id',
        'gols',
        'assistencias',
        'cartao_amarelo',
        'cartao_vermelho',
        'cartao_azul',
        'gols_contra',
    ];

    /**
     * Define o relacionamento com o modelo Jogo.
     */
    public function jogo(): BelongsTo
    {
        return $this->belongsTo(Jogo::class, 'jogo_id');
    }

    /**
     * Define o relacionamento com o modelo Jogador.
     */
    public function mensalista(): BelongsTo
    {
        return $this->belongsTo(Mensalista::class, 'mensalista_id');
    }
}
