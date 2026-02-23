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
        Schema::create('olheiro_shortlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('olheiro_id')->constrained('olheiros')->cascadeOnDelete();
            $table->string('nome', 120);
            $table->string('descricao', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olheiro_shortlists');
    }
};

