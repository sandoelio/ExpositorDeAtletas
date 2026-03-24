<?php

namespace Tests\Feature;

use App\Models\Atleta;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AtletaNomeFlexivelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::create('atletas', function (Blueprint $table) {
            $table->id();
            $table->text('imagem_base64')->nullable();
            $table->string('nome_completo')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->unsignedInteger('idade')->nullable();
            $table->string('sexo')->nullable();
            $table->string('altura')->nullable();
            $table->string('peso')->nullable();
            $table->string('cidade')->nullable();
            $table->string('entidade')->nullable();
            $table->string('posicao_jogo')->nullable();
            $table->string('contato')->nullable();
            $table->string('email')->nullable();
            $table->text('resumo')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('atletas');

        parent::tearDown();
    }

    public function test_normaliza_texto_de_busca(): void
    {
        $normalizado = Atleta::normalizarTextoBusca(
            "  Jo" . mb_chr(0x00E3, 'UTF-8') . "o  D'" . mb_chr(0x00C1, 'UTF-8') . "vila-Silva  "
        );

        $this->assertSame('joao d avila silva', $normalizado);
    }

    public function test_busca_flexivel_encontra_por_acento_caixa_trecho_e_ordem_dos_termos(): void
    {
        $joao = 'Jo' . mb_chr(0x00E3, 'UTF-8') . 'o da Silva';
        $maria = 'MARIA ' . mb_chr(0x00C9, 'UTF-8') . 'LIDA Souza';
        $eric = mb_chr(0x00C9, 'UTF-8') . 'ric Nunes';
        $joaoBuscaMaiuscula = 'JO' . mb_chr(0x00C3, 'UTF-8') . 'O';
        $ericBuscaMaiuscula = mb_chr(0x00C9, 'UTF-8') . 'R';

        Atleta::query()->create(['nome_completo' => $joao, 'entidade' => 'Base A']);
        Atleta::query()->create(['nome_completo' => $maria, 'entidade' => 'Base B']);
        Atleta::query()->create(['nome_completo' => 'Ana-Clara Lima', 'entidade' => 'Base C']);
        Atleta::query()->create(['nome_completo' => $eric, 'entidade' => 'Base D']);

        $this->assertSame([$joao], $this->buscarNomes('joao'));
        $this->assertSame([$joao], $this->buscarNomes($joaoBuscaMaiuscula));
        $this->assertSame([$joao], $this->buscarNomes('silva'));
        $this->assertSame([$joao], $this->buscarNomes('sil jo'));
        $this->assertSame([$joao], $this->buscarNomes('joaodasilva'));

        $this->assertSame([$maria], $this->buscarNomes('elida'));
        $this->assertSame([$maria], $this->buscarNomes('SOU'));
        $this->assertSame([$maria], $this->buscarNomes('sou maria'));

        $this->assertSame(['Ana-Clara Lima'], $this->buscarNomes('ana'));
        $this->assertSame(['Ana-Clara Lima'], $this->buscarNomes('clara'));
        $this->assertSame(['Ana-Clara Lima'], $this->buscarNomes('lima a'));

        $this->assertSame([$eric], $this->buscarNomes('eric'));
        $this->assertSame([$eric], $this->buscarNomes($ericBuscaMaiuscula));
    }

    private function buscarNomes(string $texto): array
    {
        return Atleta::query()
            ->buscarPorNomeFlexivel($texto)
            ->orderBy('id')
            ->pluck('nome_completo')
            ->all();
    }
}
