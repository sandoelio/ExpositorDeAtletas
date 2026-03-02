@extends('layouts.app')

@section('content')
    <style>
        .basquete-img {
            width: 50%;
            max-width: 400px;
            height: auto;
            display: block;
            margin: 5px auto;
        }

        .btn-custom {
            display: inline-block;
            background: #FF7209;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 6px 7px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
            border: 0;
        }

        .btn-custom:hover {
            background: #e66000;
            color: #fff;
            transform: scale(1.05);
        }

        .btn-olheiro {
            display: inline-block;
            background: linear-gradient(90deg, #0d6efd, #0a58ca);
            color: #fff;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 10px;
            border: 0;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.25);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .btn-olheiro:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.35);
        }

        .home-title {
            color: #fff;
            font-size: 1.9rem;
            font-weight: 700;
            margin: 4px 0 10px;
        }

        .home-actions {
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .stats-toggle {
            border-color: rgba(255, 255, 255, 0.35);
            color: #fff;
        }

        .stats-toggle:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.08);
        }

        .grafico-ranking-wrapper {
            width: 100%;
            max-width: 400px;
            height: 240px;
        }

        .relatorio-estatistico .list-group-item {
            font-size: 0.95rem;
            padding: 6px 15px;
            border: none;
            border-bottom: 4px solid #ddd;
            background: #f9f9f9;
        }

        .badge-destaque {
            background-color: #FF7209;
            color: #fff;
            padding: 2px 4px;
            border-radius: 4px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .basquete-img {
                width: 50%;
                max-width: 500px;
            }

            .btn-custom,
            .btn-olheiro {
                width: 100%;
                max-width: 320px;
                padding: 8px 10px;
                font-size: 1rem;
            }

            .home-title {
                font-size: 1.55rem;
                margin-bottom: 12px;
            }

            .home-actions {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .grafico-ranking-wrapper {
                max-width: 80%;
                height: 180px;
            }

            #rankingChart {
                height: 100% !important;
            }

            .relatorio-estatistico .row {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .relatorio-estatistico .col-12 {
                width: 100%;
                max-width: 360px;
            }

            #stats-content {
                display: none;
            }

            #stats-content.is-open {
                display: block;
            }
        }
    </style>

    <div class="d-flex justify-content-center align-items-center min-vh-90 text-center">
        <div>
            <img src="{{ asset('img/LOGO1.png') }}" alt="Logo" class="basquete-img">

            <div class="mt-1 home-actions">
                <a href="{{ route('atletas.index') }}" class="btn-custom">Atletas</a>
                <a href="{{ route('admin.login') }}" class="btn-custom">Administração</a>

                @if (Auth::guard('olheiro')->check())
                    <a href="{{ route('olheiro.atletas.index') }}" class="btn-olheiro">Técnico / Olheiro</a>
                @else
                    <a href="{{ route('olheiro.login.form') }}" class="btn-olheiro">Técnico / Olheiro</a>
                @endif
            </div>

            <div class="mt-3">
                <h5 class="text-center mb-1">Ranking dos mais visualizados</h5>
                <div class="mt-2 d-flex justify-content-center">
                    <div class="grafico-ranking-wrapper">
                        <canvas id="rankingChart"></canvas>
                    </div>
                </div>
            </div>

            @php
                $valores = collect([
                    $estatisticas['categoria12'],
                    $estatisticas['categoria14'],
                    $estatisticas['categoria16'],
                    $estatisticas['categoria18'],
                    $estatisticas['categoria21'],
                    $estatisticas['categoria22_29'],
                    $estatisticas['categoria30_39'],
                    $estatisticas['categoria'],
                ]);
                $maior = $valores->max();
                $sexoMaior = max($estatisticas['masculino'], $estatisticas['feminino']);
            @endphp

            <div class="mt-2">
                <h5 class="text-center mb-2">Relatório estatístico dos cadastrados</h5>
                <button type="button" id="toggle-stats" class="btn btn-sm stats-toggle d-md-none mb-2">
                    Mostrar estatísticas
                </button>
                <div id="stats-content" class="relatorio-estatistico mx-auto" style="max-width: 600px;">
                    <div class="mb-3 row justify-content-center">
                        <div class="col-12 col-md-6 text-center text-md-start">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Masculino</span>
                                    <strong class="{{ $estatisticas['masculino'] == $sexoMaior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['masculino'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Feminino</span>
                                    <strong class="{{ $estatisticas['feminino'] == $sexoMaior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['feminino'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Categoria (<= 12)</span>
                                    <strong class="{{ $estatisticas['categoria12'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria12'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Categoria (13-14)</span>
                                    <strong class="{{ $estatisticas['categoria14'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria14'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Categoria (15-16)</span>
                                    <strong class="{{ $estatisticas['categoria16'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria16'] }}
                                    </strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 text-center text-md-start">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Categoria (17-18)</span>
                                    <strong class="{{ $estatisticas['categoria18'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria18'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Categoria (19-21)</span>
                                    <strong class="{{ $estatisticas['categoria21'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria21'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Categoria (22-29)</span>
                                    <strong class="{{ $estatisticas['categoria22_29'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria22_29'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Categoria (30-39)</span>
                                    <strong class="{{ $estatisticas['categoria30_39'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria30_39'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Categoria (>= 40)</span>
                                    <strong class="{{ $estatisticas['categoria'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria'] }}
                                    </strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $topAtletasHome = $topAtletas->take(5);
        $nomes = $topAtletasHome->map(function ($a) {
            return explode(' ', trim($a->nome_completo))[0];
        });
        $visualizacoes = $topAtletasHome->pluck('visualizacoes');
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnToggleStats = document.getElementById('toggle-stats');
            const statsContent = document.getElementById('stats-content');
            if (btnToggleStats && statsContent) {
                btnToggleStats.addEventListener('click', () => {
                    const opened = statsContent.classList.toggle('is-open');
                    btnToggleStats.textContent = opened ? 'Ocultar estatísticas' : 'Mostrar estatísticas';
                });
            }

            const chartEl = document.getElementById('rankingChart');
            if (!chartEl || typeof Chart === 'undefined' || typeof ChartDataLabels === 'undefined') {
                return;
            }

            const ctx = chartEl.getContext('2d');
            const nomes = @json($nomes);
            const visualizacoes = @json($visualizacoes);

            const medalhaCores = visualizacoes.map((_, i) => {
                if (i === 0) return '#FFD700';
                if (i === 1) return '#C0C0C0';
                if (i === 2) return '#CD7F32';
                return '#FF7209';
            });

            Chart.register(ChartDataLabels);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: nomes,
                    datasets: [{
                        label: 'Visualizacoes',
                        data: visualizacoes,
                        backgroundColor: medalhaCores,
                        borderRadius: 6,
                        barThickness: 20,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'right',
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12,
                            },
                            formatter: value => `${value}`,
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: '#fff',
                                precision: 0,
                            },
                            grid: {
                                color: 'rgba(255,255,255,0.1)',
                            },
                            afterDataLimits(scale) {
                                scale.max += scale.max * 0.15;
                            },
                        },
                        y: {
                            ticks: {
                                color: '#fff',
                                font: {
                                    size: 12,
                                },
                            },
                            grid: {
                                display: false,
                            },
                        },
                    },
                },
            });
        });
    </script>
@endsection
