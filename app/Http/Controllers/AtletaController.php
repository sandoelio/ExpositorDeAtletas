<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AtletaService;
use App\Http\Controllers\Controller;


class AtletaController extends Controller
{
    protected $atletaService;

    public function __construct(AtletaService $atletaService)
    {
        $this->atletaService = $atletaService;
    }

    public function index()
    {
        try {

            $atletas = $this->atletaService->listarTodos();
            $posicoes = $this->atletaService->listarPosicoesUnicas();
            $cidades = $this->atletaService->listarCidadesUnicas();
            $entidades = $this->atletaService->listarEntidadesUnicas();
    
            return view('atletas.index', compact('atletas', 'posicoes', 'cidades', 'entidades')); // Retorna a view com a lista de atletas

        } catch (\Exception $ex) {
            return response()->json(['erro' => 'Erro ao carregar a lista de atletas.', 'detalhes' => $ex->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $atleta = $this->atletaService->buscarPorId($id);

            if (!$atleta) {
                return redirect()->route('atletas.index')->with('error', 'Atleta não encontrado.');
            }

            return view('atletas.show', compact('atleta')); // Retorna a view com detalhes
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Erro ao carregar atleta: ' . $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Definição das regras de validação
            $rules = [
                'nome_completo' => 'required|string|max:255',
                'data_nascimento' => 'required|date',
                'altura' => 'required|numeric|min:0.50|max:2.50',
                'peso' => 'required|numeric|min:30|max:150',
                'cpf' => 'required|unique:atletas,cpf|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/',
                'sexo' => 'required|string|in:masculino,feminino',
                'contato' => 'required|string|max:20',
                'posicao_jogo' => 'required|string|max:50',
                'cidade' => 'required|string|max:255', 
                'entidade' => 'required|string|max:255',
                'imagem' => 'nullable|image|max:2048', 
            ];

            // Definição das mensagens de erro personalizadas
            $messages = [
                'nome_completo.required' => 'O campo "Nome Completo" é obrigatório.',
                'data_nascimento.required' => 'O campo "Data de Nascimento" é obrigatório.',
                'altura.required' => 'O campo "Altura" é obrigatório.',
                'altura.numeric' => 'O campo "Altura" deve ser um número.',
                'peso.required' => 'O campo "Peso" é obrigatório.',
                'peso.numeric' => 'O campo "Peso" deve ser um número.',
                'cpf.required' => 'O campo "CPF" é obrigatório.',
                'cpf.unique' => 'O CPF informado já está cadastrado.',
                'cpf.regex' => 'O CPF deve estar no formato xxx.xxx.xxx-xx.',
                'sexo.required' => 'O campo "Sexo" é obrigatório.',
                'sexo.string' => 'O campo "Sexo" deve ser uma string.',
                'contato.required' => 'O campo "Contato" é obrigatório.',
                'posicao_jogo.required' => 'O campo "Posição no Jogo" é obrigatório.',
                'cidade.required' => 'O campo "Cidade" é obrigatório.',
                'cidade.string' => 'O campo "Cidade" deve ser uma string.',
                'entidade.required' => 'O campo "Entidade" é obrigatório.',
                'entidade.string' => 'O campo "Entidade" deve ser uma string.',
                'imagem.image' => 'O arquivo enviado deve ser uma imagem.',
                'imagem.max' => 'O tamanho da imagem não pode ser maior que 2MB.',
            ];

            // Aplicando a validação
            $validatedData = $request->validate($rules, $messages);

            // Convertendo imagem para Base64 se houver uma imagem
            $imagemBase64 = null;
            if ($request->hasFile('imagem')) {
                $imagemBase64 = base64_encode(file_get_contents($request->file('imagem')->getPathname()));
            }

            // Adicionando a imagem convertida aos dados validados
            $validatedData['imagem_base64'] = $imagemBase64;

            // Criando o atleta
            $atleta = $this->atletaService->criarAtleta($validatedData);

            // Redireciona para a listagem com mensagem de sucesso
            return redirect()->route('atletas.index')->with('success', 'Atleta cadastrado com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $ex) {
            // Captura erros de validação e retorna as mensagens
            return response()->json(['erro' => $ex->errors()], 422);
        } catch (\Exception $ex) {
            // Captura erros inesperados
            return response()->json(['erro' => 'Erro interno ao cadastrar atleta.', 'detalhes' => $ex->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Definição das regras de validação
            $rules = [
                'nome_completo' => 'sometimes|string|max:255',
                'data_nascimento' => 'sometimes|date',
                'altura' => 'sometimes|numeric|min:0.50|max:2.50',
                'peso' => 'sometimes|numeric|min:30|max:150',
                'cpf' => 'sometimes|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/' . '|unique:atletas,cpf,' . $id,
                'contato' => 'sometimes|string|max:20',
                'posicao_jogo' => 'sometimes|string|max:50',
                'cidade' => 'sometimes|string|max:255',
                'entidade' => 'sometimes|string|max:255',
                'imagem_base64' => 'nullable|string',
            ];
    
            // Definição das mensagens de erro personalizadas
            $messages = [
                'nome_completo.string' => 'O campo "Nome Completo" deve ser uma string.',
                'data_nascimento.date' => 'O campo "Data de Nascimento" deve ser uma data válida.',
                'altura.numeric' => 'O campo "Altura" deve ser um número.',
                'peso.numeric' => 'O campo "Peso" deve ser um número.',
                'cpf.regex' => 'O CPF deve estar no formato xxx.xxx.xxx-xx.',
                'cpf.unique' => 'O CPF informado já está cadastrado.',
                'contato.string' => 'O campo "Contato" deve ser uma string.',
                'posicao_jogo.string' => 'O campo "Posição no Jogo" deve ser uma string.',
                'cidade.required' => 'O campo "Cidade" é obrigatório.',
                'cidade.string' => 'O campo "Cidade" deve ser uma string.',
                'entidade.required' => 'O campo "Entidade" é obrigatório.',
                'entidade.string' => 'O campo "Entidade" deve ser uma string.',
                'imagem_base64.string' => 'O campo "Imagem" deve ser uma string Base64 válida.',
            ];
    
            // Aplicando a validação
            $validatedData = $request->validate($rules, $messages);
    
            // Atualizando o atleta
            $atletaAtualizado = $this->atletaService->atualizarAtleta($id, $validatedData);
    
            return response()->json([
                'mensagem' => 'Atleta atualizado com sucesso!',
                'atleta' => $atletaAtualizado
            ], 200);
    
        } catch (\Illuminate\Validation\ValidationException $ex) {
            // Captura erros de validação e retorna as mensagens
            return response()->json(['erro' => $ex->errors()], 422);
        } catch (\Exception $ex) {
            // Captura erros inesperados
            return response()->json(['erro' => 'Erro interno ao atualizar atleta.', 'detalhes' => $ex->getMessage()], 500);
        }
    }
    

    public function destroy($id)
    {
        try {
            $atleta = $this->atletaService->buscarPorId($id);

            if (!$atleta) {
                return response()->json(['erro' => 'Atleta não encontrado.'], 404);
            }

            $exclusao = $this->atletaService->excluirAtleta($id);

            if ($exclusao) {
                return response()->json(['mensagem' => 'Atleta excluído com sucesso!'], 200);
            } else {
                return response()->json(['erro' => 'Erro ao excluir atleta.'], 500);
            }
        } catch (\Exception $ex) {
            return response()->json(['erro' => 'Erro interno ao excluir atleta.', 'detalhes' => $ex->getMessage()], 500);
        }
        
    }
    
    public function buscar(Request $request)
    {
        try {
            $atletas = $this->atletaService->buscarAtletas($request);
            
            return response()->json($atletas);
        } catch (\Exception $ex) {
            return response()->json([
                'erro' => 'Erro ao buscar atletas.',
                'detalhes' => $ex->getMessage()
            ], 500);
        }
    }

    public function buscarPorCpf(Request $request)
    {
        try {
            $atleta = $this->atletaService->buscarPorCpf($request);
            
            if (!$atleta) {
                return response()->json(['erro' => 'Atleta não encontrado.'], 404);
            }

            return response()->json($atleta);

        } catch (\Exception $ex) {
            return response()->json(['erro' => 'Erro ao buscar atleta.', 'detalhes' => $ex->getMessage()], 500);
        }
    }

}
