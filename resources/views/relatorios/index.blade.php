@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="m-0">Relatórios</h3>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Voltar</a>
        </div>

        @php
            $stats = collect([
                ['key'=>'instituicoes','label'=>'Instituições','value'=>$instituicoesCount,'icon'=>'🏫'],
                ['key'=>'atletas','label'=>'Atletas','value'=>$atletasCount,'icon'=>'👥'],
                ['key'=>'cidades','label'=>'Cidades','value'=>$cidadesCount,'icon'=>'📍'],
                ['key'=>'crescimento','label'=>'Diário de Cadastros','value'=>($novosHoje ?? 0).' hoje','meta'=>($novosOntem ?? 0).' ontem','delta'=>$crescimentoPct ?? 0,'icon'=>'📈'],
                ['key'=>'perfis','label'=>'Perfis Completos','value'=> isset($atletasCompletos) ? number_format($atletasCompletos,0,',','.') : '0','icon'=>'✅'],
                ['key'=>'views','label'=>'Visualizações totais','value'=> isset($visualizacoesTotais) ? number_format($visualizacoesTotais,0,',','.') : '0','icon'=>'👁️'],
            ]);

            $tables = collect([
                ['key'=>'posicao','label'=>'Atletas por Posição','icon'=>'🎯','data'=>$porPosicao],
                ['key'=>'porCidade','label'=>'Atletas por Cidade','icon'=>'🌆','data'=>$porCidade],
                ['key'=>'porInstituicao','label'=>'Atletas por Instituição','icon'=>'🏆','data'=>$porInstituicao],
                ['key'=>'porAltura','label'=>'Atletas por Altura','icon'=>'📏','data'=>($porAltura ?? collect())],
            ]);
        @endphp

        <div class="tabs-wrapper mb-3">
            <ul class="nav nav-tabs" id="relTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="visao-tab" data-bs-toggle="tab" data-bs-target="#visao"
                        type="button" role="tab" aria-controls="visao" aria-selected="true">Visão Geral</button>
                </li>
                @foreach($tables as $t)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-{{ $t['key'] }}" data-bs-toggle="tab"
                            data-bs-target="#{{ $t['key'] }}" type="button" role="tab"
                            aria-controls="{{ $t['key'] }}" aria-selected="false">{{ $t['label'] }}</button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-content">
            {{-- Visão Geral --}}
            <div class="tab-pane fade show active" id="visao" role="tabpanel" aria-labelledby="visao-tab">
                <div class="row g-3 mb-4">
                    @foreach($stats as $card)
                        <div class="col-12 col-md-6 col-lg-4 d-flex">
                            <div class="card report-card w-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="icon fs-3">{{ $card['icon'] }}</div>
                                        <div class="content">
                                            <div class="label text-muted">{{ $card['label'] }}</div>
                                            <div class="value h4 fw-bold">{{ $card['value'] ?? 0 }}</div>
                                            @if(isset($card['delta']))
                                                @php
                                                    $delta = (float)$card['delta'];
                                                    $sign = $delta > 0 ? '+' : ($delta < 0 ? '' : '');
                                                    $deltaClass = $delta > 0 ? 'delta-positive' : ($delta < 0 ? 'delta-negative' : 'delta-neutral');
                                                @endphp
                                                <div class="small {{ $deltaClass }}">{{ $sign }}{{ number_format($delta,1,',','.') }}% — {{ $card['meta'] ?? '' }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Abas individuais --}}
            @foreach($tables as $t)
                <div class="tab-pane fade" id="{{ $t['key'] }}" role="tabpanel" aria-labelledby="tab-{{ $t['key'] }}">
                    <div class="row g-3 mb-4">
                        <div class="col-12 d-flex">
                            <div class="card report-card w-100">
                                <div class="card-body d-flex flex-column p-0">
                                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="icon fs-4">{{ $t['icon'] }}</div>
                                            <div>
                                                <div class="label small text-muted mb-0">{{ $t['label'] }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($t['key'] === 'porAltura')
                                        <div class="px-3 pt-3">
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <span class="badge bg-primary-subtle text-primary fw-semibold">
                                                    Maior altura: {{ number_format($alturaMax ?? 0, 2, ',', '') }} m
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="table-responsive table-card-body p-3">
                                        <table class="table table-sm mb-0 table-center">
                                            <thead class="table-light small">
                                                <tr>
                                                    @if($t['key'] === 'posicao')
                                                        <th>Posição</th>
                                                    @elseif($t['key'] === 'porCidade')
                                                        <th>Cidade</th>
                                                    @elseif($t['key'] === 'porInstituicao')
                                                        <th>Instituição</th>
                                                    @elseif($t['key'] === 'porAltura')
                                                        <th>Faixa de Altura</th>
                                                    @else
                                                        <th>Chave</th>
                                                    @endif
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($t['data'] as $r)
                                                    @php
                                                        $isMaiorFaixa = $t['key'] === 'porAltura' && isset($faixaDaMaior) && $r->faixa === $faixaDaMaior;
                                                    @endphp
                                                    <tr class="{{ $isMaiorFaixa ? 'row-maior-faixa' : '' }}">
                                                        @if($t['key'] === 'posicao')
                                                            <td class="small">{{ $r->posicao_jogo }}</td>
                                                        @elseif($t['key'] === 'porCidade')
                                                            <td class="small">{{ $r->cidade }}</td>
                                                        @elseif($t['key'] === 'porInstituicao')
                                                            <td class="small">{{ $r->entidade }}</td>
                                                        @elseif($t['key'] === 'porAltura')
                                                            <td class="small">{{ $r->faixa }}</td>
                                                        @else
                                                            <td class="small">{{ $r->key ?? '' }}</td>
                                                        @endif
                                                        <td class="align-middle text-center">{{ $r->total }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center small text-muted">Nenhum registro</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Grid e cards */
        .report-grid { align-items: stretch; }
        .report-grid .col-12.col-md-4 { display: flex; align-items: stretch; min-height: 0; }
        .report-grid .col-12.col-md-4 > .report-card { display:flex !important; flex-direction:column !important; flex:1 1 0% !important; min-height:0 !important; height:100% !important; max-height:none !important; overflow:hidden !important; }
        .report-card { display:flex; flex-direction:column; height:100%; width:100%; }
        .report-card .card-body { display:flex !important; flex-direction:column !important; flex:1 1 auto !important; min-height:0 !important; padding:1rem !important; }
        .report-card .content { display:flex; flex-direction:column; flex:1 1 auto; min-height:0; }
        .report-card .value { margin-top:.5rem; }

        /* Tabela e rolagem */
        .table-card-body, .table-responsive.table-card-body { display:flex; flex-direction:column; flex:1 1 auto; min-height:0; overflow-y:auto; overflow-x:hidden; padding:0.5rem 1rem; }
        .table-card-body table { width:100%; table-layout:fixed; border-collapse:collapse; }
        .table-card-body td, .table-card-body th { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; vertical-align:middle; padding:.5rem .75rem; }
        .table-light th { white-space: nowrap; }

        /* Centralização visual das tabelas no desktop */
        @media (min-width: 992px) {
            .table-center { max-width: 680px; margin-left: auto; margin-right: auto; }
        }

        /* Recuo extra à esquerda para impressão de centralização no desktop */
        @media (min-width: 992px) {
            .container { padding-left: 1.5rem; }
            .tabs-wrapper { padding-left: 1rem; }
            .tab-content { padding-left: 1rem; }
        }

        /* Alturas máximas */
        @media (min-width: 768px) { .table-card-body { max-height: 520px; } }
        @media (max-width: 767.98px) {
            .report-card .card-body { padding: 0.9rem; }
            .table-card-body { max-height: 260px; -webkit-overflow-scrolling: touch; }
            .container, .report-grid { padding-left: .75rem; padding-right: .75rem; }
            .row.g-3 > [class*='col-'] { flex-basis:100% !important; max-width:100% !important; }
        }
        @media (min-width: 768px) { .report-card .card-body { padding: 1rem; } }
        @media (min-width: 1400px) { .container { max-width: 1200px; } }

        /* Scrollbar visual */
        .table-card-body::-webkit-scrollbar { height: 8px; width: 8px; }
        .table-card-body::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.12); border-radius: 6px; }

        /* Tabs wrapper */
        .tabs-wrapper .nav-tabs {
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 0.6rem;
            padding: 0.25rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,250,250,0.95));
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
        }
        .tabs-wrapper .nav-tabs .nav-link {
            border: 0; background: transparent; color: #495057;
            padding: .45rem .9rem; margin: 0 .125rem; border-radius: .5rem;
            transition: background .12s ease, color .12s ease, box-shadow .12s ease;
            position: relative; z-index: 1; opacity: 0.9;
            -webkit-tap-highlight-color: transparent; touch-action: manipulation;
            -webkit-user-select: none; user-select: none; outline: none;
        }
        .tabs-wrapper .nav-tabs .nav-link.active,
        .tabs-wrapper .nav-tabs .nav-link:focus,
        .tabs-wrapper .nav-tabs .nav-link:focus-visible,
        .tabs-wrapper .nav-tabs .nav-link:active,
        .tabs-wrapper .nav-tabs .nav-link[aria-selected="true"] {
            background: #0d6efd !important; color: #fff !important;
            box-shadow: 0 6px 18px rgba(13,110,253,0.12);
            transform: none !important; border: 0 !important; z-index: 3; opacity: 1 !important; outline: none;
        }
        .tabs-wrapper .nav-tabs .nav-link:hover { background: rgba(13,110,253,0.06); color: #0b5ed7; opacity: 1; text-decoration: none; }
        @media (max-width: 576px) {
            .tabs-wrapper .nav-tabs .nav-link.active,
            .tabs-wrapper .nav-tabs .nav-link:focus,
            .tabs-wrapper .nav-tabs .nav-link:focus-visible,
            .tabs-wrapper .nav-tabs .nav-link[aria-selected="true"],
            .tabs-wrapper .nav-tabs .nav-link:active { box-shadow: none; transform: none !important; }
            .tabs-wrapper .nav-tabs .nav-link { padding: .38rem .65rem; }
        }
        .tabs-wrapper .nav-tabs::-webkit-scrollbar { height: 6px; }
        .tabs-wrapper .nav-tabs::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.08); border-radius: 6px; }
        .tabs-wrapper .nav-tabs .nav-link:not(.active) { color: #495057; }
        .tabs-wrapper .nav-tabs .nav-link.active { color: #fff; }

        /* Deltas */
        .delta-positive { color: #198754; }
        .delta-negative { color: #dc3545; }
        .delta-neutral  { color: #6c757d; }

        /* Destaque da faixa da maior altura (blindado contra .table-striped) */
        .row-maior-faixa { background-color: #fff3cd !important; }
        .row-maior-faixa > td { background-color: #fff3cd !important; font-weight: 600; }

        /* Badges sutis (fallback caso Bootstrap não tenha *-subtle) */
        .bg-primary-subtle { background-color: rgba(13,110,253,0.12) !important; color: #0d6efd !important; }
        .bg-info-subtle    { background-color: rgba(13,202,240,0.12) !important; color: #0aa2c0 !important; }
        .bg-secondary-subtle { background-color: rgba(108,117,125,0.12) !important; color: #6c757d !important; }
    </style>
@endpush
