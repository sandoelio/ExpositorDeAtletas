@extends('layouts.app')

@section('content')
    <style>
        /* Logo responsiva fluida */
        .basquete-img {
            width: 50%;
            max-width: 400px;
            height: auto;
            display: block;
            margin: 5px auto;
        }

        /* Botões estilizados */
        .btn-custom {
            display: inline-block;
            background: #FF7209;
            color: white;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 6px 7px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: #e66000;
            transform: scale(1.05);
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
            color: white;
            padding: 2px 2px;
            border-radius: 4px;
            font-weight: bold;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .basquete-img {
                width: 50%;
                /* ocupa quase toda a tela */
                max-width: 500px;
                /* pode crescer mais no mobile */
            }

            .btn-custom {
                width: 40%;
                /* ocupa largura total */
                padding: 6px;
                /* aumenta área de clique */
                font-size: 1.2rem;
                /* texto maior */
            }

            .gap-3 {
                gap: 15px !important;
                /* mais espaço entre botões */
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
                /* centraliza os cards */
                gap: 10px;
            }

            .relatorio-estatistico .col-12 {
                width: 100%;
                max-width: 360px;
                /* opcional: limita largura dos cards */
            }
        }
    </style>

    <div class="d-flex justify-content-center align-items-center min-vh-90 text-center">
        <div>
            <h6 class="mt-1">Descubra talentos, inspire-se e conecte-se com o futuro do esporte!</h6>
            <!-- Logo responsiva -->
            <img src="{{ asset('img/LOGO1.png') }}" alt="Logo" class="basquete-img">

            <div class="mt-1 d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('atletas.index') }}" class="btn-custom">Listar Atletas</a>
                <a href="{{ route('admin.login') }}" class="btn-custom">Administração</a>
            </div>
            {{-- gráfico de ranking --}}
            <div class="mt-3">
                <h5 class="text-center mb-1">🏆 Ranking dos Mais Visualizados</h5>
                <div class="mt-2 d-flex justify-content-center">
                    <div class="grafico-ranking-wrapper">
                        <canvas id="rankingChart"></canvas>
                    </div>
                </div>
            </div>
            {{-- estatístico dos cadastrados --}}
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
                <h5 class="text-center mb-2">📊 Relatório estatístico dos cadastrados</h5>
                <div class="relatorio-estatistico mx-auto" style="max-width: 600px;">
                    <div class="mb-3 row justify-content-center">
                        <div class="col-12 col-md-6 text-center text-md-start">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>👨 Masculino</span>
                                    <strong class="{{ $estatisticas['masculino'] == $sexoMaior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['masculino'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>👩 Feminino</span>
                                    <strong class="{{ $estatisticas['feminino'] == $sexoMaior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['feminino'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>🏀 Categoria (≤ 12)</span>
                                    <strong class="{{ $estatisticas['categoria12'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria12'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>🏀 Categoria (13–14)</span>
                                    <strong class="{{ $estatisticas['categoria14'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria14'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>🏀 Categoria (15–16)</span>
                                    <strong class="{{ $estatisticas['categoria16'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria16'] }}
                                    </strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 text-center text-md-start">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>🏀 Categoria (17–18)</span>
                                    <strong class="{{ $estatisticas['categoria18'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria18'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>🏀 Categoria (19–21)</span>
                                    <strong class="{{ $estatisticas['categoria21'] == $maior ? 'badge-destaque' : '' }}">
                                        {{ $estatisticas['categoria21'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>🏀 Categoria (22–29)</span>
                                    <strong
                                        class="{{ $estatisticas['categoria22_29'] == $maior ? 'badge-destaque' : '' }}">{{ $estatisticas['categoria22_29'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>🏀 Categoria (30–39)</span>
                                    <strong
                                        class="{{ $estatisticas['categoria30_39'] == $maior ? 'badge-destaque' : '' }}">{{ $estatisticas['categoria30_39'] }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>🏀 Categoria (≥ 40)</span>
                                    <strong
                                        class="{{ $estatisticas['categoria'] == $maior ? 'badge-destaque' : '' }}">{{ $estatisticas['categoria'] }}
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
        $nomes = $topAtletas->map(function ($a) {
            return explode(' ', trim($a->nome_completo))[0];
        });
        $visualizacoes = $topAtletas->pluck('visualizacoes');
    @endphp
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('rankingChart').getContext('2d');

        const nomes = @json($nomes); // primeiros nomes
        const visualizacoes = @json($visualizacoes);

        const medalhaCores = visualizacoes.map((_, i) => {
            if (i === 0) return '#FFD700'; // ouro
            if (i === 1) return '#C0C0C0'; // prata
            if (i === 2) return '#CD7F32'; // bronze
            return '#FF7209'; // padrão
        });

        // Registra o plugin
        Chart.register(ChartDataLabels);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: nomes,
                datasets: [{
                    label: 'Visualizações',
                    data: visualizacoes,
                    backgroundColor: medalhaCores,
                    borderRadius: 6,
                    barThickness: 20
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'right',
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: value => `👁️ ${value}`
                    },
                    tooltip: {
                        enabled: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            color: '#fff',
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        },
                        // margem automática para não cortar o valor
                        afterDataLimits(scale) {
                            scale.max += scale.max * 0.15; // adiciona 15% de espaço para o número
                        }
                    },
                    y: {
                        ticks: {
                            color: '#fff',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
