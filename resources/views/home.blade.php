@extends('layouts.app')

@section('content')
    <style>
        /* Logo responsiva fluida */
        .basquete-img {
            width: 80%;
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
            padding: 12px 25px;
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

        /* Responsividade */
        @media (max-width: 768px) {
            .basquete-img {
                width: 90%;
                /* ocupa quase toda a tela */
                max-width: 500px;
                /* pode crescer mais no mobile */
            }

            .btn-custom {
                width: 90%;
                /* ocupa largura total */
                padding: 15px;
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
        }
    </style>

    <div class="d-flex justify-content-center align-items-center min-vh-90 text-center">
        <div>
            <h2 class="my-1 mb-2">Bem-vindo a vitrine dos atletas</h2>
            <h6 class="mt-1">Descubra talentos, inspire-se e conecte-se com o futuro do esporte!</h6>
            <!-- Logo responsiva -->
            <img src="{{ asset('img/LOGO1.png') }}" alt="Logo" class="basquete-img">

            <div class="mt-1 d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('atletas.index') }}" class="btn-custom">Listar Atletas</a>
                <a href="{{ route('admin.login') }}" class="btn-custom">Administração</a>
            </div>
            <div class="mt-3">
                <h5 class="text-center mb-1">🏆 Ranking dos Mais Visualizados</h5>
                <div class="mt-2 d-flex justify-content-center">
                    <div class="grafico-ranking-wrapper">
                        <canvas id="rankingChart"></canvas>
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
                        max: 7000,
                        ticks: {
                            color: '#fff',
                            precision: 0
                        }, 
                        grid: {
                            color: 'rgba(255,255,255,0.1)' // linhas discretas
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
