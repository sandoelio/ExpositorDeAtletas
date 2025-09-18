<?php

namespace App\Http\Controllers;

use App\Models\Atleta;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $topAtletas = Atleta::orderByDesc('visualizacoes')->take(6)->get();

        $hoje = Carbon::today();

        $estatisticas = [
            'masculino' => Atleta::where('sexo', 'Masculino')->count(),
            'feminino' => Atleta::where('sexo', 'Feminino')->count(),
            'categoria12' => Atleta::whereDate('data_nascimento', '>=', $hoje->copy()->subYears(12))->count(),
            'categoria14' => Atleta::whereBetween('data_nascimento', [
                $hoje->copy()->subYears(14),
                $hoje->copy()->subYears(13)
            ])->count(),
            'categoria16' => Atleta::whereBetween('data_nascimento', [
                $hoje->copy()->subYears(16),
                $hoje->copy()->subYears(15)
            ])->count(),
            'categoria18' => Atleta::whereBetween('data_nascimento', [
                $hoje->copy()->subYears(18),
                $hoje->copy()->subYears(17)
            ])->count(),
            'categoria21' => Atleta::whereBetween('data_nascimento', [
                $hoje->copy()->subYears(21),
                $hoje->copy()->subYears(19)
            ])->count(),
            'categoria22_29' => Atleta::whereBetween('data_nascimento', [
                $hoje->copy()->subYears(29),
                $hoje->copy()->subYears(22)
            ])->count(),
            'categoria30_39' => Atleta::whereBetween('data_nascimento', [
                $hoje->copy()->subYears(39),
                $hoje->copy()->subYears(30)
            ])->count(),
            'categoria' => Atleta::whereDate('data_nascimento', '<=', $hoje->copy()->subYears(40))->count(),
        ];

        return view('home', compact('topAtletas', 'estatisticas'));
    }
}
