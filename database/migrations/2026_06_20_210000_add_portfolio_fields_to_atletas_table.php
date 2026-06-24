<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atletas', function (Blueprint $table) {
            $table->string('nacionalidade', 80)->nullable()->after('email');
            $table->string('estilo_jogo', 120)->nullable()->after('nacionalidade');
            $table->text('perfil_profissional')->nullable()->after('estilo_jogo');
            $table->json('principais_qualidades')->nullable()->after('perfil_profissional');
            $table->json('portfolio_temporadas')->nullable()->after('principais_qualidades');
            $table->json('portfolio_conquistas')->nullable()->after('portfolio_temporadas');
            $table->json('portfolio_historico_clubes')->nullable()->after('portfolio_conquistas');
            $table->string('instagram', 120)->nullable()->after('portfolio_historico_clubes');
            $table->string('highlights_texto', 160)->nullable()->after('instagram');
        });
    }

    public function down(): void
    {
        Schema::table('atletas', function (Blueprint $table) {
            $table->dropColumn([
                'nacionalidade',
                'estilo_jogo',
                'perfil_profissional',
                'principais_qualidades',
                'portfolio_temporadas',
                'portfolio_conquistas',
                'portfolio_historico_clubes',
                'instagram',
                'highlights_texto',
            ]);
        });
    }
};
