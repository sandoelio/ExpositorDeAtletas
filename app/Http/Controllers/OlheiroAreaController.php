<?php

namespace App\Http\Controllers;

use App\Models\Atleta;
use App\Models\OlheiroFavorito;
use App\Models\OlheiroShortlist;
use App\Models\OlheiroShortlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OlheiroAreaController extends Controller
{
    public function index(Request $request)
    {
        $olheiro = Auth::guard('olheiro')->user();

        $query = Atleta::query();

        if ($request->filled('nome')) {
            $query->where('nome_completo', 'like', '%' . trim((string) $request->nome) . '%');
        }
        if ($request->filled('cidade')) {
            $query->where('cidade', trim((string) $request->cidade));
        }
        if ($request->filled('entidade')) {
            $query->where('entidade', trim((string) $request->entidade));
        }
        if ($request->filled('posicao')) {
            $query->where('posicao_jogo', trim((string) $request->posicao));
        }

        $atletas = $query
            ->orderByDesc('visualizacoes')
            ->orderBy('nome_completo')
            ->paginate(4)
            ->withQueryString();

        $cidades = Atleta::query()
            ->whereNotNull('cidade')
            ->where('cidade', '<>', '')
            ->distinct()
            ->orderBy('cidade')
            ->pluck('cidade');

        $entidades = Atleta::query()
            ->whereNotNull('entidade')
            ->where('entidade', '<>', '')
            ->distinct()
            ->orderBy('entidade')
            ->pluck('entidade');

        $posicoes = Atleta::query()
            ->whereNotNull('posicao_jogo')
            ->where('posicao_jogo', '<>', '')
            ->distinct()
            ->orderBy('posicao_jogo')
            ->pluck('posicao_jogo');

        $favoritoIds = OlheiroFavorito::where('olheiro_id', $olheiro->id)->pluck('atleta_id');

        $favoritos = Atleta::whereIn('id', $favoritoIds)
            ->orderBy('nome_completo')
            ->get();

        $shortlists = OlheiroShortlist::where('olheiro_id', $olheiro->id)
            ->withCount('itens')
            ->orderByDesc('updated_at')
            ->orderBy('nome')
            ->get();

        $shortlistSelecionada = null;
        $shortlistId = (int) $request->query('shortlist_id', 0);

        if ($shortlistId > 0) {
            $shortlistSelecionada = OlheiroShortlist::where('olheiro_id', $olheiro->id)
                ->where('id', $shortlistId)
                ->first();
        } elseif ($shortlists->isNotEmpty()) {
            $shortlistSelecionada = $shortlists->first();
        }

        if ($shortlistSelecionada) {
            $shortlistSelecionada->load(['itens.atleta']);
        }
        $shortlistAtletaIds = $shortlistSelecionada
            ? $shortlistSelecionada->itens->pluck('atleta_id')->map(fn($id) => (int) $id)->toArray()
            : [];

        $statusOptions = [
            'observacao' => 'Observacao',
            'aprovado' => 'Aprovado',
            'revisar' => 'Revisar',
            'descartado' => 'Descartado',
        ];

        return view('olheiro.index', [
            'olheiro' => $olheiro,
            'atletas' => $atletas,
            'favoritoIds' => $favoritoIds->toArray(),
            'favoritos' => $favoritos,
            'shortlists' => $shortlists,
            'shortlistSelecionada' => $shortlistSelecionada,
            'statusOptions' => $statusOptions,
            'cidades' => $cidades,
            'entidades' => $entidades,
            'posicoes' => $posicoes,
            'shortlistAtletaIds' => $shortlistAtletaIds,
        ]);
    }

    public function toggleFavorito(Atleta $atleta)
    {
        $olheiroId = (int) Auth::guard('olheiro')->id();

        $favorito = OlheiroFavorito::where('olheiro_id', $olheiroId)
            ->where('atleta_id', $atleta->id)
            ->first();

        if ($favorito) {
            $favorito->delete();
            return back()->with('olheiro_flash', 'Atleta removido dos favoritos.');
        }

        OlheiroFavorito::create([
            'olheiro_id' => $olheiroId,
            'atleta_id' => $atleta->id,
        ]);

        return back()->with('olheiro_flash', 'Atleta adicionado aos favoritos.');
    }

    public function storeShortlist(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:8',
            'descricao' => 'nullable|string|max:255',
        ]);

        $shortlist = OlheiroShortlist::create([
            'olheiro_id' => (int) Auth::guard('olheiro')->id(),
            'nome' => trim($data['nome']),
            'descricao' => isset($data['descricao']) ? trim((string) $data['descricao']) : null,
        ]);

        return redirect()
            ->route('olheiro.atletas.index', ['shortlist_id' => $shortlist->id])
            ->with('olheiro_flash', 'Shortlist criada com sucesso.');
    }

    public function addAtletaNaShortlist(Request $request, OlheiroShortlist $shortlist, Atleta $atleta)
    {
        $this->authorizeShortlist($shortlist);

        $data = $request->validate([
            'status' => 'nullable|string|in:observacao,aprovado,revisar,descartado',
            'nota' => 'nullable|string|max:1200',
        ]);

        OlheiroShortlistItem::updateOrCreate(
            [
                'shortlist_id' => $shortlist->id,
                'atleta_id' => $atleta->id,
            ],
            [
                'status' => $data['status'] ?? 'observacao',
                'nota' => isset($data['nota']) ? trim((string) $data['nota']) : null,
            ]
        );

        return back()->with('olheiro_flash', 'Atleta salvo na shortlist.');
    }

    public function updateShortlistItem(Request $request, OlheiroShortlist $shortlist, Atleta $atleta)
    {
        $this->authorizeShortlist($shortlist);

        $data = $request->validate([
            'status' => 'required|string|in:observacao,aprovado,revisar,descartado',
            'nota' => 'nullable|string|max:1200',
        ]);

        $item = OlheiroShortlistItem::where('shortlist_id', $shortlist->id)
            ->where('atleta_id', $atleta->id)
            ->firstOrFail();

        $item->update([
            'status' => $data['status'],
            'nota' => isset($data['nota']) ? trim((string) $data['nota']) : null,
        ]);

        return back()->with('olheiro_flash', 'Item da shortlist atualizado.');
    }

    public function updateShortlistItens(Request $request, OlheiroShortlist $shortlist)
    {
        $this->authorizeShortlist($shortlist);

        $data = $request->validate([
            'status' => 'required|array',
            'status.*' => 'required|string|in:observacao,aprovado,revisar,descartado',
        ]);

        $statusPorAtleta = $data['status'];
        $atletaIds = collect(array_keys($statusPorAtleta))
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->values()
            ->all();

        if (empty($atletaIds)) {
            return back()->with('olheiro_flash', 'Nenhuma alteracao para salvar.');
        }

        $itens = OlheiroShortlistItem::where('shortlist_id', $shortlist->id)
            ->whereIn('atleta_id', $atletaIds)
            ->get();

        foreach ($itens as $item) {
            $novoStatus = $statusPorAtleta[(string) $item->atleta_id] ?? $item->status;
            if ($item->status !== $novoStatus) {
                $item->status = $novoStatus;
                $item->save();
            }
        }

        return back()->with('olheiro_flash', 'Shortlist ativa atualizada.');
    }

    public function removeShortlistItem(OlheiroShortlist $shortlist, Atleta $atleta)
    {
        $this->authorizeShortlist($shortlist);

        OlheiroShortlistItem::where('shortlist_id', $shortlist->id)
            ->where('atleta_id', $atleta->id)
            ->delete();

        return back()->with('olheiro_flash', 'Atleta removido da shortlist.');
    }

    public function destroyShortlist(OlheiroShortlist $shortlist)
    {
        $this->authorizeShortlist($shortlist);
        $shortlist->delete();

        return redirect()
            ->route('olheiro.atletas.index')
            ->with('olheiro_flash', 'Shortlist excluida.');
    }

    private function authorizeShortlist(OlheiroShortlist $shortlist): void
    {
        $olheiroId = (int) Auth::guard('olheiro')->id();
        abort_unless((int) $shortlist->olheiro_id === $olheiroId, 403);
    }
}
