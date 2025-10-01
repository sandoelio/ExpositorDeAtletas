@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="m-0">Relatórios</h3>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Voltar</a>
        </div>

        {{-- Preparar coleção de 9 cards (pode vir do controller) --}}
        @php
            $cards = collect([
                [
                    'key' => 'instituicoes',
                    'label' => 'Instituições',
                    'value' => $instituicoesCount,
                    'icon' => '🏫',
                    'type' => 'stat',
                ],
                ['key' => 'atletas', 'label' => 'Atletas', 'value' => $atletasCount, 'icon' => '👥', 'type' => 'stat'],
                ['key' => 'cidades', 'label' => 'Cidades', 'value' => $cidadesCount, 'icon' => '📍', 'type' => 'stat'],
                [
                    'key' => 'crescimento_cadastros',
                    'label' => 'Diário de Cadastros',
                    'value' => ($novosHoje ?? 0) . ' hoje',
                    'meta' => ($novosOntem ?? 0) . ' ontem',
                    'delta' => $crescimentoPct ?? 0,
                    'icon' => '📈',
                    'type' => 'stat',
                ],
                [
                    'key' => 'perfis_completos',
                    'label' => 'Perfis Completos',
                    'value' => isset($atletasCompletos) ? number_format($atletasCompletos, 0, ',', '.') : '0',
                    'icon' => '✅',
                    'type' => 'stat',
                ],
                [
                    'key' => 'views',
                    'label' => 'Visualizações totais',
                    'value' => isset($visualizacoesTotais) ? number_format($visualizacoesTotais, 0, ',', '.') : '0',
                    'icon' => '👁️',
                    'type' => 'stat',
                ],
                [
                    'key' => 'posicao',
                    'label' => 'Atletas por Posição',
                    'icon' => '🎯',
                    'type' => 'table',
                    'data' => $porPosicao,
                ],
                [
                    'key' => 'porCidade',
                    'label' => 'Atletas por Cidade',
                    'icon' => '🌆',
                    'type' => 'table',
                    'data' => $porCidade,
                ],
                [
                    'key' => 'porInstituicao',
                    'label' => 'Atletas por Instituição',
                    'icon' => '🏆',
                    'type' => 'table',
                    'data' => $porInstituicao,
                ],
            ]);
        @endphp

        <!-- Grid: 3 filas x 3 cards (desktop) -->
        <div class="row g-3 mb-4 report-grid">
            @foreach ($cards as $card)
                <div class="col-12 col-md-4">
                    <div class="card report-card h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-start gap-3">
                                <div class="icon fs-3">{{ $card['icon'] }}</div>
                                <div class="content">
                                    <div class="label text-muted">{{ $card['label'] }}</div>

                                    @if (($card['type'] ?? 'stat') === 'stat')
                                        <div class="value h4 fw-bold">{{ $card['value'] ?? 0 }}</div>

                                        {{-- delta / meta (quando existir) --}}
                                        @if (isset($card['delta']))
                                            @php
                                                $delta = (float) $card['delta'];
                                                $sign = $delta > 0 ? '+' : ($delta < 0 ? '' : '');
                                                $deltaClass =
                                                    $delta > 0
                                                        ? 'delta-positive'
                                                        : ($delta < 0
                                                            ? 'delta-negative'
                                                            : 'delta-neutral');
                                            @endphp
                                            <div class="small delta {{ $deltaClass }}">
                                                {{ $sign }}{{ number_format($delta, 1, ',', '.') }}% —
                                                {{ $card['meta'] ?? '' }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- Se for card-tabela, renderiza toda a tabela dentro do card (sem "Ver todos") --}}
                            @if (($card['type'] ?? 'stat') === 'table')
                                <div class="table-responsive table-card-body mt-3">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light small">
                                            <tr>
                                                @if ($card['key'] === 'posicao')
                                                    <th>Posição</th>
                                                @elseif($card['key'] === 'porCidade')
                                                    <th>Cidade</th>
                                                @else
                                                    <th>Instituição</th>
                                                @endif
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($card['data'] as $r)
                                                <tr>
                                                    @if ($card['key'] === 'posicao')
                                                        <td class="small">{{ $r->posicao_jogo }}</td>
                                                    @elseif($card['key'] === 'porCidade')
                                                        <td class="small">{{ $r->cidade }}</td>
                                                    @else
                                                        <td class="small">{{ $r->entidade }}</td>
                                                    @endif
                                                    <td>{{ $r->total }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* garantir layout flex do grid */
        .report-grid {
            align-items: stretch;
        }

        .report-grid .col-12.col-md-4 {
            display: flex;
            align-items: stretch;
            min-height: 0;
        }

        /* garantir o card ocupe totalmente a coluna e permita flex interno */
        .report-grid .col-12.col-md-4>.report-card {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            height: 100% !important;
            max-height: none !important;
            overflow: hidden !important;
        }

        /* corpo do card cresce para preencher o card */
        .report-card .card-body {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 auto !important;
            min-height: 0 !important;
            padding: 1rem !important;
        }

        /* elemento de conteúdo dentro do card (forçar preenchimento) */
        .report-card .content {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            min-height: 0;
            justify-content: flex-start;
        }

        /* área da tabela: ocupa resto do espaço e tem scroll interno se necessário */
        .table-card-body {
            flex: 1 1 auto !important;
            min-height: 0 !important;
            max-height: none !important;
            overflow: auto !important;
        }

        /* manter tabelas responsivas sem forçar largura */
        .table-card-body table {
            width: 100%;
            min-width: 0;
            table-layout: fixed;
        }

        /* garantir que estat cards (sem tabela) tenham o mesmo padding/altura visual */
        .report-card .value {
            margin-top: auto;
        }

        /* ajustes de teste para garantir aplicação (remova !important depois) */
        .report-grid .col-12.col-md-4>.report-card {
            max-height: none !important;
        }

        .table-card-body {
            max-height: 320px !important;
        }

        /* Mobile: voltar a comportamento natural */
        @media (max-width: 576px) {

            /* preservar o espaçamento interno dos cards como no desktop */
            .report-card {
                padding: 0 !important;
            }

            .report-card .card-body {
                padding: 1rem !important;
                /* mesmo padding do desktop */
                align-items: stretch !important;
            }

            /* manter a linha do ícone + conteúdo com espaçamento igual ao desktop */
            .report-card .d-flex.align-items-start {
                gap: 0.75rem;
                align-items: flex-start;
            }

            /* garantir largura do ícone e alinhamento central do seu conteúdo */
            .report-card .icon {
                width: 44px;
                min-width: 44px;
                text-align: center;
                flex: 0 0 44px;
                font-size: 1.35rem;
            }

            /* conteúdo ao lado do ícone deve preencher o restante e manter padding interno */
            .report-card .content {
                flex: 1 1 auto;
                padding-left: 0.25rem;
                padding-right: 0.25rem;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
            }

            /* alinhar valores e rótulos como no desktop */


            /* garantir que a área da tabela tenha os mesmos espaçamentos */
            .table-card-body {
                padding-left: 0;
                padding-right: 0;
                max-height: 200px;
                /* ajuste se desejar */
                overflow: auto;
            }

            /* evitar que a tabela fique colada na borda */
            .table-card-body table {
                margin: 0;
                padding: 0;
            }

            /* pequenas correções visuais para que o conteúdo não encoste na borda da viewport */
            .container,
            .report-grid {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }
    </style>
@endpush
