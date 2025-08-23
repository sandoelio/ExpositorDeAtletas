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

    public function create()
    {
        return view('atletas.create');
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
                'sexo' => 'required|string|in:Masculino,Feminino',
                'contato' => 'required|string|max:20',
                'posicao_jogo' => 'required|string|max:50',
                'cidade' => 'required|string|max:255',
                'entidade' => 'required|string|max:255',
                'imagem_base64' => 'nullable|image|max:2048',
                'resumo' => 'nullable|string|max:1000',
            ];

            // Definição das mensagens de erro personalizadas
            $messages = [
                'nome_completo.required' => 'O campo "Nome Completo" é obrigatório.',
                'data_nascimento.required' => 'O campo "Data de Nascimento" é obrigatório.',
                'altura.required' => 'O campo "Altura" é obrigatório.',
                'altura.numeric' => 'O campo "Altura" deve ser um número.',
                'peso.required' => 'O campo "Peso" é obrigatório.',
                'peso.numeric' => 'O campo "Peso" deve ser um número.',
                'sexo.required' => 'O campo "Sexo" é obrigatório.',
                'sexo.string' => 'O campo "Sexo" deve ser uma string.',
                'contato.required' => 'O campo "Contato" é obrigatório.',
                'posicao_jogo.required' => 'O campo "Posição no Jogo" é obrigatório.',
                'cidade.required' => 'O campo "Cidade" é obrigatório.',
                'cidade.string' => 'O campo "Cidade" deve ser uma string.',
                'entidade.required' => 'O campo "Entidade" é obrigatório.',
                'entidade.string' => 'O campo "Entidade" deve ser uma string.',
                'imagem_base64.image' => 'O arquivo enviado deve ser uma imagem.',
                'imagem_base64.max' => 'O tamanho da imagem não pode ser maior que 2MB.',
                'resumo.string' => 'O campo "Resumo" deve ser uma string.',
                'resumo.max' => 'O campo "Resumo" não pode ter mais de 1000 caracteres.',
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
            $this->atletaService->criarAtleta($validatedData);

            // Redireciona para a listagem com mensagem de sucesso
            return redirect()->back()->with('success', 'Atleta cadastrado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $ex) {
            // Captura erros de validação e retorna as mensagens
            return response()->json(['erro' => $ex->errors()], 422);
        } catch (\Exception $ex) {
            // Captura erros inesperados
            return response()->json(['erro' => 'Erro interno ao cadastrar atleta.', 'detalhes' => $ex->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $atleta = $this->atletaService->buscarPorId($id);

        if (!$atleta) {
            return redirect()->route('atletas.create')
                ->with('error', 'Atleta não encontrado.');
        }

        return view('atletas.edit', compact('atleta'));
    }

    public function update(Request $request, $id)
    {
        try {

            $data = $request->all();

            // Atualizando o atleta
            $this->atletaService->atualizarAtleta($id, $data);

            // Redireciona para a listagem com mensagem de sucesso
            return redirect()->back()->with('success', 'Atleta atualizado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return redirect()->back()->with('error', 'Erro ao atualizar atleta: ' . $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $exclusao = $this->atletaService->excluirAtleta($id);

            if ($exclusao) {
                return response()->json(['mensagem' => 'Atleta excluído com sucesso!']);
            }

            return response()->json(['erro' => 'Não foi possível excluir o atleta.'], 400);
        } catch (\Exception $ex) {
            return response()->json([
                'erro' => 'Erro interno ao excluir atleta.',
                'detalhes' => $ex->getMessage()
            ], 500);
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

    public function registrarVisualizacao($id)
    {
        try {
            $atleta = $this->atletaService->registrarVisualizacao($id);

            return response()->json([
                'status' => 'ok',
                'visualizacoes' => $atleta->visualizacoes
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'erro' => 'Erro ao registrar visualização.',
                'detalhes' => $ex->getMessage()
            ], 500);
        }
    }

}
