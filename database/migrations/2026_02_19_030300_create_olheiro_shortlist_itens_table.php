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
        Schema::create('olheiro_shortlist_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shortlist_id')->constrained('olheiro_shortlists')->cascadeOnDelete();
            $table->foreignId('atleta_id')->constrained('atletas')->cascadeOnDelete();
            $table->string('status', 30)->default('observacao');
            $table->text('nota')->nullable();
            $table->timestamps();

            $table->unique(['shortlist_id', 'atleta_id'], 'shortlist_atleta_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olheiro_shortlist_itens');
    }
};

