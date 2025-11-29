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

        @keyframes pulseOuro {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
            }

            50% {
                transform: scale(1.15);
                box-shadow: 0 0 12px rgb(255, 251, 0);
                /* dourado */
            }
        }

        @keyframes pulsePrata {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
            }

            50% {
                transform: scale(1.15);
                box-shadow: 0 0 12px rgba(236, 234, 234, 0.705);
                /* prata */
            }
        }

        @keyframes pulseBronze {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
            }

            50% {
                transform: scale(1.15);
                box-shadow: 0 0 12px rgba(205, 127, 50, 0.6);
                /* bronze */
            }
        }

        .medalha-badge-img {
            position: absolute;
            top: 7px;
            /* right: 6px; */
            left: 8px;
            font-size: 1.6rem;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 50%;
            padding: 2px 4px;
            z-index: 5;
        }

        .medalha-ouro {
            animation: pulseOuro 1.8s infinite ease-in-out;
        }

        .medalha-prata {
            animation: pulsePrata 1.8s infinite ease-in-out;
        }

        .medalha-bronze {
            animation: pulseBronze 1.8s infinite ease-in-out;
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
                    $medalha = null;
                    $classeAnimacao = '';
                    if ($atleta->visualizacoes >= 1000) {
                        $medalha = ['emoji' => '🥇', 'title' => 'Medalha de Ouro'];
                        $classeAnimacao = 'medalha-ouro';
                    } elseif ($atleta->visualizacoes >= 500) {
                        $medalha = ['emoji' => '🥈', 'title' => 'Medalha de Prata'];
                        $classeAnimacao = 'medalha-prata';
                    } elseif ($atleta->visualizacoes >= 100) {
                        $medalha = ['emoji' => '🥉', 'title' => 'Medalha de Bronze'];
                        $classeAnimacao = 'medalha-bronze';
                    }
                @endphp
                <div class="col-12 col-md-4 text-center atleta-card" data-idade="{{ $atleta->idade }}"
                    data-posicao="{{ strtolower($atleta->posicao_jogo) }}" data-cidade="{{ strtolower($atleta->cidade) }}"
                    data-entidade="{{ strtolower($atleta->entidade) }}">
                    <div class="flip-card visualizar-atleta" data-id="{{ $atleta->id }}"
                        data-url="{{ url('/atleta/visualizar/' . $atleta->phpid) }}">
                        <div class="flip-card-inner">
                            <div class="flip-front">
                                <div class="foto-front position-relative">
                                    {{-- Medalha sobre a imagem --}}
                                    @if ($medalha)
                                        <div class="medalha-badge-img {{ $classeAnimacao }}"
                                            title="{{ $medalha['title'] }}">
                                            {{ $medalha['emoji'] }}
                                        </div>
                                    @endif
                                    <img src="{{ $atleta->imagem_base64 ? 'data:image/png;base64,' . $atleta->imagem_base64 : asset('img/avatar.png') }}"
                                        alt="Foto de {{ $atleta->nome_completo }}">
                                </div>
                                <div class="info">
                                    <h3>{{ strtoupper($atleta->nome_completo) }}</h3>
                                    <div class="posicao">{{ $atleta->posicao_jogo }}</div>
                                    <small class="toque-detalhes">
                                        Toque para ver detalhes
                                    </small>
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
                    <div class="alert alert-secondary">
                        Nenhum atleta encontrado.
                    </div>
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
                            // fallback: incrementa
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
