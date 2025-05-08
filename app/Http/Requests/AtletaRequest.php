<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtletaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permite que todos os usuários enviem requisições
    }

    public function rules(): array
    {
        return [
            'nome_completo' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'altura' => 'required|numeric|min:0.50|max:2.50',
            'peso' => 'required|numeric|min:30|max:150',
            'cpf' => 'required|unique:atletas,cpf|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/',
            'contato' => 'required|string|max:20',
            'posicao_jogo' => 'required|string|max:50',
            'imagem_base64' => 'nullable|image|max:2048', // Opcional, apenas imagens
        ];
    }

    public function messages(): array
    {
        return [
            'nome_completo.required' => 'O campo "Nome Completo" é obrigatório.',
            'nome_completo.string' => 'O campo "Nome Completo" deve ser uma string.',
            'nome_completo.max' => 'O campo "Nome Completo" não pode ter mais de 255 caracteres.',
            'data_nascimento.required' => 'O campo "Data de Nascimento" é obrigatório.',
            'data_nascimento.date' => 'O campo "Data de Nascimento" deve ser uma data válida.',
            'altura.required' => 'O campo "Altura" é obrigatório.',
            'altura.numeric' => 'O campo "Altura" deve ser um número.',
            'altura.min' => 'O campo "Altura" deve ser no mínimo 0.50 metros.',
            'altura.max' => 'O campo "Altura" não pode ser maior que 2.50 metros.',
            'peso.required' => 'O campo "Peso" é obrigatório.',
            'peso.numeric' => 'O campo "Peso" deve ser um número.',
            'peso.min' => 'O campo "Peso" deve ser no mínimo 30kg.',
            'peso.max' => 'O campo "Peso" não pode ser maior que 150kg.',
            'cpf.required' => 'O campo "CPF" é obrigatório.',
            'cpf.unique' => 'O CPF informado já está cadastrado.',
            'cpf.regex' => 'O CPF deve estar no formato xxx.xxx.xxx-xx.',
            'contato.required' => 'O campo "Contato" é obrigatório.',
            'posicao_jogo.required' => 'O campo "Posição no Jogo" é obrigatório.',
            'imagem_base64.image' => 'O campo "Imagem" deve ser um arquivo de imagem.',
            'imagem_base64.max' => 'O arquivo "Imagem" deve ter no máximo 2MB.',
        ];
    }
}
