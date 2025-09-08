@extends('layouts.app')

@section('content')
    <style>
        /* ===== Flip Card ===== */
        .flip-card {
            perspective: 1000px;
            height: 200px;
            /* altura reduzida para desktop */
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

        /* Frente */
        .flip-front {
            display: flex;
            height: 100%;
            position: relative;
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
            /* permite que ocupe várias linhas */
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

        /* Verso */
        .flip-back {
            /* background-color: #FF7F50; */
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

        .badge-pos {
            background: #e66000;
            color: #fff;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 700;
        }

        .badge-back {
            background: #e66000;
            color: #fff;
            padding: 1px 1px;
            border-radius: 6px;
            font-weight: 700;
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

        /* ===== Ajustes Mobile ===== */
        @media (max-width: 768px) {
            .flip-card {
                height: 180px;
                /* altura reduzida em mobile */
            }

            .flip-front .foto-front {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .flip-front .info {
                flex: 0 0 50%;
                max-width: 50%;
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
        }
    </style>

<div class="container">
    <div class="mb-3">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="background:#e66000;color:white">
            Voltar para a Home
        </a>
    </div>

        {{-- falta : filtros e js, paginação --}}

        {{-- Lista de Atletas --}}
        <div class="row g-3" id="lista-atletas">
            @forelse($atletas as $atleta)
                <div class="col-md-4 text-center">
                    <div class="flip-card visualizar-atleta" data-id="{{ $atleta->id }}"
                        data-url-secure="{{ secure_url('/atleta/visualizar') }}"
                        data-url-local="{{ url('/atleta/visualizar') }}">
                        <div class="flip-card-inner">

                            {{-- Frente --}}
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

                            {{-- Verso --}}
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
            if (meta) return meta.getAttribute('content');
            return '{{ csrf_token() }}';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.flip-card.visualizar-atleta').forEach(card => {
                card.addEventListener('click', async function() {
                    this.classList.toggle('is-flipped');
                    if (!this.classList.contains('is-flipped')) return;

                    const id = this.dataset.id;
                    const counterEl = document.getElementById('visualizacoes-' + id);
                    const baseSecure = (this.dataset.urlSecure || '').replace(/\/$/, '');
                    const baseLocal = (this.dataset.urlLocal || '').replace(/\/$/, '');
                    const postUrl = (location.protocol === 'https:' && baseSecure ? baseSecure :
                        baseLocal) + '/' + id;

                    try {
                        const resp = await fetch(postUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': getCsrf(),
                                'Content-Type': 'application/json'
                            }
                        });
                        let newViews = null;
                        try {
                            const data = await resp.json();
                            if (data && typeof data.visualizacoes !== 'undefined') {
                                newViews = parseInt(data.visualizacoes, 10);
                            }
                        } catch (_) {}
                        if (counterEl) {
                            if (newViews !== null && !Number.isNaN(newViews)) counterEl
                                .textContent = newViews;
                            else counterEl.textContent = parseInt(counterEl.textContent || '0',
                                10) + 1;
                        }
                    } catch (err) {
                        console.error('Erro ao registrar visualização:', err);
                        if (counterEl) counterEl.textContent = parseInt(counterEl.textContent ||
                            '0', 10) + 1;
                    }
                });
            });
        });
    </script>
@endsection
