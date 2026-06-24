@extends('layouts.app')

@section('title', 'Portfolio do Atleta')

@php
    $nomePartes = explode(' ', trim((string) ($atleta['nome'] ?? 'Atleta')));
    $primeiroNome = array_shift($nomePartes) ?: ($atleta['nome'] ?? 'Atleta');
    $restanteNome = implode(' ', $nomePartes);
    $altura = $atleta['altura'] ?? '-';
    $peso = $atleta['peso'] ?? '-';
    $posicao = $atleta['posicao'] ?? '-';
    $nacionalidade = $atletaModel->nacionalidade ?: 'Brasileiro';
    $estiloJogo = $atletaModel->estilo_jogo ?: $posicao;
    $dataNascimento = $atletaModel->data_nascimento ? \Carbon\Carbon::parse($atletaModel->data_nascimento)->format('d/m/Y') : '-';
    $fotoPortfolio = route('atletas.og-image', $atletaModel->id);
    $iniciais = function ($nome) {
        return collect(explode(' ', trim((string) $nome)))
            ->filter()
            ->map(fn($parte) => mb_substr($parte, 0, 1))
            ->take(3)
            ->implode('');
    };
@endphp

@section('content')
    <div class="portfolio-shell">
        <div class="portfolio-actions">
            <a href="{{ route('atletas.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
            <a href="{{ route('atletas.show', $atletaModel->id) }}" class="btn btn-outline-primary">
                <i class="bi bi-person-vcard me-1"></i> Ver perfil
            </a>
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Imprimir
            </button>
        </div>

        <article class="portfolio-card">
            <!-- HERO SECTION -->
            <section class="portfolio-hero">
                <div class="portfolio-photo">
                    <img src="{{ $fotoPortfolio }}" alt="Foto de {{ $atleta['nome'] }}">
                </div>

                <div class="portfolio-identity">
                    <h1>{{ $primeiroNome }} <span>{{ $restanteNome }}</span></h1>
                    <div class="portfolio-meta">
                        <span><i class="bi bi-flag-fill"></i> {{ $nacionalidade }}</span>
                        <span><i class="bi bi-rulers"></i> {{ $altura }}</span>
                        <span><i class="bi bi-person-fill"></i> {{ $peso }}</span>
                        <span><i class="bi bi-dribbble"></i> {{ $posicao }}</span>
                    </div>
                    <strong class="portfolio-role">{{ $estiloJogo }}</strong>
                </div>
            </section>

            <!-- ESTATÍSTICAS DA TEMPORADA -->
            <section class="portfolio-section">
                <h2 class="portfolio-section-title">Estatísticas da temporada 2025</h2>
                <div class="portfolio-season-grid">
                    @foreach ($temporadas as $temporada)
                        <article class="portfolio-season">
                            <header>
                                <span class="portfolio-shield" title="{{ $temporada['equipe'] ?? 'Equipe' }}"
                                    data-bs-toggle="tooltip">{{ $iniciais($temporada['equipe'] ?? 'Equipe') }}</span>
                                <div class="portfolio-season-info">
                                    <strong>{{ $temporada['equipe'] ?? 'Equipe' }}</strong>
                                    <small>{{ $temporada['temporada'] ?? '-' }}</small>
                                </div>
                            </header>
                            <div class="portfolio-stats">
                                <div><strong>{{ $temporada['ppg'] ?? '-' }}</strong><span>PPG</span></div>
                                <div><strong>{{ $temporada['rpg'] ?? '-' }}</strong><span>RPG</span></div>
                                <div><strong>{{ $temporada['apg'] ?? '-' }}</strong><span>APG</span></div>
                                <div><strong>{{ $temporada['eff'] ?? '-' }}</strong><span>EFF</span></div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <!-- PERFIL PROFISSIONAL E QUALIDADES -->
            <section class="portfolio-section portfolio-split">
                <article class="portfolio-panel">
                    <h2><i class="bi bi-person-badge-fill"></i> Perfil profissional</h2>
                    <p>{{ $perfilProfissional ?: 'Perfil do atleta não preenchido.' }}</p>
                </article>

                <article class="portfolio-panel">
                    <h2><i class="bi bi-shield-check"></i> Principais qualidades</h2>
                    <ul class="portfolio-list">
                        @forelse ($qualidades as $qualidade)
                            <li><i class="bi bi-check-circle-fill"></i> {{ $qualidade }}</li>
                        @empty
                            <li><i class="bi bi-info-circle"></i> Qualidades não preenchidas</li>
                        @endforelse
                    </ul>
                </article>
            </section>

            <!-- CONQUISTAS POR CLUBE -->
            <section class="portfolio-section">
                <h2 class="portfolio-section-title">Conquistas por clube</h2>
                <div class="portfolio-achievements">
                    @forelse ($conquistas as $conquista)
                        <article>
                            <div class="portfolio-club-head">
                                <span class="portfolio-shield" title="{{ $conquista['equipe'] ?? 'Equipe' }}"
                                    data-bs-toggle="tooltip">{{ $iniciais($conquista['equipe'] ?? 'Equipe') }}</span>
                                <div>
                                    <strong>{{ $conquista['equipe'] ?? 'Equipe' }}</strong>
                                    <small>{{ $conquista['periodo'] ?? '-' }}</small>
                                </div>
                            </div>
                            <ul class="portfolio-list">
                                @forelse (($conquista['itens'] ?? []) as $item)
                                    <li><i class="bi bi-trophy-fill"></i> {{ $item }}</li>
                                @empty
                                    <li><i class="bi bi-info-circle"></i> Sem conquistas registradas</li>
                                @endforelse
                            </ul>
                        </article>
                    @empty
                        <div class="portfolio-empty">
                            <p><i class="bi bi-info-circle"></i> Conquistas não preenchidas</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- HISTÓRICO DE CLUBES -->
            <section class="portfolio-section">
                <h2 class="portfolio-section-title">Histórico de clubes - Ano a ano</h2>
                <div class="portfolio-timeline">
                    @forelse ($historicoClubes as $clube)
                        <article>
                            <span class="portfolio-dot"></span>
                            <strong>{{ $clube['ano'] ?? '-' }}</strong>
                            <span class="portfolio-shield" title="{{ $clube['equipe'] ?? 'Equipe' }}"
                                data-bs-toggle="tooltip">{{ $iniciais($clube['equipe'] ?? 'Equipe') }}</span>
                            <small>{{ $clube['equipe'] ?? 'Equipe' }}</small>
                        </article>
                    @empty
                        <div class="portfolio-empty">
                            <p><i class="bi bi-info-circle"></i> Histórico não preenchido</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- RODAPÉ COM INFORMAÇÕES PESSOAIS, HIGHLIGHTS E CONTATO -->
            <footer class="portfolio-footer">
                <section>
                    <h2><i class="bi bi-person-badge"></i> Informações pessoais</h2>
                    <p><i class="bi bi-calendar-event"></i> <strong>Data de nascimento:</strong> {{ $dataNascimento }}</p>
                    <p><i class="bi bi-rulers"></i> <strong>Altura:</strong> {{ $altura }}</p>
                    <p><i class="bi bi-person-fill"></i> <strong>Peso:</strong> {{ $peso }}</p>
                    <p><i class="bi bi-flag-fill"></i> <strong>Nacionalidade:</strong> {{ $nacionalidade }}</p>
                </section>
                <section>
                    <h2><i class="bi bi-camera-video"></i> Highlights</h2>
                    <p>{{ $highlightsTexto ?: 'Highlights disponíveis sob demanda' }}</p>
                    @if (!empty($atleta['video_original_url']))
                        <a href="{{ $atleta['video_original_url'] }}" target="_blank" rel="noopener noreferrer" class="portfolio-link">
                            <i class="bi bi-play-circle"></i> Abrir vídeo
                        </a>
                    @endif
                </section>
                <section>
                    <h2><i class="bi bi-telephone"></i> Contato</h2>
                    <p><i class="bi bi-telephone-fill"></i> {{ $atletaModel->contato ?: '-' }}</p>
                    <p><i class="bi bi-envelope-fill"></i> {{ $atleta['email'] ?: 'E-mail não informado' }}</p>
                    @if ($instagram)
                        <p><i class="bi bi-instagram"></i> <a href="https://instagram.com/{{ ltrim($instagram, '@') }}" target="_blank" rel="noopener noreferrer" class="portfolio-link">{{ $instagram }}</a></p>
                    @endif
                </section>
            </footer>
        </article>
    </div>
