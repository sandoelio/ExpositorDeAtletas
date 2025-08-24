<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('atletas', function (Blueprint $table) {
            $table->id();
            $table->longText('imagem_base64')->nullable(); // Campo para armazenar a imagem em Base64
            $table->string('nome_completo'); // Nome completo do atleta
            $table->date('data_nascimento'); // Data de nascimento
            $table->integer('idade')->nullable(); // Calculada posteriormente no Model
            $table->float('altura', 8, 2); // Altura em metros ou centímetros
            $table->float('peso', 8, 2); // Peso em kg
            $table->enum('sexo', ['masculino', 'feminino']); // Sexo do atleta
            $table->string('cidade')->default('Indefinido'); // Cidade do atleta
            $table->string('entidade')->default('Indefinido');// Escola ou instituição de treinamento
            $table->string('posicao_jogo'); // Posição que o atleta joga (Ex: Armador, Ala, Pivô)
            $table->string('contato'); // Contato telefônico ou e-mail
            $table->text('resumo')->nullable(); // Resumo ou biografia do atleta
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('atletas');
    }
};
