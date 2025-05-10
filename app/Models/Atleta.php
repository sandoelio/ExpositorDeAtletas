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
        'cpf',
        'idade',
        'sexo',
        'altura',
        'peso',
        'cidade',
        'entidade',
        'posicao_jogo',
        'contato',
        'resumo'
    ];

    public function getIdadeAttribute()
    {
        return \Carbon\Carbon::parse($this->data_nascimento)->age;
    }
}
