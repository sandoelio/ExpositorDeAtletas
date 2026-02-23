@extends('layouts.app')

@section('content')
    <div class="admin-dash-wrap">
        <header class="admin-dash-header">
            <img src="{{ asset('img/LOGO1.png') }}" alt="Logo Cesta Baiana Basquete" class="admin-dash-logo">
            <div>
                <h1 class="admin-dash-title">Painel do Administrador</h1>
            </div>
        </header>

        <div class="admin-grid">
            <article class="admin-card">
                <h2>Gestão de Atletas</h2>
                <p>Cadastre, atualize e importe atletas em lote.</p>
                <div class="admin-card-actions">
                    <a href="{{ route('atletas.create') }}" class="btn-admin">Cadastrar novo</a>
                    <a href="{{ route('admin.index') }}" class="btn-admin">Atualizar dados</a>
                    <a href="{{ route('atletas.import.form') }}" class="btn-admin">Importar em lote</a>
                </div>
            </article>

            <article class="admin-card">
                <h2>Gestão de Técnicos/Olheiros</h2>
                <p>Revise e ajuste os dados de técnicos e olheiros cadastrados.</p>
                <div class="admin-card-actions">
                    <a href="{{ route('admin.olheiros.index') }}" class="btn-admin">Atualizar técnicos/olheiros</a>
                </div>
            </article>

            <article class="admin-card">
                <h2>Análise e Relatórios</h2>
                <p>Acompanhe a visão geral, top visualizados e desempenho das shortlists.</p>
                <div class="admin-card-actions">
                    <a href="{{ route('relatorios.index') }}" class="btn-admin">Abrir relatórios</a>
                </div>
            </article>

            <article class="admin-card admin-card-exit">
                <h2>Sistema</h2>
                <p>Encerrar sessão do painel administrativo.</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-exit">
                        <i class="bi bi-box-arrow-right"></i>
                        Sair
                    </button>
                </form>
            </article>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .admin-dash-wrap {
            max-width: 1120px;
            margin: 0 auto;
            display: grid;
            gap: 16px;
            margin-bottom: 0.9rem;
        }

        .admin-dash-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            border-radius: 14px;
            background: linear-gradient(135deg, #fff4ea 0%, #ffe4cf 100%);
            border: 1px solid rgba(40, 54, 95, 0.08);
        }

        .admin-dash-logo {
            width: 84px;
            max-width: 100%;
            border-radius: 8px;
            background: #fff;
            border: 1px solid rgba(40, 54, 95, 0.08);
            padding: 4px;
        }

        .admin-dash-title {
            margin: 0;
            color: #28365f;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .admin-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .admin-card {
            background: #fff;
            border: 1px solid rgba(40, 54, 95, 0.12);
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 6px 18px rgba(20, 40, 80, 0.05);
        }

        .admin-card h2 {
            margin: 0 0 6px;
            color: #28365f;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .admin-card p {
            margin: 0 0 10px;
            color: #4a5878;
            font-size: 0.92rem;
            line-height: 1.35;
        }

        .admin-card-actions {
            display: grid;
            gap: 8px;
        }

        .btn-admin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 10px;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid #28365f;
            color: #28365f;
            font-weight: 700;
            background: #f8faff;
        }

        .btn-admin:hover {
            color: #1f2b4e;
            background: #eef3ff;
        }

        .admin-card-exit {
            background: #fff8f8;
            border-color: rgba(176, 32, 32, 0.2);
        }

        .btn-exit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #dc3545;
            background: #dc3545;
            color: #fff;
            font-weight: 700;
            width: 100%;
        }

        .btn-exit:hover {
            background: #bf2d3b;
        }

        @media (max-width: 991.98px) {
            .admin-grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .admin-dash-header {
                align-items: flex-start;
            }

            .admin-dash-wrap {
                margin-bottom: 1.25rem;
            }
        }

        @media (max-width: 575.98px) {
            .admin-dash-wrap {
                gap: 12px;
                margin-bottom: 1.6rem;
            }

            .admin-dash-title {
                font-size: 1.28rem;
                line-height: 1.1;
            }

            .admin-grid {
                gap: 20px;
            }

            .admin-card {
                margin-bottom: 2px;
            }
        }
    </style>
@endpush
