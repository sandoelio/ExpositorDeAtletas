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

        ///-----------------------------------------------------------

        // Atletas por faixa de altura (intervalos de 10 cm) — compatível com only_full_group_by
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL: CTE para normalizar alturas e agrupar por faixa
            $porAltura = DB::select("
        WITH cleaned AS (
          SELECT
            id,
            NULLIF(TRIM(altura::text), '') AS altura_txt,
            CAST(REPLACE(NULLIF(TRIM(altura::text), ''), ',', '.') AS DECIMAL(5,2)) AS altura_num
          FROM atletas
          WHERE altura IS NOT NULL
            AND TRIM(altura::text) <> ''
        )
        SELECT
          CONCAT(
            TO_CHAR(FLOOR((altura_num * 100) / 10) * 10 / 100, 'FM9.99'),
            ' - ',
            TO_CHAR(((FLOOR((altura_num * 100) / 10) * 10 + 9) / 100), 'FM9.99')
          ) AS faixa,
          COUNT(*) AS total,
          MIN(altura_num) AS min_h,
          MAX(altura_num) AS max_h
        FROM cleaned
        GROUP BY faixa
        ORDER BY faixa ASC
    ");

            // Buscar apenas a maior altura (valor numérico)
            $alturaMaxRow = DB::selectOne("
        SELECT MAX(altura_num) AS altura_max
        FROM (
          SELECT CAST(REPLACE(NULLIF(TRIM(altura::text), ''), ',', '.') AS DECIMAL(5,2)) AS altura_num
          FROM atletas
          WHERE altura IS NOT NULL
            AND TRIM(altura::text) <> ''
        ) AS sub
    ");

            // Normaliza para uso parecido com MySQL branch
            $alturaMax = $alturaMaxRow ? (float) $alturaMaxRow->altura_max : null;
            $faixaDaMaior = null;
            $totalDaFaixaMaior = 0;

            if ($alturaMax !== null) {
                // calcula faixa em cm / buckets de 10 cm
                $cm = (int) round($alturaMax * 100);
                $bucketLowCm = floor($cm / 10) * 10;
                $bucketHighCm = $bucketLowCm + 9;

                // faixa formatada no padrão PT-BR (vírgula) — mantém compat com view
                $faixaDaMaior = number_format($bucketLowCm / 100, 2, ',', '') . ' - ' . number_format($bucketHighCm / 100, 2, ',', '');

                // conta quantos estão nessa faixa usando mesma normalização (DECIMAL(5,2))
                $totalRow = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM (
              SELECT CAST(REPLACE(NULLIF(TRIM(altura::text), ''), ',', '.') AS DECIMAL(5,2)) AS altura_num
              FROM atletas
              WHERE altura IS NOT NULL
                AND TRIM(altura::text) <> ''
            ) AS sub
            WHERE altura_num BETWEEN ? AND ?
        ", [$bucketLowCm / 100, $bucketHighCm / 100]);

                $totalDaFaixaMaior = $totalRow ? (int) $totalRow->total : 0;
            }
        } else {
            // MySQL: Query builder para faixas e duas queries para maior faixa
            $porAltura = DB::table('atletas')
                ->select(DB::raw("
            CONCAT(
                FORMAT(FLOOR((CAST(REPLACE(altura, ',', '.') AS DECIMAL(5,2)) * 100) / 10) * 10 / 100, 2),
                ' - ',
                FORMAT(((FLOOR((CAST(REPLACE(altura, ',', '.') AS DECIMAL(5,2)) * 100) / 10) * 10 + 9) / 100), 2)
            ) AS faixa,
            COUNT(*) AS total,
            MIN(CAST(REPLACE(altura, ',', '.') AS DECIMAL(5,2))) AS min_h,
            MAX(CAST(REPLACE(altura, ',', '.') AS DECIMAL(5,2))) AS max_h
        "))
                ->whereNotNull('altura')
                ->whereRaw("TRIM(altura) <> ''")
                ->groupBy('faixa')
                ->orderBy('faixa', 'asc')
                ->get();

            // Maior altura numérica
            $alturaMaxRow = DB::table('atletas')
                ->select(DB::raw("MAX(CAST(REPLACE(altura, ',', '.') AS DECIMAL(5,2))) AS altura_max"))
                ->whereNotNull('altura')
                ->whereRaw("TRIM(altura) <> ''")
                ->first();

            $alturaMax = $alturaMaxRow ? (float) $alturaMaxRow->altura_max : null;
            $faixaDaMaior = null;
            $totalDaFaixaMaior = 0;

            if ($alturaMax !== null) {
                $cm = (int) round($alturaMax * 100);
                $bucketLowCm = floor($cm / 10) * 10;
                $bucketHighCm = $bucketLowCm + 9;

                $faixaDaMaior = number_format($bucketLowCm / 100, 2, ',', '') . ' - ' . number_format($bucketHighCm / 100, 2, ',', '');

                $totalRow = DB::table('atletas')
                    ->select(DB::raw('COUNT(*) AS total'))
                    ->whereNotNull('altura')
                    ->whereRaw("TRIM(altura) <> ''")
                    ->whereRaw(
                        "CAST(REPLACE(altura, ',', '.') AS DECIMAL(5,2)) BETWEEN ? AND ?",
                        [$bucketLowCm / 100, $bucketHighCm / 100]
                    )
                    ->first();

                $totalDaFaixaMaior = $totalRow ? (int) $totalRow->total : 0;
            }
        }

        // Se $porAltura foi obtido via DB::select (array), converte para Collection
        if (is_array($porAltura)) {
            $porAltura = collect($porAltura);
        }

        // envia para a view
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
