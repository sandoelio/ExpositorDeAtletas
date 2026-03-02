<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atleta;

class AdminAtletaController extends Controller
{
    public function index(Request $request)
    {
        $texto = trim((string) $request->query('texto', ''));
        $entidade = trim((string) $request->query('entidade', ''));

        $entidades = Atleta::select('entidade')
            ->distinct()
            ->orderBy('entidade')
            ->pluck('entidade');

        $query = Atleta::query()->orderBy('nome_completo');

        if ($entidade !== '') {
            $query->where('entidade', $entidade);
        }

        if ($texto !== '') {
            $textoBusca = mb_strtolower($texto, 'UTF-8');
            $query->whereRaw('LOWER(nome_completo) LIKE ?', ['%' . $textoBusca . '%']);
        }

        $atletas = $query->paginate(5)->withQueryString();

        return view('admin.index', [
            'entidades' => $entidades,
            'atletas' => $atletas,
            'filtroEntidade' => $entidade,
            'filtroTexto' => $texto,
        ]);
    }
}
