@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center min-vh-90 text-center">
        <div>
            <h2 class="text-center my-5">Bem-vindo ao Painel de Administração</h2>

            <!-- Logo responsiva -->
            <img src="{{ asset('img/LOGO1.png') }}" alt="Logo" class="basquete-img">

            <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('atletas.create') }}" class="btn-custom">Criar Atletas</a>
                <a href="{{ route('admin.index') }}" class="btn-custom">Atualizar Atletas</a>
                <form action="{{ route('logout') }}" method="POST" style="margin:0">
                    @csrf
                    <button type="submit" class="btn-custom">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Logo responsiva fluida */
        .basquete-img {
            width: 80%;
            max-width: 400px;
            height: auto;
            display: block;
            margin: 20px auto;
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

        /* Responsividade */
        @media (max-width: 768px) {
            .basquete-img {
                width: 90%;
                /* ocupa quase toda a tela */
                max-width: 500px;
                /* pode crescer mais no mobile */
            }

            .btn-custom {
                width: 100%;
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
        }
    </style>
@endsection
