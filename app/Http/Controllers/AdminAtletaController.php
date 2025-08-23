<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atleta;

class AdminAtletaController extends Controller
{
    public function index(Request $request)
    {
        // coleta todas as entidades únicas
        $entidades = Atleta::select('entidade')
            ->distinct()
            ->orderBy('entidade')
            ->pluck('entidade');

        // inicia query
        $query = Atleta::orderBy('nome_completo');

        // aplica filtro de entidade se informado
        if ($request->filled('entidade')) {
            $query->where('entidade', $request->entidade);
        }

        // você pode usar paginate() ou get()
        $atletas = $query->paginate(10)
            ->withQueryString();

        return view('admin.index', [
            'entidades'      => $entidades,
            'atletas'        => $atletas,
            'filtroEntidade' => $request->entidade ?? ''
        ]);
    }
}