@endsection

@push('styles')
    <style>
        .portfolio-shell {
            max-width: 1040px;
            margin: 0 auto 1rem;
        }

        .portfolio-actions {
            display: flex;
            justify-content: space-between;
            gap: 0.6rem;
            margin-bottom: 0.65rem;
        }

        .portfolio-card {
            overflow: hidden;
            border-radius: 10px;
            background: #f3f6fb;
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.2);
        }

        /* HERO SECTION */
        .portfolio-hero {
            display: grid;
            grid-template-columns: 38% 62%;
            min-height: 350px;
            color: #fff;
            background: #07111f;
        }

        .portfolio-photo {
            background: #111b2b;
            min-height: 350px;
        }

        .portfolio-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            display: block;
        }

        .portfolio-identity {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, #07111f 0%, #101b31 62%, #164ea1 100%);
        }

        .portfolio-identity h1 {
            margin: 0;
            font-size: clamp(2.3rem, 5vw, 5rem);
            line-height: 0.98;
            font-weight: 900;
            text-transform: uppercase;
        }

        .portfolio-identity h1 span {
            display: block;
            color: #237bdc;
        }

        .portfolio-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.18);
        }

        .portfolio-meta span {
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            font-weight: 800;
            font-size: 0.9rem;
        }

        .portfolio-role {
            margin-top: 0.85rem;
            color: #58a1ff;
            font-size: 1.25rem;
            text-transform: uppercase;
        }

        /* SECTIONS */
        .portfolio-section {
            padding: 1rem 1.15rem;
            background: #fff;
            border-top: 4px solid #dce5f2;
        }

        .portfolio-section-title {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin: 0 0 0.85rem;
            color: #1f2d4f;
            font-size: 1rem;
            font-weight: 900;
            text-align: center;
            text-transform: uppercase;
        }

        .portfolio-section-title::before,
        .portfolio-section-title::after {
            content: "";
            height: 2px;
            flex: 1;
            background: #28365f;
            opacity: 0.32;
        }

        /* TEMPORADAS */
        .portfolio-season-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .portfolio-season {
            background: #07111f;
            color: #fff;
            border: 1px solid #dbe1ec;
            border-radius: 6px;
            overflow: hidden;
        }

        .portfolio-season header {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.7rem;
            background: #1f66b7;
            text-transform: uppercase;
        }

        .portfolio-season-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .portfolio-season-info strong {
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .portfolio-season-info small {
            font-size: 0.75rem;
            color: #b8d4f1;
        }

        .portfolio-shield {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.5);
            background: linear-gradient(135deg, #f8fbff, #c9d8ee);
            color: #1f2d4f;
            font-weight: 900;
            font-size: 0.82rem;
        }

        .portfolio-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            text-align: center;
        }

        .portfolio-stats div {
            padding: 0.9rem 0.35rem;
            border-left: 1px solid rgba(255, 255, 255, 0.18);
        }

        .portfolio-stats div:first-child {
            border-left: none;
        }

        .portfolio-stats strong {
            display: block;
            color: #fff;
            font-size: 1.45rem;
            line-height: 1;
        }

        .portfolio-stats span {
            color: #d7e5f7;
            font-size: 0.72rem;
            font-weight: 900;
        }

        /* SPLIT PANELS */
        .portfolio-split {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 0.9rem;
        }

        .portfolio-panel {
            padding: 0.9rem;
            border: 1px solid #dbe1ec;
            border-radius: 8px;
            background: #f8fbff;
        }

        .portfolio-panel h2 {
            margin: 0 0 0.6rem;
            color: #1f2d4f;
            font-size: 1rem;
            font-weight: 900;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .portfolio-panel p {
            margin: 0;
            color: #25365d;
            line-height: 1.55;
            font-size: 0.95rem;
        }

        .portfolio-list {
            display: grid;
            gap: 0.42rem;
            margin: 0;
            padding: 0;
            list-style: none;
            color: #25365d;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .portfolio-list i {
            color: #1f66b7;
        }

        /* CONQUISTAS */
        .portfolio-achievements {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .portfolio-achievements article {
            padding-right: 0.75rem;
            border-right: 1px solid #cdd8e8;
        }

        .portfolio-achievements article:last-child {
            border-right: none;
        }

        .portfolio-club-head {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 0.65rem;
            color: #1f2d4f;
        }

        .portfolio-club-head strong,
        .portfolio-club-head small {
            display: block;
        }

        .portfolio-club-head strong {
            font-weight: 900;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .portfolio-club-head small {
            color: #1f66b7;
            font-weight: 900;
            font-size: 0.75rem;
        }

        /* TIMELINE */
        .portfolio-timeline {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(94px, 1fr));
            gap: 0.55rem;
        }

        .portfolio-timeline article {
            position: relative;
            text-align: center;
            color: #1f2d4f;
            padding-top: 1.25rem;
        }

        .portfolio-timeline article::before {
            content: "";
            position: absolute;
            top: 0.38rem;
            left: 0;
            right: 0;
            height: 2px;
            background: #28365f;
        }

        .portfolio-dot {
            position: relative;
            z-index: 1;
            display: block;
            width: 10px;
            height: 10px;
            margin: 0 auto 0.38rem;
            border-radius: 50%;
            background: #1f66b7;
        }

        .portfolio-timeline strong,
        .portfolio-timeline small {
            display: block;
        }

        .portfolio-timeline strong {
            font-weight: 900;
            font-size: 0.85rem;
        }

        .portfolio-timeline small {
            margin-top: 0.35rem;
            font-weight: 900;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        /* FOOTER */
        .portfolio-footer {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.8rem;
            padding: 1rem 1.15rem;
            background: #07111f;
            color: #fff;
        }

        .portfolio-footer section {
            border-right: 1px solid rgba(255, 255, 255, 0.18);
            padding-right: 0.8rem;
        }

        .portfolio-footer section:last-child {
            border-right: none;
        }

        .portfolio-footer h2 {
            margin: 0 0 0.55rem;
            color: #237bdc;
            font-size: 0.95rem;
            font-weight: 900;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .portfolio-footer p {
            margin: 0.25rem 0;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .portfolio-footer p i {
            margin-right: 0.3rem;
            color: #237bdc;
        }

        .portfolio-link {
            color: #58a1ff;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: color 0.2s;
        }

        .portfolio-link:hover {
            color: #7bb8ff;
        }

        .portfolio-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 1rem;
            color: #7a8ba8;
        }

        .portfolio-empty p {
            margin: 0;
            font-size: 0.95rem;
        }

        /* RESPONSIVO - TABLET */
        @media (max-width: 991.98px) {
            .portfolio-hero {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .portfolio-photo {
                min-height: 300px;
            }

            .portfolio-season-grid {
                grid-template-columns: 1fr;
            }

            .portfolio-split {
                grid-template-columns: 1fr;
            }

            .portfolio-achievements {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .portfolio-achievements article,
            .portfolio-footer section {
                border-right: none;
                border-bottom: 1px solid #dbe1ec;
                padding-bottom: 0.75rem;
                padding-right: 0;
            }

            .portfolio-footer section {
                border-bottom-color: rgba(255, 255, 255, 0.18);
            }

            .portfolio-footer {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        /* RESPONSIVO - MOBILE */
        @media (max-width: 575.98px) {
            .portfolio-actions {
                flex-direction: column;
            }

            .portfolio-actions .btn {
                width: 100%;
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }

            .portfolio-photo {
                min-height: 230px;
            }

            .portfolio-identity {
                padding: 1.2rem;
            }

            .portfolio-identity h1 {
                font-size: clamp(1.8rem, 4vw, 2.5rem);
            }

            .portfolio-meta {
                gap: 0.45rem 0.8rem;
                font-size: 0.8rem;
            }

            .portfolio-meta span {
                font-size: 0.8rem;
            }

            .portfolio-role {
                font-size: 1rem;
            }

            .portfolio-section,
            .portfolio-footer {
                padding: 0.85rem;
            }

            .portfolio-section-title {
                font-size: 0.9rem;
                gap: 0.5rem;
            }

            .portfolio-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .portfolio-stats div {
                padding: 0.7rem 0.25rem;
            }

            .portfolio-stats strong {
                font-size: 1.2rem;
            }

            .portfolio-stats span {
                font-size: 0.65rem;
            }

            .portfolio-season header {
                padding: 0.6rem;
            }

            .portfolio-shield {
                width: 44px;
                height: 44px;
                font-size: 0.75rem;
            }

            .portfolio-panel h2,
            .portfolio-footer h2 {
                font-size: 0.9rem;
            }

            .portfolio-panel p,
            .portfolio-list,
            .portfolio-footer p {
                font-size: 0.9rem;
            }

            .portfolio-achievements {
                grid-template-columns: 1fr;
            }

            .portfolio-achievements article {
                border-right: none;
                border-bottom: 1px solid #dbe1ec;
                padding-right: 0;
                padding-bottom: 0.75rem;
            }

            .portfolio-timeline {
                grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
                gap: 0.4rem;
            }

            .portfolio-timeline article {
                padding-top: 1rem;
            }

            .portfolio-timeline article::before {
                top: 0.3rem;
            }

            .portfolio-dot {
                width: 8px;
                height: 8px;
            }

            .portfolio-timeline strong {
                font-size: 0.8rem;
            }

            .portfolio-timeline small {
                font-size: 0.7rem;
            }

            .portfolio-footer {
                grid-template-columns: 1fr;
                gap: 0.8rem;
            }

            .portfolio-footer section {
                border-bottom: 1px solid rgba(255, 255, 255, 0.18);
                padding-bottom: 0.75rem;
            }

            .portfolio-footer section:last-child {
                border-bottom: none;
            }
        }

        /* PRINT */
        @media print {
            .portfolio-actions {
                display: none;
            }

            .portfolio-shell {
                margin: 0;
            }

            .portfolio-card {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips do Bootstrap
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
                if (window.bootstrap && window.bootstrap.Tooltip) {
                    new window.bootstrap.Tooltip(el);
                }
            });
        });
    </script>
@endpush
