<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Atleta extends Model
{
    protected $table = 'atletas';

    protected $fillable = [
        'imagem_base64',
        'nome_completo',
        'data_nascimento',
        'idade',
        'sexo',
        'altura',
        'peso',
        'cidade',
        'entidade',
        'posicao_jogo',
        'contato',
        'resumo',
    ];

    /**
     * Mutator para data_nascimento.
     * Sempre que atribuirmos uma nova data de nascimento,
     * a coluna 'idade' será calculada e preenchida automaticamente.
     */
    public function setDataNascimentoAttribute($value)
    {
        // Armazena a data de nascimento
        $this->attributes['data_nascimento'] = $value;

        // Calcula a idade e preenche o atributo
        $this->attributes['idade'] = Carbon::parse($value)->age;
    }

    /**
     * Accessor para ler a idade diretamente.
     * Pode ser removido se você não precisar de lógica adicional
     * ao obter esse valor.
     */
    public function getIdadeAttribute($value)
    {
        return $value;
    }
}
