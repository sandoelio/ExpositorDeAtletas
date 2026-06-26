<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Atleta;
use Illuminate\Http\Request;
use App\Services\AtletaService;
use App\Services\PerfilAtletaService;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\AtletasTemplateExport;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AtletaController extends Controller
{
    private const MAX_PORTFOLIO_TEMPORADAS = 2;
    private const MAX_PORTFOLIO_CONQUISTAS = 3;
    private const MAX_PORTFOLIO_HISTORICO = 7;

    protected $atletaService;
    protected $perfilAtletaService;

    public function __construct(AtletaService $atletaService, PerfilAtletaService $perfilAtletaService)
    {
        $this->atletaService = $atletaService;
        $this->perfilAtletaService = $perfilAtletaService;
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
            if ($request->filled('nome')) {
                $query->buscarPorNomeFlexivel($request->nome);
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
                ->paginate(6)                      // fixo: 6 por pagina
                ->appends($request->query());      // mantém filtros da URL

            // repovoa os selects
            $posicoes  = Atleta::select('posicao_jogo')->distinct()->get();
            $cidades   = Atleta::select('cidade')->distinct()->get();
            $entidades = Atleta::select('entidade')->distinct()->get();

            $top10Visualizados = DB::table('atletas')
                ->whereNotNull('visualizacoes')
                ->orderByDesc('visualizacoes')
                ->limit(10)
                ->pluck('id')
                ->toArray();

            return view(
                'atletas.index',
                compact('atletas', 'posicoes', 'cidades', 'entidades', 'top10Visualizados'));

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

            $dadosPerfil = $this->perfilAtletaService->montarDados($atleta);
            return view('atletas.perfil', $dadosPerfil);
        } catch (\Exception $ex) {
            return redirect()->route('atletas.index')->with('error', 'Erro ao carregar atleta: ' . $ex->getMessage());
        }
    }

    public function portfolio($id)
    {
        try {
            $atleta = $this->atletaService->buscarPorId($id);

            if (!$atleta) {
                return redirect()->route('atletas.index')->with('error', 'Atleta nao encontrado.');
            }

            $dadosPerfil = $this->perfilAtletaService->montarDados($atleta);
            $perfil = $dadosPerfil['atleta'];

            return view('atletas.portfolio', [
                'atletaModel' => $atleta,
                'atleta' => $perfil,
                'temporadas' => $this->obterTemporadasPortfolio($atleta),
                'qualidades' => $this->obterQualidadesPortfolio($atleta),
                'conquistas' => $this->obterConquistasPortfolio($atleta),
                'historicoClubes' => $this->obterHistoricoClubesPortfolio($atleta),
                'perfilProfissional' => $atleta->perfil_profissional ?: $perfil['bio'],
                'highlightsTexto' => $atleta->highlights_texto ?: 'Highlights disponiveis sob demanda',
                'instagram' => $atleta->instagram ?: null,
            ]);
        } catch (\Exception $ex) {
            return redirect()->route('atletas.index')->with('error', 'Erro ao carregar portfolio: ' . $ex->getMessage());
        }
    }

    public function ogImage($id)
    {
        $atleta = Atleta::findOrFail($id);
        $cacheControl = 'no-cache, max-age=0, must-revalidate';

        $raw = trim((string) ($atleta->imagem_base64 ?? ''));
        if ($raw !== '') {
            $mime = null;
            $base64 = $raw;

            if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/', $raw, $matches)) {
                $mime = strtolower(trim((string) ($matches[1] ?? '')));
                $base64 = (string) ($matches[2] ?? '');
            }

            $base64 = preg_replace('/\s+/', '', $base64);
            $binary = base64_decode($base64, true);

            if ($binary !== false) {
                if (!$mime) {
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $detected = (string) $finfo->buffer($binary);
                    $mime = str_starts_with($detected, 'image/') ? $detected : 'image/png';
                }

                return response($binary, 200, [
                    'Content-Type' => $mime,
                    'Cache-Control' => $cacheControl,
                ]);
            }
        }

        $avatarPath = public_path('img/avatar.png');
        $logoPath = public_path('img/LOGO1.png');
        $fallbackPath = file_exists($avatarPath) ? $avatarPath : $logoPath;

        if (file_exists($fallbackPath)) {
            return response()->file($fallbackPath, [
                'Cache-Control' => $cacheControl,
            ]);
        }

        abort(404);
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
                'email' => 'nullable|email|max:255',
                'posicao_jogo' => 'required|string|max:50',
                'cidade' => 'required|string|max:255',
                'entidade' => 'required|string|max:255',
                'imagem' => 'nullable|image|max:2048',
                'resumo' => 'nullable|string|max:1000',
                'nacionalidade' => 'nullable|string|max:80',
                'estilo_jogo' => 'nullable|string|max:120',
                'perfil_profissional' => 'nullable|string|max:2000',
                'principais_qualidades_texto' => 'nullable|string|max:2000',
                'portfolio_temporadas_texto' => 'nullable|string|max:5000',
                'portfolio_conquistas_texto' => 'nullable|string|max:7000',
                'portfolio_historico_clubes_texto' => 'nullable|string|max:5000',
                'instagram' => 'nullable|string|max:120',
                'highlights_texto' => 'nullable|string|max:160',
                'temporadas.equipe' => 'nullable|array|max:' . self::MAX_PORTFOLIO_TEMPORADAS,
                'conquistas.equipe' => 'nullable|array|max:' . self::MAX_PORTFOLIO_CONQUISTAS,
                'historico.ano' => 'nullable|array|max:' . self::MAX_PORTFOLIO_HISTORICO,
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
                'email.email' => 'O campo "Email" deve ser um e-mail valido.',
                'posicao_jogo.required' => 'O campo "Posição no Jogo" é obrigatório.',
                'cidade.required' => 'O campo "Cidade" é obrigatório.',
                'cidade.string' => 'O campo "Cidade" deve ser uma string.',
                'entidade.required' => 'O campo "Entidade" é obrigatório.',
                'entidade.string' => 'O campo "Entidade" deve ser uma string.',
                'imagem.image' => 'O arquivo enviado deve ser uma imagem.',
                'imagem.max' => 'O tamanho da imagem não pode ser maior que 2MB.',
                'resumo.string' => 'O campo "Resumo" deve ser uma string.',
                'resumo.max' => 'O campo "Resumo" não pode ter mais de 1000 caracteres.',
            ];

            // Aplicando a validação
            $validatedData = $request->validate($rules, $messages);
            $validatedData['email'] = !empty($validatedData['email'])
                ? strtolower(trim((string) $validatedData['email']))
                : null;
            $validatedData = $this->normalizarDadosPortfolio($validatedData, $request);

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
            $request->validate([
                'email' => 'nullable|email|max:255',
                'nacionalidade' => 'nullable|string|max:80',
                'estilo_jogo' => 'nullable|string|max:120',
                'perfil_profissional' => 'nullable|string|max:2000',
                'principais_qualidades_texto' => 'nullable|string|max:2000',
                'portfolio_temporadas_texto' => 'nullable|string|max:5000',
                'portfolio_conquistas_texto' => 'nullable|string|max:7000',
                'portfolio_historico_clubes_texto' => 'nullable|string|max:5000',
                'instagram' => 'nullable|string|max:120',
                'highlights_texto' => 'nullable|string|max:160',
                'imagem' => 'nullable|image|max:2048',
                'temporadas.equipe' => 'nullable|array|max:' . self::MAX_PORTFOLIO_TEMPORADAS,
                'conquistas.equipe' => 'nullable|array|max:' . self::MAX_PORTFOLIO_CONQUISTAS,
                'historico.ano' => 'nullable|array|max:' . self::MAX_PORTFOLIO_HISTORICO,
            ]);

            $data = $request->all();
            $data['email'] = $request->filled('email')
                ? strtolower(trim((string) $request->input('email')))
                : null;
            $data = $this->normalizarDadosPortfolio($data, $request);

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

    public function registrarVisualizacao(Request $request, $id)
    {
        try {
            $resultado = $this->atletaService->registrarVisualizacao(
                $id,
                (string) $request->session()->getId(),
                (string) $request->ip()
            );

            $atleta = $resultado['atleta'];
            return response()->json([
                'status' => 'ok',
                'visualizacoes' => $atleta->visualizacoes,
                'counted' => (bool) ($resultado['counted'] ?? false),
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
            $emailImport = !empty($data['email']) ? strtolower(trim((string) $data['email'])) : null;
            if (!empty($emailImport) && !filter_var($emailImport, FILTER_VALIDATE_EMAIL)) {
                $emailImport = null;
            }

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
                'email'           => $emailImport,
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
            $query->buscarPorNomeFlexivel($texto);
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

    private function normalizarDadosPortfolio(array $data, Request $request): array
    {
        // Processar qualidades (array dinâmico ou textarea legado)
        $qualidades = $request->input('qualidades');
        if ($qualidades && is_array($qualidades)) {
            $data['principais_qualidades'] = collect($qualidades)
                ->map(fn($q) => trim($q))
                ->filter()
                ->values()
                ->all() ?: null;
        } else {
            $data['principais_qualidades'] = $this->linhasParaArray($request->input('principais_qualidades_texto'));
        }

        // Processar temporadas (array dinâmico ou textarea legado)
        $temporadasEquipes = $request->input('temporadas.equipe');
        if ($temporadasEquipes && is_array($temporadasEquipes)) {
            $temporadas = [];
            foreach ($temporadasEquipes as $idx => $equipe) {
                if (trim($equipe)) {
                    $temporadas[] = [
                        'equipe' => trim($equipe),
                        'temporada' => trim($request->input("temporadas.temporada.$idx") ?? $request->input("temporadas.ano.$idx") ?? ''),
                        'ppg' => trim($request->input("temporadas.ppg.$idx") ?? '--'),
                        'rpg' => trim($request->input("temporadas.rpg.$idx") ?? '--'),
                        'apg' => trim($request->input("temporadas.apg.$idx") ?? '--'),
                    ];
                }
            }
            $data['portfolio_temporadas'] = !empty($temporadas) ? array_slice($temporadas, 0, self::MAX_PORTFOLIO_TEMPORADAS) : null;
        } else {
            $data['portfolio_temporadas'] = $this->linhasTemporadasParaArray($request->input('portfolio_temporadas_texto'));
        }

        // Processar conquistas (array dinâmico ou textarea legado)
        $conquistasEquipes = $request->input('conquistas.equipe');
        if ($conquistasEquipes && is_array($conquistasEquipes)) {
            $conquistas = [];
            foreach ($conquistasEquipes as $idx => $equipe) {
                if (trim($equipe)) {
                    $itensTexto = trim($request->input("conquistas.itens.$idx") ?? '');
                    $itens = collect(explode(';', $itensTexto))
                        ->map(fn($item) => trim($item))
                        ->filter()
                        ->values()
                        ->all();
                    
                    $conquistas[] = [
                        'equipe' => trim($equipe),
                        'periodo' => trim($request->input("conquistas.periodo.$idx") ?? ''),
                        'itens' => $itens,
                    ];
                }
            }
            $data['portfolio_conquistas'] = !empty($conquistas) ? array_slice($conquistas, 0, self::MAX_PORTFOLIO_CONQUISTAS) : null;
        } else {
            $data['portfolio_conquistas'] = $this->linhasConquistasParaArray($request->input('portfolio_conquistas_texto'));
        }

        // Processar histórico (array dinâmico ou textarea legado)
        $historicoAnos = $request->input('historico.ano');
        if ($historicoAnos && is_array($historicoAnos)) {
            $historico = [];
            foreach ($historicoAnos as $idx => $ano) {
                if (trim($ano)) {
                    $historico[] = [
                        'ano' => trim($ano),
                        'equipe' => trim($request->input("historico.equipe.$idx") ?? ''),
                    ];
                }
            }
            $data['portfolio_historico_clubes'] = !empty($historico) ? array_slice($historico, 0, self::MAX_PORTFOLIO_HISTORICO) : null;
        } else {
            $data['portfolio_historico_clubes'] = $this->linhasHistoricoParaArray($request->input('portfolio_historico_clubes_texto'));
        }

        // Remover campos de textarea legado
        unset(
            $data['principais_qualidades_texto'],
            $data['portfolio_temporadas_texto'],
            $data['portfolio_conquistas_texto'],
            $data['portfolio_historico_clubes_texto'],
            $data['qualidades'],
            $data['temporadas'],
            $data['conquistas'],
            $data['historico']
        );

        return $data;
    }

    private function linhasParaArray(?string $texto): ?array
    {
        $linhas = collect(preg_split('/\r\n|\r|\n/', (string) $texto))
            ->map(fn($linha) => trim($linha))
            ->filter()
            ->values();

        return $linhas->isEmpty() ? null : $linhas->all();
    }

    private function linhasTemporadasParaArray(?string $texto): ?array
    {
        $linhas = $this->linhasComColunas($texto);

        $dados = $linhas->map(function (array $colunas) {
            return [
                'equipe' => $colunas[0] ?? null,
                'temporada' => $colunas[1] ?? null,
                'ppg' => $colunas[2] ?? '--',
                'rpg' => $colunas[3] ?? '--',
                'apg' => $colunas[4] ?? '--',
            ];
        })->filter(fn(array $linha) => !empty($linha['equipe']) || !empty($linha['temporada']))->values();

        $dados = $dados->take(self::MAX_PORTFOLIO_TEMPORADAS);

        return $dados->isEmpty() ? null : $dados->all();
    }

    private function linhasConquistasParaArray(?string $texto): ?array
    {
        $linhas = $this->linhasComColunas($texto);

        $dados = $linhas->map(function (array $colunas) {
            $itens = collect(explode(';', (string) ($colunas[2] ?? '')))
                ->map(fn($item) => trim($item))
                ->filter()
                ->values()
                ->all();

            return [
                'equipe' => $colunas[0] ?? null,
                'periodo' => $colunas[1] ?? null,
                'itens' => $itens,
            ];
        })->filter(fn(array $linha) => !empty($linha['equipe']) || !empty($linha['periodo']) || !empty($linha['itens']))->values();

        $dados = $dados->take(self::MAX_PORTFOLIO_CONQUISTAS);

        return $dados->isEmpty() ? null : $dados->all();
    }

    private function linhasHistoricoParaArray(?string $texto): ?array
    {
        $linhas = $this->linhasComColunas($texto);

        $dados = $linhas->map(function (array $colunas) {
            return [
                'ano' => $colunas[0] ?? null,
                'equipe' => $colunas[1] ?? null,
            ];
        })->filter(fn(array $linha) => !empty($linha['ano']) || !empty($linha['equipe']))->values();

        $dados = $dados->take(self::MAX_PORTFOLIO_HISTORICO);

        return $dados->isEmpty() ? null : $dados->all();
    }

    private function linhasComColunas(?string $texto): \Illuminate\Support\Collection
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $texto))
            ->map(fn($linha) => trim($linha))
            ->filter()
            ->map(fn($linha) => collect(explode('|', $linha))->map(fn($coluna) => trim($coluna))->all())
            ->values();
    }

    private function obterTemporadasPortfolio(Atleta $atleta): array
    {
        if (!empty($atleta->portfolio_temporadas)) {
            return array_slice($atleta->portfolio_temporadas, 0, self::MAX_PORTFOLIO_TEMPORADAS);
        }

        return [[
            'equipe' => $atleta->entidade ?: 'Equipe atual',
            'temporada' => now()->year,
            'ppg' => '--',
            'rpg' => '--',
            'apg' => '--',
        ]];
    }

    private function obterQualidadesPortfolio(Atleta $atleta): array
    {
        if (!empty($atleta->principais_qualidades)) {
            return $atleta->principais_qualidades;
        }

        return array_values(array_filter([
            $atleta->posicao_jogo ? 'Atua como ' . $atleta->posicao_jogo : null,
            $atleta->altura ? 'Altura competitiva para a posicao' : null,
            $atleta->entidade ? 'Atleta vinculado a ' . $atleta->entidade : null,
            'Perfil disponivel para avaliacao',
        ]));
    }

    private function obterConquistasPortfolio(Atleta $atleta): array
    {
        if (!empty($atleta->portfolio_conquistas)) {
            return array_slice($atleta->portfolio_conquistas, 0, self::MAX_PORTFOLIO_CONQUISTAS);
        }

        return [[
            'equipe' => $atleta->entidade ?: 'Equipe atual',
            'periodo' => now()->year,
            'itens' => ['Atleta cadastrado na vitrine', 'Perfil disponivel para avaliacao'],
        ]];
    }

    private function obterHistoricoClubesPortfolio(Atleta $atleta): array
    {
        if (!empty($atleta->portfolio_historico_clubes)) {
            return array_slice($atleta->portfolio_historico_clubes, 0, self::MAX_PORTFOLIO_HISTORICO);
        }

        return [[
            'ano' => now()->year,
            'equipe' => $atleta->entidade ?: 'Equipe atual',
        ]];
    }

}
