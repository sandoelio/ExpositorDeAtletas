<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('olheiros', function (Blueprint $table) {
            $table->boolean('aprovado')->default(false)->after('password');
            $table->timestamp('aprovado_em')->nullable()->after('aprovado');
        });

        // Mantem acesso dos olheiros ja existentes no sistema.
        DB::table('olheiros')->update([
            'aprovado' => true,
            'aprovado_em' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('olheiros', function (Blueprint $table) {
            $table->dropColumn(['aprovado', 'aprovado_em']);
        });
    }
};

