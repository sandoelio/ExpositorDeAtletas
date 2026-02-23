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
        Schema::create('olheiro_favoritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('olheiro_id')->constrained('olheiros')->cascadeOnDelete();
            $table->foreignId('atleta_id')->constrained('atletas')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['olheiro_id', 'atleta_id'], 'olheiro_favorito_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olheiro_favoritos');
    }
};

