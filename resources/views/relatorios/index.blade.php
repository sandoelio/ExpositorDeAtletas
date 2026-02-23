@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 report-page-header">
            <h3 class="m-0 report-title">Relatórios</h3>
            <a href="{{ route('admin.dashboard') }}" class="btn report-back-btn">Voltar</a>
        </div>

        @php
            $stats = collect([
                ['key' => 'instituicoes', 'label' => 'Instituições', 'value' => $instituicoesCount, 'icon' => '🏫'],
                ['key' => 'atletas', 'label' => 'Atletas', 'value' => $atletasCount, 'icon' => '👥'],
                ['key' => 'cidades', 'label' => 'Cidades', 'value' => $cidadesCount, 'icon' => '📍'],
                ['key' => 'tecnicos', 'label' => 'Técnicos/Olheiros', 'value' => $olheirosCount ?? 0, 'icon' => '🧭'],
                [
                    'key' => 'crescimento',
                    'label' => 'Diário de Cadastros',
                    'value' => ($novosHoje ?? 0) . ' hoje',
                    'meta' => ($novosOntem ?? 0) . ' ontem',
                    'delta' => $crescimentoPct ?? 0,
                    'icon' => '📈',
                ],
                [
                    'key' => 'perfis',
                    'label' => 'Perfis Completos',
                    'value' => isset($atletasCompletos) ? number_format($atletasCompletos, 0, ',', '.') : '0',
                    'icon' => '✅',
                ],
                [
                    'key' => 'views',
                    'label' => 'Visualizações totais',
                    'value' => isset($visualizacoesTotais) ? number_format($visualizacoesTotais, 0, ',', '.') : '0',
                    'icon' => '👁️',
                ],
            ]);

            $tables = collect([
                ['key' => 'posicao', 'label' => 'Atletas por Posição', 'icon' => '🎯', 'data' => $porPosicao],
                ['key' => 'porCidade', 'label' => 'Atletas por Cidade', 'icon' => '🌆', 'data' => $porCidade],
                [
                    'key' => 'porInstituicao',
                    'label' => 'Atletas por Instituição',
                    'icon' => '🏆',
                    'data' => $porInstituicao,
                ],
                [
                    'key' => 'porAltura',
                    'label' => 'Faixa de Altura',
                    'icon' => '📏',
                    'data' => $porAltura ?? collect(),
                ],
                [
                    'key' => 'atletasAltos',
                    'label' => 'Atletas Altos',
                    'icon' => '🏀',
                    'data' => $altos190 ?? collect(),
                ],
                [
                    'key' => 'visualizados',
                    'label' => 'Visualizados',
                    'icon' => 'TOP 10',
                    'data' => $top10Visualizados ?? collect(),
                ],
                [
                    'key' => 'olheirosAba',
                    'label' => 'Olheiros',
                    'icon' => '🧭',
                    'data' => collect(),
                ],
            ]);
        @endphp

        <div class="tabs-wrapper mb-3">
            <ul class="nav nav-tabs" id="relTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="visao-tab" data-bs-toggle="tab" data-bs-target="#visao"
                        type="button" role="tab" aria-controls="visao" aria-selected="true">Visão Geral</button>
                </li>
                @foreach ($tables as $t)
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
                    @foreach ($stats as $card)
                        <div class="col-12 col-md-6 col-lg-4 d-flex">
                            <div class="card report-card w-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="icon fs-3">{{ $card['icon'] }}</div>
                                        <div class="content">
                                            <div class="label text-muted">{{ $card['label'] }}</div>
                                            <div class="value h4 fw-bold">{{ $card['value'] ?? 0 }}</div>
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
                                                <div class="small {{ $deltaClass }}">
                                                    {{ $sign }}{{ number_format($delta, 1, ',', '.') }}% —
                                                    {{ $card['meta'] ?? '' }}</div>
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
            @foreach ($tables as $t)
                <div class="tab-pane fade" id="{{ $t['key'] }}" role="tabpanel"
                    aria-labelledby="tab-{{ $t['key'] }}">
                    <div class="row g-3 mb-4">
                        <div class="col-12 d-flex">
                            <div class="card report-card w-100">
                                <div class="card-body d-flex flex-column p-0">
                                    <div
                                        class="d-flex align-items-center justify-content-between p-3 border-bottom tab-panel-header {{ $t['key'] === 'olheirosAba' ? 'tab-header-compact' : '' }}">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="icon {{ $t['key'] === 'olheirosAba' ? 'fs-5' : 'fs-4' }}">
                                                {{ $t['icon'] }}</div>
                                            <div>
                                                <div class="label small text-muted mb-0">{{ $t['label'] }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ✅ Mantém badges apenas na aba porAltura --}}
                                    @if ($t['key'] === 'porAltura')
                                        <div class="px-3 pt-3">
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <span class="badge bg-primary-subtle text-primary fw-semibold">
                                                    Maior altura: {{ number_format($alturaMax ?? 0, 2, ',', '') }}
                                                </span>
                                                @if (!empty($faixaDaMaior))
                                                    <span class="badge bg-info-subtle text-info fw-semibold">
                                                        Faixa da maior: {{ $faixaDaMaior }}
                                                    </span>
                                                @endif
                                                @if (isset($totalDaFaixaMaior))
                                                    <span class="badge bg-secondary-subtle text-secondary fw-semibold">
                                                        Total na faixa: {{ $totalDaFaixaMaior }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- ✅ Aba nova: atletasAltos tem tabela própria --}}
                                    @if ($t['key'] === 'atletasAltos')
                                        <div class="table-responsive table-card-body p-3 atletas-altos-scroll">
                                            <table class="table table-sm mb-0 table-center">
                                                <thead class="table-light small">
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th>Instituição</th>
                                                        <th>Idade</th>
                                                        <th>Altura</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($t['data'] as $a)
                                                        <tr>
                                                            <td class="small">{{ $a->nome_completo }}</td>
                                                            <td class="small">{{ $a->entidade }}</td>
                                                            <td class="small">{{ $a->idade ?? '-' }}</td>
                                                            <td class="small">
                                                                {{ number_format((float) $a->altura_m, 2, ',', '') }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center small text-muted">
                                                                Nenhum atleta encontrado
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    @elseif($t['key'] === 'olheirosAba')
                                        <div class="p-3">
                                            <form action="{{ route('relatorios.index') }}" method="GET" class="mb-3 olheiro-filter-bar">
                                                <input type="hidden" name="aba" value="olheirosAba">
                                                <div class="row g-2 align-items-end">
                                                    <div class="col-12 col-md-9">
                                                        <label for="olheiro_id" class="form-label small mb-1">
                                                            Selecione o técnico/olheiro
                                                        </label>
                                                        <select name="olheiro_id" id="olheiro_id"
                                                            class="form-select form-select-sm">
                                                            @forelse($olheirosLista as $ol)
                                                                <option value="{{ $ol->id }}"
                                                                    {{ (int) $olheiroSelecionadoId === (int) $ol->id ? 'selected' : '' }}>
                                                                    {{ $ol->nome }} - {{ $ol->entidade ?? '-' }}
                                                                </option>
                                                            @empty
                                                                <option value="">Nenhum técnico/olheiro cadastrado
                                                                </option>
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-3">
                                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                                            Carregar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                            @if ($olheiroSelecionado)
                                                <div class="row g-3">
                                                    <div class="col-12 col-lg-4">
                                                        <div class="card h-100 border">
                                                            <div class="card-body py-3">
                                                                <div class="small"><strong>Nome:</strong>
                                                                    {{ $olheiroSelecionado->nome }}</div>
                                                                <div class="small"><strong>Instituição:</strong>
                                                                    {{ $olheiroSelecionado->entidade ?? '-' }}</div>
                                                                <hr class="my-2">
                                                                <div class="small"><strong>Favoritos:</strong>
                                                                    {{ $olheiroFavoritos->count() }}</div>
                                                                <div class="small"><strong>Shortlists:</strong>
                                                                    {{ $olheiroShortlistsCount }}</div>
                                                                <div class="small"><strong>Atletas inseridos:</strong>
                                                                    {{ $olheiroAtletasEmShortlists }}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-8">
                                                        <div class="card h-100 border">
                                                            <div class="card-body py-3">
                                                                <h6 class="mb-2">Atletas favoritados</h6>
                                                                <div
                                                                    class="table-responsive olheiro-section-scroll olheiro-favoritos-scroll">
                                                                    <table class="table table-sm mb-0">
                                                                        <thead class="table-light small">
                                                                            <tr>
                                                                                <th>Atleta</th>
                                                                                <th>Instituição</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @forelse($olheiroFavoritos as $fav)
                                                                                <tr>
                                                                                    <td class="small">
                                                                                        {{ $fav->nome_completo }}</td>
                                                                                    <td class="small">
                                                                                        {{ $fav->entidade ?? '-' }}</td>
                                                                                </tr>
                                                                            @empty
                                                                                <tr>
                                                                                    <td colspan="2"
                                                                                        class="text-center small text-muted">
                                                                                        Nenhum favorito
                                                                                    </td>
                                                                                </tr>
                                                                            @endforelse
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="card border">
                                                            <div class="card-body py-3">
                                                                <h6 class="mb-2">Shortlists criadas</h6>
                                                                <div
                                                                    class="table-responsive olheiro-section-scroll olheiro-shortlists-scroll">
                                                                    <table class="table table-sm mb-0">
                                                                        <thead class="table-light small">
                                                                            <tr>
                                                                                <th>Shortlist</th>
                                                                                <th>Atletas e status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @forelse($olheiroShortlists as $sl)
                                                                                <tr>
                                                                                    <td class="small">{{ $sl->nome }}
                                                                                    </td>
                                                                                    <td class="small">
                                                                                        <div class="shortlist-itens-scroll">
                                                                                            @if (collect($sl->itens ?? [])->isNotEmpty())
                                                                                                <div
                                                                                                    class="d-flex flex-column gap-1">
                                                                                                    @foreach ($sl->itens as $item)
                                                                                                        @php
                                                                                                            $statusTexto = trim((string) ($item->status ?? 'Sem status'));
                                                                                                            $badgeClass = 'bg-secondary';
                                                                                                            $itemStatusClass = 'semstatus';
                                                                                                            if (stripos($statusTexto, 'reprov') !== false) {
                                                                                                                $badgeClass = 'bg-danger';
                                                                                                                $itemStatusClass = 'reprovado';
                                                                                                            } elseif (stripos($statusTexto, 'aprov') !== false) {
                                                                                                                $badgeClass = 'bg-success';
                                                                                                                $itemStatusClass = 'aprovado';
                                                                                                            } elseif (stripos($statusTexto, 'observ') !== false) {
                                                                                                                $badgeClass = 'bg-warning text-dark';
                                                                                                                $itemStatusClass = 'observacao';
                                                                                                            }
                                                                                                        @endphp
                                                                                                        <div
                                                                                                            class="d-flex justify-content-between align-items-center gap-2 shortlist-item-status {{ $itemStatusClass }}">
                                                                                                            <span class="text-truncate"
                                                                                                                title="{{ $item->nome_completo }} ({{ $item->entidade ?? '-' }})">
                                                                                                                <span class="atleta-nome">{{ $item->nome_completo }}</span>
                                                                                                                <span class="atleta-entidade">({{ $item->entidade ?? '-' }})</span>
                                                                                                            </span>
                                                                                                            <span
                                                                                                                class="badge {{ $badgeClass }} flex-shrink-0">{{ $statusTexto }}</span>
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            @else
                                                                                                <span
                                                                                                    class="text-muted">Sem atletas inseridos</span>
                                                                                            @endif
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @empty
                                                                                <tr>
                                                                                    <td colspan="2"
                                                                                        class="text-center small text-muted">
                                                                                        Nenhuma shortlist criada
                                                                                    </td>
                                                                                </tr>
                                                                            @endforelse
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-info mb-0">Nenhum técnico/olheiro selecionado.
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($t['key'] === 'visualizados')
                                        <div class="table-responsive table-card-body p-3">
                                            <table class="table table-sm mb-0 table-center visualizados-table">
                                                <thead class="table-light small">
                                                    <tr>
                                                        <th class="text-center viz-col-rank" style="width: 72px;">#</th>
                                                        <th class="viz-col-atleta">Atleta</th>
                                                        <th class="viz-col-inst">Instituicao</th>
                                                        <th class="viz-col-city">Cidade</th>
                                                        <th class="text-center viz-col-views">Visualizações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($t['data'] as $a)
                                                        <tr>
                                                            <td class="text-center small fw-semibold viz-col-rank">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td class="small viz-col-atleta">
                                                                <div class="viz-atleta-nome">{{ $a->nome_completo }}</div>
                                                                <div class="viz-mobile-meta">
                                                                    {{ $a->entidade ?? '-' }} | {{ $a->cidade ?? '-' }}
                                                                </div>
                                                            </td>
                                                            <td class="small viz-col-inst">{{ $a->entidade ?? '-' }}</td>
                                                            <td class="small viz-col-city">{{ $a->cidade ?? '-' }}</td>
                                                            <td class="align-middle text-center fw-semibold viz-col-views">
                                                                {{ number_format((int) ($a->visualizacoes ?? 0), 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center small text-muted">
                                                                Nenhum atleta encontrado
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="table-responsive table-card-body p-3">
                                            <table class="table table-sm mb-0 table-center">
                                                <thead class="table-light small">
                                                    <tr>
                                                        @if ($t['key'] === 'posicao')
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
                                                            $isMaiorFaixa =
                                                                $t['key'] === 'porAltura' &&
                                                                isset($faixaDaMaior) &&
                                                                isset($r->faixa) &&
                                                                $r->faixa === $faixaDaMaior;
                                                        @endphp
                                                        <tr class="{{ $isMaiorFaixa ? 'row-maior-faixa' : '' }}">
                                                            @if ($t['key'] === 'posicao')
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
                                                            <td colspan="2" class="text-center small text-muted">Nenhum
                                                                registro</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
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
        .report-page-header {
            background: linear-gradient(135deg, #fff6ee 0%, #ffe8d7 100%);
            border: 1px solid rgba(40, 54, 95, 0.1);
            border-radius: 12px;
            padding: 0.6rem 0.8rem;
        }

        .report-title {
            color: #28365f;
            font-weight: 800;
            font-size: 1.25rem;
        }

        .report-back-btn {
            background: #ff7209;
            border: 1px solid #ff7209;
            color: #fff;
            font-weight: 700;
            min-width: 92px;
        }

        .report-back-btn:hover {
            background: #e66000;
            border-color: #e66000;
            color: #fff;
        }

        /* Grid e cards */
        .report-grid {
            align-items: stretch;
        }

        .report-grid .col-12.col-md-4 {
            display: flex;
            align-items: stretch;
            min-height: 0;
        }

        .report-grid .col-12.col-md-4>.report-card {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            height: 100% !important;
            max-height: none !important;
            overflow: hidden !important;
        }

        .report-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
            border: 1px solid rgba(40, 54, 95, 0.12);
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(17, 35, 70, 0.05);
            overflow: hidden;
        }

        .report-card .card-body {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 auto !important;
            min-height: 0 !important;
            padding: 1rem !important;
        }

        .report-card .content {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            min-height: 0;
        }

        .report-card .value {
            margin-top: .5rem;
        }

        /* Tabela e rolagem */
        .table-card-body,
        .table-responsive.table-card-body {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 1rem;
        }

        .table-card-body table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .table-card-body td,
        .table-card-body th {
            /* white-space: nowrap; */
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            padding: 0.55rem 0.65rem;
        }

        .table-light th {
            white-space: nowrap;
            font-weight: 700;
            color: #334666;
        }

        .visualizados-table {
            table-layout: auto !important;
        }

        .visualizados-table th,
        .visualizados-table td {
            overflow: visible;
            text-overflow: initial;
        }

        .visualizados-table .viz-atleta-nome {
            font-weight: 600;
            white-space: normal;
            line-height: 1.25;
        }

        .visualizados-table .viz-mobile-meta {
            display: none;
            margin-top: 2px;
            font-size: 0.72rem;
            color: #60708e;
            font-weight: 600;
            white-space: normal;
            line-height: 1.15;
        }

        .tab-panel-header {
            background: #f8fbff;
        }

        .olheiro-filter-bar {
            background: #f8fbff;
            border: 1px solid rgba(40, 54, 95, 0.1);
            border-radius: 10px;
            padding: 0.6rem;
        }

        /* Centralização visual das tabelas no desktop */
        @media (min-width: 992px) {
            .table-center {
                max-width: 680px;
                margin-left: auto;
                margin-right: auto;
            }
        }

        /* Recuo extra à esquerda para impressão de centralização no desktop */
        @media (min-width: 992px) {
            .container {
                padding-left: 1.5rem;
            }

            .tabs-wrapper {
                padding-left: 1rem;
            }

            .tab-content {
                padding-left: 1rem;
            }
        }

        /* Alturas máximas */
        @media (min-width: 768px) {
            .table-card-body {
                max-height: 460px;
            }
        }

        @media (max-width: 767.98px) {
            .tabs-wrapper {
                margin-bottom: 0.5rem !important;
            }

            .tabs-wrapper .nav-tabs {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 6px;
                white-space: normal;
                overflow-x: visible;
                padding: 0.35rem;
            }

            .tabs-wrapper .nav-tabs .nav-item {
                margin: 0;
            }

            .tabs-wrapper .nav-tabs .nav-link {
                width: 100%;
                margin: 0;
                padding: 0.4rem 0.45rem;
                min-height: 38px;
                font-size: 0.78rem;
                line-height: 1.15;
                text-align: center;
            }

            .tab-content>.tab-pane>.row.g-3 {
                --bs-gutter-x: 0.5rem;
                --bs-gutter-y: 0.5rem;
            }

            .visualizados-table .viz-col-inst,
            .visualizados-table .viz-col-city {
                display: none;
            }

            .visualizados-table .viz-col-rank {
                width: 46px !important;
                text-align: center;
            }

            .visualizados-table .viz-col-views {
                width: 84px;
                white-space: nowrap;
                text-align: center;
            }

            .visualizados-table .viz-col-atleta {
                min-width: 160px;
            }

            .visualizados-table .viz-mobile-meta {
                display: block;
            }

            .report-card .card-body {
                padding: 0.75rem;
            }

            .table-card-body {
                max-height: 280px;
                padding: 0.4rem 0.55rem;
                -webkit-overflow-scrolling: touch;
            }

            .container,
            .report-grid {
                padding-left: 0.55rem;
                padding-right: 0.55rem;
            }

            .row.g-3>[class*='col-'] {
                flex-basis: 100% !important;
                max-width: 100% !important;
            }

            .atletas-altos-scroll {
                max-height: 260px;
                /* mobile igual às outras abas */
                -webkit-overflow-scrolling: touch;
            }
        }

        @media (min-width: 768px) {
            .report-card .card-body {
                padding: 1rem;
            }
        }

        @media (min-width: 1400px) {
            .container {
                max-width: 1200px;
            }
        }

        /* Scrollbar visual */
        .table-card-body::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        .table-card-body::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.12);
            border-radius: 6px;
        }

        /* Tabs wrapper */
        .tabs-wrapper .nav-tabs {
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 0.6rem;
            padding: 0.25rem;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(250, 250, 250, 0.95));
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
        }

        .tabs-wrapper .nav-tabs .nav-link {
            border: 0;
            background: transparent;
            color: #495057;
            padding: .45rem .9rem;
            margin: 0 .125rem;
            border-radius: .5rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            transition: background .12s ease, color .12s ease, box-shadow .12s ease;
            position: relative;
            z-index: 1;
            opacity: 0.9;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
            -webkit-user-select: none;
            user-select: none;
            outline: none;
        }

        .tabs-wrapper .nav-tabs .nav-link.active,
        .tabs-wrapper .nav-tabs .nav-link:focus,
        .tabs-wrapper .nav-tabs .nav-link:focus-visible,
        .tabs-wrapper .nav-tabs .nav-link:active,
        .tabs-wrapper .nav-tabs .nav-link[aria-selected="true"] {
            background: #0d6efd !important;
            color: #fff !important;
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.12);
            transform: none !important;
            border: 0 !important;
            z-index: 3;
            opacity: 1 !important;
            outline: none;
        }

        .tabs-wrapper .nav-tabs .nav-link:hover {
            background: rgba(13, 110, 253, 0.06);
            color: #0b5ed7;
            opacity: 1;
            text-decoration: none;
        }

        @media (max-width: 576px) {

            .tabs-wrapper .nav-tabs .nav-link.active,
            .tabs-wrapper .nav-tabs .nav-link:focus,
            .tabs-wrapper .nav-tabs .nav-link:focus-visible,
            .tabs-wrapper .nav-tabs .nav-link[aria-selected="true"],
            .tabs-wrapper .nav-tabs .nav-link:active {
                box-shadow: none;
                transform: none !important;
            }

            .tabs-wrapper .nav-tabs .nav-link {
                padding: .38rem .65rem;
            }
        }

        .tabs-wrapper .nav-tabs::-webkit-scrollbar {
            height: 6px;
        }

        .tabs-wrapper .nav-tabs::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.08);
            border-radius: 6px;
        }

        .tabs-wrapper .nav-tabs .nav-link:not(.active) {
            color: #495057;
        }

        .tabs-wrapper .nav-tabs .nav-link.active {
            color: #fff;
        }

        /* Deltas */
        .delta-positive {
            color: #198754;
        }

        .delta-negative {
            color: #dc3545;
        }

        .delta-neutral {
            color: #6c757d;
        }

        /* Destaque da faixa da maior altura (blindado contra .table-striped) */
        .row-maior-faixa {
            background-color: #fff3cd !important;
        }

        .row-maior-faixa>td {
            background-color: #fff3cd !important;
            font-weight: 600;
        }

        /* Badges sutis (fallback caso Bootstrap não tenha *-subtle) */
        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.12) !important;
            color: #0d6efd !important;
        }

        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.12) !important;
            color: #0aa2c0 !important;
        }

        .bg-secondary-subtle {
            background-color: rgba(108, 117, 125, 0.12) !important;
            color: #6c757d !important;
        }

        .text-info {
            color: #0aa2c0 !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }
        /* ===== Scroll REAL na aba "Atletas Altos" ===== */
        .atletas-altos-scroll {
            display: block !important;
            /* mata o flex da .table-card-body */
            overflow-y: auto !important;
            overflow-x: hidden !important;
            max-height: 320px;
            /* desktop (força aparecer com 10+ linhas) */
            padding: 0.5rem 1rem;
            -webkit-overflow-scrolling: touch;
        }

        /* mantém o scroll “bonito” */
        .atletas-altos-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .atletas-altos-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, .12);
            border-radius: 6px;
        }

        @media (max-width: 767.98px) {
            .atletas-altos-scroll {
                max-height: 410px;
            }
        }

        .olheiro-section-scroll {
            max-height: 240px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .olheiro-favoritos-scroll {
            overflow: hidden !important;
        }

        .olheiro-favoritos-scroll table {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 0;
        }

        .olheiro-favoritos-scroll thead,
        .olheiro-favoritos-scroll tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .olheiro-favoritos-scroll tbody {
            display: block;
            max-height: 108px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .olheiro-favoritos-scroll tbody td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tab-header-compact {
            padding: 0.45rem 0.75rem !important;
        }

        .tab-header-compact .label {
            font-size: 0.78rem !important;
            line-height: 1.1;
        }

        .olheiro-shortlists-scroll {
            overflow: hidden !important;
        }

        .olheiro-shortlists-scroll table {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 0;
        }

        .olheiro-shortlists-scroll thead,
        .olheiro-shortlists-scroll tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .olheiro-shortlists-scroll thead th:nth-child(1),
        .olheiro-shortlists-scroll tbody td:nth-child(1) {
            width: 26%;
        }

        .olheiro-shortlists-scroll thead th:nth-child(2),
        .olheiro-shortlists-scroll tbody td:nth-child(2) {
            width: 74%;
        }

        .olheiro-shortlists-scroll tbody {
            display: block;
            max-height: 204px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .olheiro-shortlists-scroll tbody td {
            vertical-align: middle;
        }

        .olheiro-shortlists-scroll tbody td:nth-child(1) {
            text-align: center;
        }

        .shortlist-itens-scroll {
            max-height: 68px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .shortlist-item-status {
            padding: 0.2rem 0.35rem;
            border-left: 4px solid #6c757d;
            border-radius: 6px;
            background: rgba(108, 117, 125, 0.12);
        }

        .shortlist-item-status.aprovado {
            border-left-color: #198754;
            background: rgba(25, 135, 84, 0.14);
        }

        .shortlist-item-status.reprovado {
            border-left-color: #dc3545;
            background: rgba(220, 53, 69, 0.14);
        }

        .shortlist-item-status.observacao {
            border-left-color: #ffc107;
            background: rgba(255, 193, 7, 0.18);
        }

        @media (max-width: 767.98px) {
            .tab-header-compact {
                padding: 0.4rem 0.6rem !important;
            }

            .tab-header-compact .label {
                font-size: 0.74rem !important;
            }

            .olheiro-shortlists-scroll thead th,
            .olheiro-shortlists-scroll tbody td {
                padding: 0.45rem 0.35rem;
                font-size: 0.8rem;
            }

            .olheiro-shortlists-scroll thead th {
                white-space: normal;
                line-height: 1.15;
                text-align: center;
            }

            .olheiro-shortlists-scroll thead th:nth-child(1),
            .olheiro-shortlists-scroll tbody td:nth-child(1) {
                width: 34%;
            }

            .olheiro-shortlists-scroll thead th:nth-child(2),
            .olheiro-shortlists-scroll tbody td:nth-child(2) {
                width: 66%;
                white-space: nowrap;
            }

            .olheiro-shortlists-scroll .atleta-entidade {
                display: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aba = new URLSearchParams(window.location.search).get('aba');
            if (!aba || aba === 'visao') return;

            const tabButton = document.getElementById('tab-' + aba);
            if (tabButton && window.bootstrap && bootstrap.Tab) {
                bootstrap.Tab.getOrCreateInstance(tabButton).show();
            }
        });
    </script>
@endpush





