<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RelatorioController extends Controller
{
    public function index()
    {
        // Total de instituições inscritas
        $instituicoesCount = DB::table('atletas')
            ->select(DB::raw('COUNT(DISTINCT entidade) as total'))
            ->value('total');

        // Total de atletas
        $atletasCount = DB::table('atletas')->count();

        // Total de cidades distintas
        $cidadesCount = DB::table('atletas')
            ->select(DB::raw('COUNT(DISTINCT cidade) as total'))
            ->value('total');

        // Número de atletas por posição
        $porPosicao = DB::table('atletas')
            ->select('posicao_jogo', DB::raw('COUNT(*) as total'))
            ->groupBy('posicao_jogo')
            ->orderByDesc('total')
            ->get();

        // Número de atletas por cidade
        $porCidade = DB::table('atletas')
            ->select('cidade', DB::raw('COUNT(*) as total'))
            ->groupBy('cidade')
            ->orderByDesc('total')
            ->get();

        // Número de atletas por instituição
        $porInstituicao = DB::table('atletas')
            ->select('entidade', DB::raw('COUNT(*) as total'))
            ->groupBy('entidade')
            ->orderByDesc('total')
            ->get();

        // Hoje (base para cálculos)
        $today = Carbon::today();

        // Perfis completos: foto, bio e telefone etc preenchidos
        $atletasCompletos = DB::table('atletas')
            ->whereNotNull('imagem_base64')
            ->whereNotNull('resumo')
            ->count();

        // Visualizações: soma da coluna 'visualizacoes' na tabela 'atletas'
        $visualizacoesTotais = (int) DB::table('atletas')->sum('visualizacoes');

        // Crescimento diário de cadastros: hoje vs ontem
        $novosHoje = DB::table('atletas')
            ->whereDate('created_at', $today)
            ->count();

        $novosOntem = DB::table('atletas')
            ->whereDate('created_at', Carbon::yesterday())
            ->count();

        // variação percentual segura
        if ($novosOntem > 0) {
            $deltaPct = (($novosHoje - $novosOntem) / $novosOntem) * 100;
        } else {
            $deltaPct = $novosHoje > 0 ? 100.0 : 0.0;
        }

        $crescimentoPct = round($deltaPct, 1);

        // Atletas por faixa de altura (intervalos de 10 cm) — compatível com only_full_group_by
        $porAltura = DB::table('atletas')
            ->select(DB::raw("
                FLOOR((CAST(REPLACE(altura::text, ',', '.') AS DECIMAL(4,2)) * 100) / 10) AS bucket_10cm,
                COUNT(*) AS total,
                MIN(CAST(REPLACE(altura::text, ',', '.') AS DECIMAL(4,2))) AS min_h,
                MAX(CAST(REPLACE(altura::text, ',', '.') AS DECIMAL(4,2))) AS max_h
            "))
            ->whereNotNull('altura')
            ->whereRaw("TRIM(altura::text) <> ''")
            ->groupBy('bucket_10cm')
            ->orderBy('bucket_10cm', 'asc')
            ->get();

        $porAltura = $porAltura->map(function ($r) {
            $lowCm = (int)$r->bucket_10cm * 10;
            $highCm = $lowCm + 9;

            $lowM = $lowCm / 100;
            $highM = $highCm / 100;

            return (object)[
                'faixa' => number_format($lowM, 2, ',', '') . ' - ' . number_format($highM, 2, ',', ''),
                'total' => (int)$r->total,
                'min_h' => (float)$r->min_h,
                'max_h' => (float)$r->max_h,
            ];
        });

        // Maior altura absoluta no banco
        $alturaMaxRow = DB::table('atletas')
            ->select(DB::raw("MAX(CAST(REPLACE(altura::text, ',', '.') AS DECIMAL(4,2))) AS altura_max"))
            ->whereNotNull('altura')
            ->whereRaw("TRIM(altura::text) <> ''")
            ->first();

        $alturaMax = $alturaMaxRow && $alturaMaxRow->altura_max !== null
            ? (float)$alturaMaxRow->altura_max
            : null;

        // Determinar a faixa da maior altura e total na faixa
        $faixaDaMaior = null;
        $totalDaFaixaMaior = 0;

        if ($alturaMax !== null) {
            $cm = (int)round($alturaMax * 100);
            $bucketLowCm = floor($cm / 10) * 10;   // ex.: 167 -> 160
            $bucketHighCm = $bucketLowCm + 9;      // ex.: 160 -> 169

            $faixaDaMaior = number_format($bucketLowCm / 100, 2, ',', '') . ' - ' . number_format($bucketHighCm / 100, 2, ',', '');

            $totalRow = DB::table('atletas')
                ->select(DB::raw('COUNT(*) AS total'))
                ->whereNotNull('altura')
                ->whereRaw("TRIM(altura::text) <> ''")
                ->whereRaw(
                    "CAST(REPLACE(altura::text, ',', '.') AS DECIMAL(4,2)) BETWEEN ? AND ?",
                    [$bucketLowCm / 100, $bucketHighCm / 100]
                )
                ->first();

            $totalDaFaixaMaior = $totalRow ? (int)$totalRow->total : 0;
        }
        return view('relatorios.index', compact(
            'instituicoesCount',
            'atletasCount',
            'cidadesCount',
            'porPosicao',
            'porCidade',
            'porInstituicao',
            'atletasCompletos',
            'visualizacoesTotais',
            'novosHoje',
            'novosOntem',
            'crescimentoPct',
            'porAltura',
            'alturaMax',
            'faixaDaMaior',
            'totalDaFaixaMaior'
        ));
    }
}
