<?php

namespace App\Http\Controllers;

use App\Models\Atleta;

class HomeController extends Controller
{
    public function index()
    {
        $topAtletas = Atleta::orderByDesc('visualizacoes')
            ->take(6)
            ->get(['nome_completo', 'visualizacoes']);

        return view('home', compact('topAtletas'));
    }
}
