<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class AtletasTemplateExport implements FromArray, WithHeadings
{
    /**
     * Retorna as linhas de dados (aqui nenhuma, só o cabeçalho).
     */
    public function array(): array
    {
        return [];
    }

    /**
     * Define o cabeçalho do arquivo XLS(X).
     */
    public function headings(): array
    {
        return [
            'imagem_base64',
            'nome_completo',
            'data_nascimento',        
            'sexo',
            'altura',
            'peso',
            'cidade',
            'entidade',
            'posicao_jogo',
            'contato',
            'resumo'
        ];
    }
}
