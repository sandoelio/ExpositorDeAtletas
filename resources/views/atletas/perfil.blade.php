@extends('layouts.app')

@section('title', ($atleta['nome'] ?? 'Perfil do atleta') . ' | Vitrine de Atletas')

@php
    $ogTitle = ($atleta['nome'] ?? 'Atleta') . ' | Vitrine de Atletas';
    $ogResumoBase = trim(
        implode(
            ' | ',
            array_filter([
                $atleta['posicao'] ?? null,
                $atleta['entidade'] ?? null,
                $atleta['cidade'] ?? null,
            ]),
        ),
    );
    $ogBio = trim((string) ($atleta['bio'] ?? ''));
    $ogDescription = \Illuminate\Support\Str::limit(trim($ogResumoBase . '. ' . $ogBio), 180, '...');
    $ogUrl = route('atletas.perfil', $atleta['id'] ?? 0);
    $ogImage = route('atletas.og-image', $atleta['id'] ?? 0);
@endphp

@push('meta')
    <meta name="description" content="{{ $ogDescription }}">
    <link rel="canonical" href="{{ $ogUrl }}">

    <meta property="og:type" content="profile">
    <meta property="og:site_name" content="Vitrine de Atletas">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ $ogUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Perfil de {{ $atleta['nome'] ?? 'atleta' }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
@endpush

@section('content')
    <div class="demo-profile-page">
        <div class="demo-shape demo-shape-a"></div>
        <div class="demo-shape demo-shape-b"></div>

        <section class="demo-hero">
            <div class="demo-hero-main">
                <span class="demo-kicker">Perfil do atleta</span>
                <h1>{{ $atleta['nome'] }}</h1>
                <p class="demo-role">{{ $atleta['posicao'] }} | {{ $atleta['entidade'] }}</p>

                <div class="demo-tags">
                    <span>Rank #{{ is_numeric($atleta['rank'] ?? null) ? $atleta['rank'] : '-' }}</span>
                    <span>{{ number_format((int) ($atleta['visualizacoes'] ?? 0), 0, ',', '.') }} visualizacoes</span>
                    <span>{{ is_numeric($atleta['idade']) ? $atleta['idade'] . ' anos' : $atleta['idade'] }}</span>
                    <span>{{ $atleta['altura'] }}</span>
                    <span>{{ $atleta['peso'] }}</span>
                    <span>{{ $atleta['cidade'] }}</span>
                </div>

                <p class="demo-summary">{{ $atleta['bio'] }}</p>

                <div class="demo-actions">
                    <a href="{{ route('atletas.index') }}" class="btn-demo btn-demo-primary">Voltar para atletas</a>
                    <button id="copy-demo-link" class="btn-demo btn-demo-light" type="button"
                        data-share-url="{{ $ogUrl }}">Compartilhar perfil</button>
                </div>
                <small id="copy-feedback" class="demo-feedback" aria-live="polite"></small>
            </div>

            <div class="demo-hero-side">
                <div class="demo-photo-card">
                    <span class="demo-status">Disponivel para avaliacao</span>
                    <img src="{{ $atleta['foto_url'] }}" alt="Foto de {{ $atleta['nome'] }}">
                </div>
            </div>
        </section>

        <section class="demo-stats-grid">
            @foreach ($stats as $item)
                <article class="demo-stat-card">
                    <small>{{ $item['label'] }}</small>
                    <strong>{{ $item['valor'] }}</strong>
                </article>
            @endforeach
        </section>

        <section class="demo-content-grid">
            <article class="demo-video-card">
                <header>
                    <h2>Video de destaque</h2>
                </header>

                <div class="demo-video-placeholder">
                    @if (($atleta['video_tipo'] ?? null) === 'iframe' && !empty($atleta['video_embed_url']))
                        <iframe src="{{ $atleta['video_embed_url'] }}" title="Video destaque de {{ $atleta['nome'] }}"
                            loading="lazy" allowfullscreen></iframe>
                    @elseif(($atleta['video_tipo'] ?? null) === 'file' && !empty($atleta['video_original_url']))
                        <video controls preload="metadata">
                            <source src="{{ $atleta['video_original_url'] }}">
                            Seu navegador nao suporta video HTML5.
                        </video>
                    @elseif(!empty($atleta['video_original_url']))
                        <i class="bi bi-link-45deg"></i>
                        <p>Link de video disponivel</p>
                        <a href="{{ $atleta['video_original_url'] }}" target="_blank" rel="noopener noreferrer"
                            class="btn-demo btn-demo-light">Abrir video</a>
                    @else
                        <i class="bi bi-play-circle-fill"></i>
                        <p>Nenhum video cadastrado no campo resumo</p>
                    @endif
                </div>
            </article>

            <aside class="demo-info-card">
                <h2>Destaques recentes</h2>
                <ul>
                    @foreach ($destaques as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>

                <div class="demo-contact-box">
                    <h3>Contato rapido</h3>
                    <p>Acoes com base no contato cadastrado do atleta.</p>
                    <div class="demo-contact-actions">
                        @if (!empty($atleta['whatsapp_url']))
                            <a href="{{ $atleta['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer"
                                class="btn-demo btn-demo-primary">Chamar no WhatsApp</a>
                        @else
                            <button type="button" class="btn-demo btn-demo-primary" disabled>WhatsApp indisponivel</button>
                        @endif
                        @if (!empty($atleta['email_url']))
                            <a href="{{ $atleta['email_url'] }}" class="btn-demo btn-demo-light">Enviar e-mail</a>
                        @else
                            <button type="button" class="btn-demo btn-demo-light" disabled>E-mail indisponivel</button>
                        @endif
                    </div>
                </div>
            </aside>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;600;700;800&display=swap');

        .demo-profile-page {
            --ink: #0f2740;
            --ink-soft: #2e4761;
            --accent: #ff6a13;
            --accent-soft: #ffd7be;
            --surface: #fff9f3;
            --white: #ffffff;
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            padding: 1.1rem;
            margin-bottom: 0.85rem;
            background:
                radial-gradient(1200px 500px at -10% -20%, #ffe6d4 0%, transparent 60%),
                radial-gradient(800px 380px at 100% 0%, #ffd8ba 0%, transparent 58%),
                linear-gradient(170deg, #fffaf6 0%, #fff4ea 100%);
        }

        .demo-shape {
            position: absolute;
            pointer-events: none;
            border-radius: 999px;
            z-index: 0;
        }

        .demo-shape-a {
            width: 220px;
            height: 220px;
            right: -80px;
            bottom: -90px;
            background: rgba(255, 106, 19, 0.2);
        }

        .demo-shape-b {
            width: 150px;
            height: 150px;
            left: -70px;
            top: 30%;
            background: rgba(15, 39, 64, 0.1);
        }

        .demo-hero,
        .demo-stats-grid,
        .demo-content-grid {
            position: relative;
            z-index: 1;
        }

        .demo-hero {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 1rem;
            align-items: stretch;
        }

        .demo-hero-main,
        .demo-hero-side {
            border: 1px solid rgba(15, 39, 64, 0.08);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.84);
            backdrop-filter: blur(4px);
            box-shadow: 0 8px 24px rgba(15, 39, 64, 0.08);
        }

        .demo-hero-main {
            padding: 1rem 1.1rem;
        }

        .demo-kicker {
            display: inline-block;
            font-family: 'Manrope', sans-serif;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--ink-soft);
            background: var(--accent-soft);
            border-radius: 999px;
            padding: 0.32rem 0.62rem;
        }

        .demo-hero-main h1 {
            margin: 0.45rem 0 0.1rem;
            font-family: 'Bebas Neue', sans-serif;
            color: var(--ink);
            font-size: clamp(2.2rem, 5vw, 3rem);
            letter-spacing: 0.02em;
            line-height: 1;
        }

        .demo-role {
            margin: 0;
            color: var(--ink-soft);
            font-family: 'Manrope', sans-serif;
            font-weight: 700;
        }

        .demo-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 0.72rem;
        }

        .demo-tags span {
            font-family: 'Manrope', sans-serif;
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--ink);
            background: #eef4fa;
            padding: 0.34rem 0.56rem;
            border-radius: 999px;
        }

        .demo-summary {
            margin: 0.75rem 0 0.85rem;
            font-family: 'Manrope', sans-serif;
            color: #22384d;
            line-height: 1.5;
            max-width: 60ch;
        }

        .demo-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
        }

        .btn-demo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid transparent;
            padding: 0.5rem 0.8rem;
            font-family: 'Manrope', sans-serif;
            font-size: 0.86rem;
            font-weight: 800;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .btn-demo:hover {
            transform: translateY(-1px);
        }

        .btn-demo[disabled] {
            opacity: 0.55;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .btn-demo-primary {
            background: var(--accent);
            color: var(--white);
            box-shadow: 0 8px 16px rgba(255, 106, 19, 0.28);
        }

        .btn-demo-primary:hover {
            color: var(--white);
            background: #e85c0b;
        }

        .btn-demo-light {
            color: var(--ink);
            background: var(--white);
            border-color: rgba(15, 39, 64, 0.18);
        }

        .btn-demo-light:hover {
            color: var(--ink);
            background: #f8fbff;
        }

        .demo-feedback {
            display: block;
            min-height: 18px;
            margin-top: 0.4rem;
            font-family: 'Manrope', sans-serif;
            color: #1b5e20;
            font-weight: 700;
        }

        .demo-hero-side {
            padding: 0.8rem;
        }

        .demo-photo-card {
            height: 100%;
            border-radius: 12px;
            background: linear-gradient(170deg, #102844 0%, #234969 70%, #1a3552 100%);
            padding: 0.7rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .demo-status {
            align-self: flex-end;
            background: #4caf50;
            color: #fff;
            border-radius: 999px;
            padding: 0.24rem 0.52rem;
            font-family: 'Manrope', sans-serif;
            font-size: 0.74rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .demo-photo-card img {
            width: 100%;
            max-height: 290px;
            object-fit: cover;
            border-radius: 10px;
            background: #fff;
        }

        .demo-stats-grid {
            margin-top: 0.9rem;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .demo-stat-card {
            border: 1px solid rgba(15, 39, 64, 0.1);
            border-radius: 12px;
            padding: 0.65rem;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 20px rgba(15, 39, 64, 0.06);
        }

        .demo-stat-card small {
            display: block;
            font-family: 'Manrope', sans-serif;
            color: #45617d;
            font-weight: 700;
            font-size: 0.88rem;
        }

        .demo-stat-card strong {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.02em;
            color: var(--ink);
            font-size: clamp(1.45rem, 2.5vw, 1.9rem);
            line-height: 1.05;
        }

        .demo-content-grid {
            margin-top: 0.65rem;
            display: grid;
            grid-template-columns: 1.45fr 1fr;
            gap: 0.65rem;
        }

        .demo-video-card,
        .demo-info-card {
            border: 1px solid rgba(15, 39, 64, 0.1);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 20px rgba(15, 39, 64, 0.06);
            padding: 0.65rem;
        }

        .demo-video-card header h2,
        .demo-info-card h2 {
            margin: 0;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.03em;
            color: var(--ink);
            font-size: 1.4rem;
        }

        .demo-video-placeholder {
            margin-top: 0.45rem;
            min-height: 185px;
            border-radius: 12px;
            border: 1px dashed rgba(255, 106, 19, 0.45);
            background: linear-gradient(140deg, #ffeddc 0%, #fff7ef 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0.7rem;
        }

        .demo-video-placeholder iframe,
        .demo-video-placeholder video {
            width: 100%;
            max-width: 100%;
            min-height: 210px;
            border: 0;
            border-radius: 10px;
            background: #000;
        }

        .demo-video-placeholder i {
            font-size: 2.2rem;
            color: var(--accent);
            margin-bottom: 0.4rem;
        }

        .demo-video-placeholder p {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            font-weight: 700;
            color: #2f4961;
        }

        .demo-info-card ul {
            margin: 0.45rem 0 0.55rem;
            padding-left: 1rem;
            font-family: 'Manrope', sans-serif;
            color: #243b53;
        }

        .demo-info-card li {
            margin-bottom: 0.25rem;
            font-weight: 700;
        }

        .demo-contact-box {
            border-radius: 10px;
            background: #f4f9ff;
            border: 1px solid rgba(15, 39, 64, 0.1);
            padding: 0.58rem;
        }

        .demo-contact-box h3 {
            margin: 0 0 0.2rem;
            font-family: 'Manrope', sans-serif;
            font-size: 1rem;
            color: var(--ink);
            font-weight: 800;
        }

        .demo-contact-box p {
            margin: 0 0 0.45rem;
            font-family: 'Manrope', sans-serif;
            color: #46617b;
            font-size: 0.83rem;
            font-weight: 600;
        }

        .demo-contact-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.32rem;
        }

        @media (max-width: 991.98px) {
            .demo-hero,
            .demo-content-grid {
                grid-template-columns: 1fr;
            }

            .demo-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .demo-photo-card img {
                max-height: 340px;
            }
        }

        @media (max-width: 575.98px) {
            .demo-profile-page {
                padding: 0.7rem;
                padding-bottom: 1rem;
                margin-bottom: 1.35rem;
            }

            .demo-hero-main,
            .demo-hero-side,
            .demo-video-card,
            .demo-info-card {
                padding: 0.7rem;
            }

            .demo-stats-grid {
                grid-template-columns: 1fr;
            }

            .btn-demo {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shareBtn = document.getElementById('copy-demo-link');
            const feedback = document.getElementById('copy-feedback');
            if (!shareBtn || !feedback) return;

            shareBtn.addEventListener('click', async function() {
                const url = shareBtn.dataset.shareUrl || window.location.href;
                let copied = false;

                if (navigator.clipboard && window.isSecureContext) {
                    try {
                        await navigator.clipboard.writeText(url);
                        copied = true;
                    } catch (error) {
                        copied = false;
                    }
                }

                if (!copied) {
                    const input = document.createElement('input');
                    input.value = url;
                    document.body.appendChild(input);
                    input.select();
                    copied = document.execCommand('copy');
                    document.body.removeChild(input);
                }

                feedback.textContent = copied ? 'Link copiado com sucesso.' : 'Nao foi possivel copiar o link.';
                setTimeout(function() {
                    feedback.textContent = '';
                }, 2200);
            });
        });
    </script>
@endpush
