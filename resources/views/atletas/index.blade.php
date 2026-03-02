@extends('layouts.app')

@section('content')
    <style>
        /* ===== Card Atleta ===== */
        .flip-card {
            height: 220px;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
        }

        .flip-card-inner {
            width: 100%;
            height: 100%;
            display: flex;
        }

        .flip-front {
            display: flex;
            width: 100%;
            height: 100%;
            position: relative;
            justify-content: center;
        }

        .flip-front .foto-front {
            flex: 0 0 65%;
            max-width: 65%;
            height: 100%;
            overflow: hidden;
        }

        .flip-front .foto-front img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            background: #dfe3ea;
            display: block;
            border-radius: 12px 0 0 12px;
        }

        .flip-front .info {
            flex: 0 0 35%;
            max-width: 35%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 12px;
            position: relative;
            z-index: 2;
            color: #fff;
        }

        .flip-front .info::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to left, rgba(10, 35, 66, 0.9), rgba(10, 35, 66, 0.3));
            z-index: -1;
            border-radius: 0 12px 12px 0;
        }

        .flip-front .info h3 {
            font-size: 14px;
            font-weight: 700;
            margin: 0 0 6px 0;
            line-height: 1.2;
            text-transform: uppercase;
            white-space: normal;
            text-overflow: ellipsis;
        }

        .flip-front .posicao {
            font-size: 27px;
            font-weight: 900;
            color: #e66000;
            margin: 8px 0 6px 0;
        }

        .badge-pos {
            background: #e66000;
            color: #fff;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 700;
        }

        .perfil-action-btn {
            width: 100%;
            max-width: 118px;
            min-width: 0;
            white-space: nowrap;
            display: inline-block;
            font-size: 0.82rem;
            padding: 0.3rem 0.5rem;
            line-height: 1.1;
        }

        .perfil-open-wrap {
            margin-top: auto;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 576px) {
            .perfil-action-btn {
                max-width: 106px;
                font-size: 0.78rem;
                padding: 0.26rem 0.4rem;
            }
        }

        strong {
            color: #28365F;
        }

        .foto-front {
            position: relative;
        }

        /* Badge: posição esquerda (mantém visual atual do badge) */
        .card-top-badge.top10-visualizado {
            position: absolute;
            top: 8px;
            left: 8px;
            right: auto;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 22px;
            font-size: 13px;
            font-weight: 700;
            color: #000 !important;
            /* texto principal em preto */
            z-index: 40;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: transform .18s ease, box-shadow .18s ease;
            background: linear-gradient(90deg, #ff9a00 0%, #ff5e62 100%);
        }

        /* Ícone: usa currentColor, garante preto */
        .card-top-badge.top10-visualizado .badge-icon svg,
        .card-top-badge.top10-visualizado .badge-icon svg * {
            width: 18px;
            height: 18px;
            display: block;
            color: #000 !important;
            fill: currentColor !important;
            stroke: currentColor !important;
        }

        /* Texto principal e subtítulo em preto */
        .card-top-badge.top10-visualizado .badge-main {
            display: inline-block;
            line-height: 1;
            color: #000 !important;
            text-shadow: none !important;
        }

        .card-top-badge.top10-visualizado .badge-sub {
            display: block;
            font-size: 10px;
            opacity: 0.95;
            font-weight: 600;
            color: #000 !important;
        }

        /* Destaque para top3 (mantém cores de fundo, mas texto/ícone continuam pretos) */
        .card-top-badge.rank-1 {
            background: linear-gradient(90deg, #ffd54a, #ffb300);
        }

        .card-top-badge.rank-2 {
            background: linear-gradient(90deg, #cfd8dc, #90a4ae);
        }

        .card-top-badge.rank-3 {
            background: linear-gradient(90deg, #d7b89a, #b07a3a);
        }

        /* Pulso opcional (mantém comportamento) */
        .card-top-badge.pulse {
            animation: topPulse 2.2s infinite ease-in-out;
        }

        /* Mobile: mantém texto e ícone pretos e compacta o badge */
        @media (max-width: 480px) {
            .card-top-badge.top10-visualizado {
                padding: 6px 8px;
                gap: 6px;
                font-size: 12px;
                left: 8px;
            }

            .card-top-badge.top10-visualizado .badge-main,
            .card-top-badge.top10-visualizado .badge-sub,
            .card-top-badge.top10-visualizado .badge-icon svg {
                color: #000 !important;
                fill: currentColor !important;
                stroke: currentColor !important;
            }
        }

        /* garante que o container do card permita posicionamento absoluto do badge */
        .flip-card.visualizar-atleta {
            position: relative;
            overflow: visible;
        }

        @media (max-width: 768px) {
            .flip-card {
                height: 214px;
            }

            .flip-front {
                justify-content: flex-start;
                width: 118%;
            }

            .flip-front .foto-front {
                flex: 0 0 50%;
            }

            .flip-front .info {
                flex: 0 0 50%;
                padding: 12px;
            }

            .flip-front .info h3 {
                font-size: 14px;
            }

            .flip-front .posicao {
                font-size: 25px;
            }

            .atleta-card {
                flex: 0 0 100%;
                max-width: 100%;
            }

            #lista-atletas .atleta-card:last-child {
                margin-bottom: 0.7rem;
            }

            #form-filtros .col-12.d-flex.gap-2 button {
                width: 50%;
            }

            #form-filtros,
            #lista-atletas {
                padding-left: 10px;
                padding-right: 10px;
            }

            #form-filtros input,
            #form-filtros select,
            #form-filtros button,
            .atleta-card {
                width: 100%;
                /* ocupa toda a largura disponível */
                max-width: 100%;
                /* garante que não encolha demais */
            }
        }

        .filtros-row {
            margin-bottom: 4px;
        }

        .filtros-card {
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 12px;
            background: rgba(18, 36, 74, 0.35);
            padding: 0.5rem;
        }

        .btn-ordenar-vis {
            min-height: 34px;
            font-size: 1rem;
            font-weight: 600;
        }

        .filtro-btn {
            min-height: 34px;
            padding: 0.26rem 0.68rem;
            font-size: 0.88rem;
            line-height: 1.1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .filtro-actions {
            display: flex;
            gap: 8px;
            align-items: flex-end;
            justify-content: flex-end;
            margin-top: 2px;
        }

        .filtro-actions .filtro-btn {
            flex: 1 1 0;
            min-width: 104px;
        }

        #form-filtros .mt-2 {
            margin-top: 2.0rem !important;
        }

        #form-filtros .filtro-ativo {
            border-color: #ff7209 !important;
            box-shadow: 0 0 0 0.16rem rgba(255, 114, 9, 0.28);
            background: #fffaf6;
        }

        .filtro-resultados-meta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.25rem 0.52rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            background: rgba(255, 114, 9, 0.95);
        }

        .paginacao-box {
            margin-top: 0.45rem;
            margin-bottom: 0.65rem;
            text-align: center;
        }

        .paginacao-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #fff;
            border-radius: 999px;
            padding: 0.22rem 0.64rem;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .paginacao-box .pagination {
            margin-bottom: 0;
        }

        .paginacao-box .page-link {
            min-width: 84px;
            text-align: center;
            font-weight: 700;
            padding-top: 0.28rem;
            padding-bottom: 0.28rem;
        }

        @media (max-width: 767.98px) {
            .filtro-actions {
                justify-content: stretch;
            }

            .filtro-actions .filtro-btn {
                min-width: 0;
            }

            .paginacao-box .page-link {
                min-width: 84px;
                font-size: 0.9rem;
            }
        }
    </style>

    <div class="container">
        @php $ordenandoPorVisualizacoes = request('ordenar') === 'visualizacoes'; @endphp
        <div class="filtros-card mb-3">

        <a href="{{ route('atletas.index', array_merge(request()->query(), ['ordenar' => 'visualizacoes'])) }}"
            class="btn w-100 btn-ordenar-vis {{ $ordenandoPorVisualizacoes ? 'btn-dark' : 'btn-outline-secondary' }}"
            style="background:{{ $ordenandoPorVisualizacoes ? '#333' : '#e66000' }}; color:white">
            Ordenar por Visualizações 👁️
        </a>

        {{-- FORMULÁRIO DE FILTROS --}}
        <form method="GET" action="{{ route('atletas.index') }}" id="form-filtros" class="row filtros-row g-2 mb-1">
            <div class="col-12 col-md-4">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control"
                    value="{{ request('nome') }}" placeholder="Digite o nome do atleta..."
                    autocomplete="off" {{ request('nome') ? 'autofocus' : '' }}>
            </div>
            <div class="col-6 col-md-2">
                <label for="idade_min">Idade Mín</label>
                <input type="number" name="idade_min" id="idade_min" class="form-control" min="0"
                    value="{{ request('idade_min') }}">
            </div>
            <div class="col-6 col-md-2">
                <label for="idade_max">Idade Máx</label>
                <input type="number" name="idade_max" id="idade_max" class="form-control" min="0"
                    value="{{ request('idade_max') }}">
            </div>
            <div class="col-md-4">
                <label for="posicao">Posição</label>
                <select name="posicao" id="posicao" class="form-control">
                    <option value="">Todas</option>
                    @foreach ($posicoes as $p)
                        @php $val = is_object($p) ? $p->posicao_jogo : $p; @endphp
                        <option value="{{ $val }}" {{ request('posicao') === $val ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="cidade">Cidade</label>
                <select name="cidade" id="cidade" class="form-control">
                    <option value="">Todas</option>
                    @foreach ($cidades as $c)
                        @php $val = is_object($c) ? $c->cidade : $c; @endphp
                        <option value="{{ $val }}" {{ request('cidade') === $val ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="entidade">Entidade</label>
                <select name="entidade" id="entidade" class="form-control">
                    <option value="">Todas</option>
                    @foreach ($entidades as $e)
                        @php $val = is_object($e) ? $e->entidade : $e; @endphp
                        <option value="{{ $val }}" {{ request('entidade') === $val ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-4 mt-2">
                <div class="filtro-actions">
                    <button type="submit" class="btn filtro-btn" style="background:#e66000; color:#fff">
                        Filtrar
                    </button>
                    <a href="{{ route('atletas.index') }}" class="btn btn-outline-secondary filtro-btn">
                        Limpar
                    </a>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-start mb-1">
            <span class="filtro-resultados-meta">{{ $atletas->total() }} atleta(s) encontrado(s)</span>
        </div>
        </div>

        {{-- LISTA DE CARDS --}}
        <div class="row g-3" id="lista-atletas">
            @forelse($atletas as $atleta)
                @php
                    // garante que $top10Visualizados exista e converte para inteiros/strings conforme necessário
                    $top10Ids = isset($top10Visualizados) ? array_values((array) $top10Visualizados) : [];
                    // tenta encontrar a posição (0-based). Ajuste para ->id se você pluckou id no controller.
                    $searchKey = (string) ($atleta->id ?? ($atleta->phpid ?? ''));
                    $posIndex = array_search($searchKey, array_map('strval', $top10Ids), true);
                    $isTop10 = $posIndex !== false;
                    $rank = $isTop10 ? $posIndex + 1 : null;
                    // classe de destaque para top3
                    $rankClass = $rank === 1 ? 'rank-1' : ($rank === 2 ? 'rank-2' : ($rank === 3 ? 'rank-3' : ''));
                    // pulse apenas para top3 (opcional) ou para todos top10 troque a condição
                    $pulseClass = $rank && $rank <= 3 ? 'pulse' : '';
                @endphp
                <div class="col-12 col-md-4 text-center atleta-card" data-idade="{{ $atleta->idade }}"
                    data-posicao="{{ strtolower($atleta->posicao_jogo) }}" data-cidade="{{ strtolower($atleta->cidade) }}"
                    data-entidade="{{ strtolower($atleta->entidade) }}">
                    <div class="flip-card visualizar-atleta">
                        <div class="flip-card-inner">
                            <div class="flip-front">
                                <div class="foto-front position-relative">
                                    @if ($isTop10)
                                        <div class="card-top-badge top10-visualizado {{ $rankClass }} {{ $pulseClass }}"
                                            title="Top {{ $rank }} mais visualizados" role="status"
                                            aria-label="Top {{ $rank }} mais visualizados">
                                            <span class="badge-icon" aria-hidden="true">
                                                {{-- ícone estrela/coroa combinado (SVG) --}}
                                                @if ($rank === 1)
                                                    <!-- coroa para o 1º -->
                                                    <svg viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path d="M3 7l4 2 3-4 4 4 3-2 2 6H3l0-6z" fill="currentColor" />
                                                        <path d="M5 19h14v2H5z" fill="rgba(0,0,0,0.08)" />
                                                    </svg>
                                                @elseif($rank === 2)
                                                    <!-- estrela para 2º -->
                                                    <svg viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path
                                                            d="M12 2l2.9 6.3L21 9l-5 3.9L17.8 21 12 17.6 6.2 21 7 12.9 2 9l6.1-0.7L12 2z"
                                                            fill="currentColor" />
                                                    </svg>
                                                @elseif($rank === 3)
                                                    <!-- medalha/estrela pequena para 3º -->
                                                    <svg viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <circle cx="12" cy="10" r="3" fill="currentColor" />
                                                        <path d="M7 14v6l5-3 5 3v-6" fill="currentColor" />
                                                    </svg>
                                                @else
                                                    <!-- ícone genérico para top10 -->
                                                    <svg viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path
                                                            d="M12 2l2.9 6.3L21 9l-5 3.9L17.8 21 12 17.6 6.2 21 7 12.9 2 9l6.1-0.7L12 2z"
                                                            fill="currentColor" />
                                                    </svg>
                                                @endif
                                            </span>

                                            <span class="badge-main">
                                                Top {{ $rank }}
                                                <span class="badge-sub">Visualizado</span>
                                            </span>
                                        </div>
                                    @endif
                                    <img src="{{ $atleta->imagem_base64 ? 'data:image/png;base64,' . $atleta->imagem_base64 : asset('img/avatar.png') }}"
                                        alt="Foto de {{ $atleta->nome_completo }}">
                                </div>
                                <div class="info">
                                    <h3>{{ strtoupper($atleta->nome_completo) }}</h3>
                                    <div class="posicao">{{ $atleta->posicao_jogo }}</div>
                                    <div class="badge-pos viz-counter-wrapper" style="margin-top:6px;">
                                        👁️
                                        <span id="visualizacoes-{{ $atleta->id }}">
                                            {{ (int) $atleta->visualizacoes }}
                                        </span>
                                    </div>
                                    <div class="perfil-open-wrap">
                                        <a href="{{ route('atletas.show', $atleta->id) }}"
                                            class="btn btn-sm btn-light perfil-action-btn perfil-open-link"
                                            data-track-url="{{ url('/atleta/visualizar/' . $atleta->id) }}">
                                            Ver perfil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-secondary">Nenhum atleta encontrado.</div>
                </div>
            @endforelse
        </div>

        {{-- PAGINAÇÃO --}}
        <div class="paginacao-box">
            <div class="paginacao-status">Página {{ $atletas->currentPage() }} de {{ $atletas->lastPage() }}</div>
            <div class="d-flex justify-content-center mt-2">
                {{ $atletas->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    </div>

    <script>
        // CSRF helper
        function getCsrf() {
            const m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.getAttribute('content') : '{{ csrf_token() }}';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const formFiltros = document.getElementById('form-filtros');
            document.querySelectorAll('.perfil-open-link').forEach(link => {
                link.addEventListener('click', () => {
                    const trackUrl = link.dataset.trackUrl;
                    if (!trackUrl) {
                        return;
                    }

                    fetch(trackUrl, {
                        method: 'POST',
                        keepalive: true,
                        headers: {
                            'X-CSRF-TOKEN': getCsrf(),
                            'Content-Type': 'application/json'
                        },
                        body: '{}'
                    }).catch(() => {});
                });
            });

            // Controle de desativação de filtros
            const nomeEl = document.getElementById('nome');
            const idadeMin = document.getElementById('idade_min');
            const idadeMax = document.getElementById('idade_max');
            const posicaoEl = document.getElementById('posicao');
            const cidadeEl = document.getElementById('cidade');
            const entidadeEl = document.getElementById('entidade');

            function toggleFiltros() {
                const min = idadeMin.value.trim();
                const max = idadeMax.value.trim();

                if (min && max) {
                    posicaoEl.disabled = cidadeEl.disabled = entidadeEl.disabled = true;
                } else if (posicaoEl.value) {
                    idadeMin.disabled = idadeMax.disabled = cidadeEl.disabled = entidadeEl.disabled = true;
                } else if (cidadeEl.value) {
                    idadeMin.disabled = idadeMax.disabled = posicaoEl.disabled = entidadeEl.disabled = true;
                } else if (entidadeEl.value) {
                    idadeMin.disabled = idadeMax.disabled = posicaoEl.disabled = cidadeEl.disabled = true;
                } else {
                    idadeMin.disabled = idadeMax.disabled = posicaoEl.disabled = cidadeEl.disabled = entidadeEl
                        .disabled = false;
                }
            }

            function atualizarFiltrosAtivos() {
                [nomeEl, idadeMin, idadeMax, posicaoEl, cidadeEl, entidadeEl].forEach(el => {
                    if (!el) {
                        return;
                    }
                    const valor = String(el.value || '').trim();
                    el.classList.toggle('filtro-ativo', valor !== '');
                });
            }

            [idadeMin, idadeMax, posicaoEl, cidadeEl, entidadeEl].forEach(el => {
                if (el) {
                    el.addEventListener('input', function() {
                        toggleFiltros();
                        atualizarFiltrosAtivos();
                    });
                    el.addEventListener('change', function() {
                        toggleFiltros();
                        atualizarFiltrosAtivos();
                    });
                }
            });

            let nomeDebounce = null;
            let ultimoNomeSubmetido = (nomeEl ? (nomeEl.value || '').trim() : '');
            if (nomeEl && formFiltros) {
                const restoreCursor = function() {
                    const fim = nomeEl.value.length;
                    nomeEl.focus();
                    if (nomeEl.setSelectionRange) {
                        nomeEl.setSelectionRange(fim, fim);
                    }
                };

                if (nomeEl.value.trim() !== '') {
                    restoreCursor();
                }

                nomeEl.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        ultimoNomeSubmetido = nomeEl.value.trim();
                        formFiltros.submit();
                    }
                });

                nomeEl.addEventListener('input', function() {
                    atualizarFiltrosAtivos();
                    clearTimeout(nomeDebounce);
                    nomeDebounce = setTimeout(function() {
                        const nomeAtual = nomeEl.value.trim();
                        if (nomeAtual !== '' && nomeAtual.length < 3) {
                            return;
                        }
                        if (nomeAtual === ultimoNomeSubmetido) {
                            return;
                        }
                        ultimoNomeSubmetido = nomeAtual;
                        formFiltros.submit();
                    }, 900);
                });
            }

            toggleFiltros();
            atualizarFiltrosAtivos();
        });
    </script>
@endsection

