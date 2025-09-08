@extends('layouts.app')

@section('content')
    <style>
        /* ===== Flip Card ===== */
        .flip-card {
            perspective: 1000px;
            height: 200px;
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
            width: 100%;
            height: 100%;
            object-fit: cover;
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
            gap: 12px;
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

        @media (max-width: 768px) {
            .flip-card {
                height: 100%;
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
                font-size: 30px;
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
                /* margin-bottom: 1rem; */
            }

            /* garante um extra só depois do último card */
            #lista-atletas .atleta-card:last-child {
                margin-bottom: 4rem;
            }

            #form-filtros .col-12.d-flex.gap-2 button {
                width: 50%;
            }

        }

        .filtros-row {
            margin-bottom: 18px;
        }
    </style>

    <div class="container">
        <div class="mb-3">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="background:#e66000;color:white">
                Voltar para a Home
            </a>
        </div>

        {{-- ===== FILTROS ===== --}}
        <form id="form-filtros" class="row filtros-row g-2" onsubmit="event.preventDefault(); buscarAtletas();">
            <div class="col-md-2">
                <label for="idade_min">Idade Min</label>
                <input type="number" id="idade_min" class="form-control" min="0" placeholder="Min">
            </div>
            <div class="col-md-2">
                <label for="idade_max">Idade Max</label>
                <input type="number" id="idade_max" class="form-control" min="0" placeholder="Max">
            </div>
            <div class="col-md-3">
                <label for="posicao">Posição</label>
                <select id="posicao" class="form-control">
                    <option value="">Todas</option>
                    @if (isset($posicoes) && count($posicoes))
                        @foreach ($posicoes as $p)
                            <option value="{{ is_object($p) ? $p->posicao_jogo : $p }}">
                                {{ is_object($p) ? $p->posicao_jogo : $p }}
                            </option>
                        @endforeach
                    @else
                        <option>Ala</option>
                        <option>Armador</option>
                        <option>Pivô</option>
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label for="cidade">Cidade</label>
                <select id="cidade" class="form-control">
                    <option value="">Todas</option>
                    @if (isset($cidades) && count($cidades))
                        @foreach ($cidades as $c)
                            <option value="{{ is_object($c) ? $c->cidade : $c }}">{{ is_object($c) ? $c->cidade : $c }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <label for="entidade">Entidade</label>
                <select id="entidade" class="form-control">
                    <option value="">Todas</option>
                    @if (isset($entidades) && count($entidades))
                        @foreach ($entidades as $e)
                            <option value="{{ is_object($e) ? $e->entidade : $e }}">{{ is_object($e) ? $e->entidade : $e }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-12 col-md-6 mt-2 d-flex gap-2">
                <button type="button" class="btn flex-fill" style="background:#e66000;color:#fff"
                    onclick="buscarAtletas()">Filtrar</button>
                <button type="button" class="btn btn-outline-secondary flex-fill" onclick="limparFiltros()">Limpar</button>
            </div>
        </form>

        {{-- ===== CARDS ===== --}}
        <div class="row g-3" id="lista-atletas">
            @forelse($atletas as $atleta)
                <div class="col-12 col-md-4 text-center atleta-card" data-idade="{{ $atleta->idade ?? '' }}"
                    data-posicao="{{ strtolower($atleta->posicao_jogo ?? '') }}"
                    data-cidade="{{ strtolower($atleta->cidade ?? '') }}"
                    data-entidade="{{ strtolower($atleta->entidade ?? '') }}">
                    <div class="flip-card visualizar-atleta" data-id="{{ $atleta->id }}"
                        data-url-secure="{{ secure_url('/atleta/visualizar') }}"
                        data-url-local="{{ url('/atleta/visualizar') }}">
                        <div class="flip-card-inner">
                            <div class="flip-front">
                                <div class="foto-front">
                                    <img src="{{ !empty($atleta->imagem_base64) ? 'data:image/png;base64,' . $atleta->imagem_base64 : asset('img/avatar.png') }}"
                                        alt="Foto de {{ $atleta->nome_completo }}">
                                </div>
                                <div class="info">
                                    <h3>{{ strtoupper($atleta->nome_completo) }}</h3>
                                    <div class="posicao">{{ $atleta->posicao_jogo }}</div>
                                    <small class="toque-detalhes">Toque para ver detalhes</small>
                                    <div class="viz-counter-wrapper badge-pos" style="margin-top:6px;">
                                        👁️ <span class="viz-counter"
                                            id="visualizacoes-{{ $atleta->id }}">{{ (int) ($atleta->visualizacoes ?? 0) }}</span>
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
                                        <p class="badge-back"><strong>Idade:</strong> {{ $atleta->idade ?? '—' }}</p>
                                        <p class="badge-back"><strong>Altura (cm):</strong> {{ $atleta->altura ?? '—' }}
                                        </p>
                                        <p class="badge-back"><strong>Peso (kg):</strong> {{ $atleta->peso ?? '—' }}</p>
                                        <p class="badge-back"><strong>Cidade:</strong> {{ $atleta->cidade ?? '—' }}</p>
                                        <p class="badge-back"><strong>Treina:</strong> {{ $atleta->entidade ?? '—' }}</p>
                                        <p class="badge-back"><strong>Contato:</strong> {{ $atleta->contato ?? '—' }}</p>
                                        <p><strong>Link:</strong> <a href="{{ $atleta->resumo }}" target="_blank"
                                                rel="noopener noreferrer" class="video-link">{{ $atleta->resumo }}</a></p>
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
    </div>

    <script>
        function getCsrf() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '{{ csrf_token() }}';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Flip + registro de visualização
            document.querySelectorAll('.flip-card.visualizar-atleta').forEach(card => {
                card.addEventListener('click', async function() {
                    this.classList.toggle('is-flipped');
                    if (!this.classList.contains('is-flipped')) return;

                    const id = this.dataset.id;
                    const counterEl = document.getElementById('visualizacoes-' + id);

                    // Ajuste de URL localhost / servidor
                    let base;
                    const hostname = window.location.hostname;
                    if (hostname === '127.0.0.1' || hostname === 'localhost') {
                        base = this.dataset.urlLocal;
                    } else {
                        base = this.dataset.urlSecure;
                    }

                    const postUrl = base + '/' + id;

                    try {
                        const resp = await fetch(postUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': getCsrf(),
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await resp.json();
                        if (data && data.visualizacoes !== undefined) {
                            counterEl.textContent = data.visualizacoes;
                        } else {
                            counterEl.textContent = parseInt(counterEl.textContent) + 1;
                        }
                    } catch (err) {
                        counterEl.textContent = parseInt(counterEl.textContent) + 1;
                    }
                });
            });

            // ===== Controle de desativação de filtros =====
            const idadeMin = document.getElementById('idade_min');
            const idadeMax = document.getElementById('idade_max');
            const posicao = document.getElementById('posicao');
            const cidade = document.getElementById('cidade');
            const entidade = document.getElementById('entidade');

            function toggleFiltros() {
                if (!idadeMin || !idadeMax || !posicao || !cidade || !entidade) return;

                const min = idadeMin.value.trim();
                const max = idadeMax.value.trim();

                if (min && max) {
                    posicao.disabled = true;
                    cidade.disabled = true;
                    entidade.disabled = true;
                } else if (posicao.value) {
                    idadeMin.disabled = true;
                    idadeMax.disabled = true;
                    cidade.disabled = true;
                    entidade.disabled = true;
                } else if (cidade.value) {
                    idadeMin.disabled = true;
                    idadeMax.disabled = true;
                    posicao.disabled = true;
                    entidade.disabled = true;
                } else if (entidade.value) {
                    idadeMin.disabled = true;
                    idadeMax.disabled = true;
                    posicao.disabled = true;
                    cidade.disabled = true;
                } else {
                    idadeMin.disabled = false;
                    idadeMax.disabled = false;
                    posicao.disabled = false;
                    cidade.disabled = false;
                    entidade.disabled = false;
                }
            }

            [idadeMin, idadeMax, posicao, cidade, entidade].forEach(el => {
                if (el) {
                    el.addEventListener('input', toggleFiltros);
                    el.addEventListener('change', toggleFiltros);
                }
            });

            toggleFiltros();
        });

        // ===== FILTRAGEM =====
        function buscarAtletas() {
            const min = document.getElementById('idade_min').value.trim();
            const max = document.getElementById('idade_max').value.trim();
            const pos = document.getElementById('posicao').value.trim().toLowerCase();
            const cidade = document.getElementById('cidade').value.trim().toLowerCase();
            const entidade = document.getElementById('entidade').value.trim().toLowerCase();

            document.querySelectorAll('.atleta-card').forEach(card => {
                const cardIdade = card.dataset.idade ? parseInt(card.dataset.idade) : null;
                const cardPos = (card.dataset.posicao || '').toLowerCase();
                const cardCidade = (card.dataset.cidade || '').toLowerCase();
                const cardEntidade = (card.dataset.entidade || '').toLowerCase();

                let ok = true;
                if (min && !isNaN(min) && (cardIdade === null || cardIdade < parseInt(min))) ok = false;
                if (max && !isNaN(max) && (cardIdade === null || cardIdade > parseInt(max))) ok = false;
                if (pos && pos !== cardPos) ok = false;
                if (cidade && !cardCidade.includes(cidade)) ok = false;
                if (entidade && !cardEntidade.includes(entidade)) ok = false;

                card.style.display = ok ? '' : 'none';
            });
        }

        function limparFiltros() {
            document.getElementById('form-filtros').reset();
            document.querySelectorAll('.atleta-card').forEach(card => card.style.display = '');
            document.querySelectorAll('#form-filtros select, #form-filtros input').forEach(el => el.disabled = false);
        }
    </script>
@endsection
