<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estatisticas_por_jogo', function (Blueprint $table) {
            // Chaves estrangeiras que formam a chave primária composta
            $table->foreignId('jogo_id')->constrained('jogos')->onDelete('cascade');
            $table->foreignId('mensalista_id')->constrained('mensalistas')->onDelete('restrict');

            // Colunas de estatísticas
            $table->unsignedSmallInteger('gols')->default(0);
            $table->unsignedSmallInteger('assistencias')->default(0);
            $table->tinyInteger('cartao_amarelo')->default(0);
            $table->tinyInteger('cartao_vermelho')->default(0);
            $table->tinyInteger('cartao_azul')->default(0);
            $table->unsignedSmallInteger('gols_contra')->default(0);

            // Chave primária composta
            $table->primary(['jogo_id', 'mensalista_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatisticas_por_jogo');
    }
};
