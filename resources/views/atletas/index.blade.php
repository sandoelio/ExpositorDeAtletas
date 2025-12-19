@extends('layouts.app')

@section('content')
    <style>
        /* ===== Flip Card ===== */
        .flip-card {
            perspective: 1000px;
            height: 240px;
            cursor: pointer;
            position: relative;
        }

        .flip-card-inner {
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            position: relative;
            border-radius: 12px;
        }

        .flip-card.is-flipped .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-front,
        .flip-back {
            position: absolute;
            inset: 0;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
        }

        .flip-front {
            display: flex;
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
            width: 104%;
            height: 106%;
            object-fit: inherit;
            display: block;
            border-radius: 12px 0 0 12px;
        }

        .flip-front .info {
            flex: 0 0 35%;
            max-width: 35%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 16px;
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
            font-size: 15px;
            font-weight: 500;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            white-space: normal;
            text-overflow: ellipsis;
        }

        .flip-front .posicao {
            font-size: 25px;
            font-weight: 900;
            color: #e66000;
            margin: 14px 0 6px 0;
        }

        .flip-front .toque-detalhes {
            font-size: 12px;
            opacity: 0.8;
            margin-top: auto;
        }

        .flip-back {
            background: linear-gradient(135deg, #FF7F50, #FF7F50 40%, #FF4500);
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            color: #fff;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            padding: 5px;
        }

        .flip-back .conteudo {
            display: flex;
            /* gap: 12px; */
            flex: 1;
            align-items: start;
        }

        .flip-back .foto-back {
            flex: 0 0 100px;
        }

        .flip-back .foto-back img {
            width: 95px;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
        }

        .flip-back .dados {
            flex: 1;
            font-size: 15px;
            line-height: 1.25;
            align-items: flex-end;
            text-align: left;
        }

        .flip-back .dados p {
            margin: 2px 0;
        }

        .badge-pos,
        .badge-back {
            background: #e66000;
            color: #fff;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 700;
        }

        .badge-back {
            padding: 1px 1px;
        }

        .video-link {
            color: #fff;
            text-decoration: underline;
        }

        .video-link:hover {
            text-decoration: none;
            color: #000;
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
                height: 240px;
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

            .flip-back .foto-back {
                flex: 0 0 80px;
            }

            .flip-back .foto-back img {
                width: 100px;
                height: 150px;
            }

            .flip-back .dados {
                font-size: 15px;
            }

            .atleta-card {
                flex: 0 0 100%;
                max-width: 100%;
            }

            #lista-atletas .atleta-card:last-child {
                margin-bottom: 4rem;
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
            margin-bottom: 18px;
        }
    </style>

    <div class="container">
        <div class="mb-3">
            <div class="mb-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary"
                    style="background:#e66000; color:white; font-size: 1.4rem;" title="Voltar para a Home">
                    <i class="fas fa-home"></i>
                </a>
            </div>
        </div>

        @php $ordenandoPorVisualizacoes = request('ordenar') === 'visualizacoes'; @endphp

        <a href="{{ route('atletas.index', array_merge(request()->query(), ['ordenar' => 'visualizacoes'])) }}"
            class="btn w-100 {{ $ordenandoPorVisualizacoes ? 'btn-dark' : 'btn-outline-secondary' }}"
            style="background:{{ $ordenandoPorVisualizacoes ? '#333' : '#e66000' }}; color:white">
            Ordenar por Visualizações 👁️
        </a>

        {{-- FORMULÁRIO DE FILTROS --}}
        <form method="GET" action="{{ route('atletas.index') }}" id="form-filtros" class="row filtros-row g-2 mb-4">
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
            <div class="col-md-3">
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
            <div class="col-md-3">
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
            <div class="col-md-2">
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

            <div class="col-12 col-md-6 mt-2 d-flex gap-2">
                <button type="submit" class="btn flex-fill" style="background:#e66000; color:#fff">
                    Filtrar
                </button>
                <a href="{{ route('atletas.index') }}" class="btn btn-outline-secondary flex-fill">
                    Limpar
                </a>
            </div>
        </form>

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
                    <div class="flip-card visualizar-atleta" data-id="{{ $atleta->id }}"
                        data-url="{{ url('/atleta/visualizar/' . $atleta->id) }}">
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
                                    <small class="toque-detalhes">Toque para ver detalhes</small>
                                    <div class="badge-pos viz-counter-wrapper" style="margin-top:6px;">
                                        👁️
                                        <span id="visualizacoes-{{ $atleta->id }}">
                                            {{ (int) $atleta->visualizacoes }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flip-back">
                                <div class="conteudo">
                                    <div class="foto-back">
                                        <img src="{{ asset('img/basket-silhouette.png') }}"
                                            alt="Foto de {{ $atleta->nome_completo }}">
                                    </div>
                                    <div class="dados">
                                        <p><strong>Idade:</strong> {{ $atleta->idade }}</p>
                                        <p><strong>Altura (cm):</strong> {{ $atleta->altura }}</p>
                                        <p><strong>Peso (kg):</strong> {{ $atleta->peso }}</p>
                                        <p><strong>Cidade:</strong> {{ $atleta->cidade }}</p>
                                        <p><strong>Treina:</strong> {{ $atleta->entidade }}</p>
                                        <p><strong>Contato:</strong> {{ $atleta->contato }}</p>
                                        <p><strong>Link:</strong>
                                            <a href="{{ $atleta->resumo }}" target="_blank" rel="noopener noreferrer"
                                                class="video-link">
                                                {{ $atleta->resumo }}
                                            </a>
                                        </p>
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
        <div class="d-flex justify-content-center mt-4 mb-5">
            {{ $atletas->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
        </div>
    </div>

    <script>
        // CSRF helper
        function getCsrf() {
            const m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.getAttribute('content') : '{{ csrf_token() }}';
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.flip-card.visualizar-atleta')
                .forEach(card => {
                    card.addEventListener('click', async () => {
                        card.classList.toggle('is-flipped');
                        if (!card.classList.contains('is-flipped')) return;

                        const id = card.dataset.id;
                        const counter = document.getElementById('visualizacoes-' + id);
                        const baseUrl = card.dataset.url;

                        try {
                            const resp = await fetch(baseUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': getCsrf(),
                                    'Content-Type': 'application/json'
                                }
                            });
                            const data = await resp.json();
                            if (data.visualizacoes !== undefined) {
                                counter.textContent = data.visualizacoes;
                                return;
                            }
                        } catch {
                            console.error('Erro ao registrar visualização.');
                        }
                        counter.textContent = +counter.textContent + 1;
                    });
                });

            // Controle de desativação de filtros
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

            [idadeMin, idadeMax, posicaoEl, cidadeEl, entidadeEl].forEach(el => {
                if (el) {
                    el.addEventListener('input', toggleFiltros);
                    el.addEventListener('change', toggleFiltros);
                }
            });

            toggleFiltros();
        });
    </script>
@endsection
