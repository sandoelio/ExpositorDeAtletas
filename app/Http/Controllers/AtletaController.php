<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Atleta;
use Illuminate\Http\Request;
use App\Services\AtletaService;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AtletasTemplateExport;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

use function Laravel\Prompts\error;

class AtletaController extends Controller
{
    protected $atletaService;

    public function __construct(AtletaService $atletaService)
    {
        $this->atletaService = $atletaService;
    }

    public function index(Request $request)
    {
        try {

            $query = Atleta::query();

            // aplica filtros se vierem na URL
            if ($request->filled('idade_min')) {
                $query->where('idade', '>=', (int)$request->idade_min);
            }
            if ($request->filled('idade_max')) {
                $query->where('idade', '<=', (int)$request->idade_max);
            }
            if ($request->filled('posicao')) {
                $query->where('posicao_jogo', $request->posicao);
            }
            if ($request->filled('cidade')) {
                $query->where('cidade', 'like', '%' . $request->cidade . '%');
            }
            if ($request->filled('entidade')) {
                $query->where('entidade', 'like', '%' . $request->entidade . '%');
            }
            if ($request->get('ordenar') === 'visualizacoes') {
                $query->orderByDesc('visualizacoes');
            } else {
                $query->orderBy('nome_completo', 'asc'); // padrão
            }

            // paginar com appends para manter os filtros
            $atletas = $query
                ->orderBy('nome_completo', 'asc')
                ->paginate(6)                      // 6 items por página
                ->appends($request->query());      // mantém ?idade_min=...&posicao=...

            // repovoa os selects
            $posicoes  = Atleta::select('posicao_jogo')->distinct()->get();
            $cidades   = Atleta::select('cidade')->distinct()->get();
            $entidades = Atleta::select('entidade')->distinct()->get();

            return view(
                'atletas.index',
                compact('atletas', 'posicoes', 'cidades', 'entidades'));

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
                return redirect()->back()->with('success', 'Atleta excluido com sucesso!');
            }

            return redirect()->back()->with('error','Não foi possível excluir o atleta.');
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

    public function showImportForm()
    {
        return view('atletas.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xls,xlsx',
        ]);

        $sheets   = Excel::toArray([], $request->file('file'));
        $rows     = $sheets[0];
        $header   = array_map('trim', array_shift($rows));
        $report   = ['created' => 0, 'ignored' => 0];
        $ignoredDetails = [];

        foreach ($rows as $index => $row) {
            $data     = array_combine($header, $row);
            $nome     = trim($data['nome_completo'] ?? '');
            $entidade = trim($data['entidade']     ?? '');

            // 1) Falta nome ou entidade
            if (!$nome || !$entidade) {
                $report['ignored']++;
                $ignoredDetails[] = [
                    'row'     => $index + 2, // +2 porque o array slice pula 1 header e index começa em 0
                    'nome'    => $nome ?: '(vazio)',
                    'entidade' => $entidade ?: '(vazio)',
                    'reason'  => 'Nome ou instituição ausente'
                ];
                continue;
            }

            // 2) Duplicata por nome+entidade
            $exists = Atleta::where('nome_completo', $nome)
                ->where('entidade',      $entidade)
                ->exists();
            if ($exists) {
                $report['ignored']++;
                $ignoredDetails[] = [
                    'row'      => $index + 2,
                    'nome'     => $nome,
                    'entidade' => $entidade,
                    'reason'   => 'Duplicata'
                ];
                continue;
            }

            // 3) Prepara atributos e cria
            $attrs = [
                'nome_completo'   => $nome,
                'entidade'        => $entidade,
                'data_nascimento' => $this->convertDate($data['data_nascimento'] ?? null),
                'altura'          => $data['altura']       ?? null,
                'peso'            => $data['peso']         ?? null,
                'sexo'            => $data['sexo']         ?? null,
                'cidade'          => $data['cidade']       ?? 'Indefinido',
                'posicao_jogo'    => $data['posicao_jogo'] ?? null,
                'contato'         => $data['contato']      ?? null,
                'resumo'          => $data['resumo']       ?? null,
                'imagem_base64'   => $data['imagem_base64'] ?? null,
            ];

            Atleta::create($attrs);
            $report['created']++;
        }

        return view('atletas.import-report', compact('report', 'ignoredDetails'));
    }

    /**
     * Transforma data Excel (serial ou string) em Y-m-d
     */
    private function convertDate($raw): ?string
    {
        if (!$raw) {
            return null;
        }

        // serial number do Excel
        if (is_numeric($raw)) {
            return ExcelDate::excelToDateTimeObject($raw)
                ->format('Y-m-d');
        }

        // string dd/mm/yyyy ou yyyy-mm-dd
        try {
            return Carbon::parse($raw)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    public function downloadTemplate()
    {
        return Excel::download(new AtletasTemplateExport, 'atletas_template.xlsx');
    }

    public function buscaAtletas(Request $request)
    {
        $texto = $request->texto;
        $entidade = $request->entidade;

        $query = Atleta::query();

        if ($texto) {
            $query->where('nome_completo', 'LIKE', "%{$texto}%");
        }

        if ($entidade) {
            $query->where('entidade', $entidade);
        }

        $atletas = $query->get();

        $dados = $atletas->map(function ($a) {
            return [
                'id' => $a->id,
                'nome_completo' => $a->nome_completo,
                'entidade' => $a->entidade,
                'edit_url' => route('atletas.edit', $a->id),
                'delete_url' => route('atletas.destroy', $a->id),
            ];
        });

        return response()->json($dados);
    }
}

