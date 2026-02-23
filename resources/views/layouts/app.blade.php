<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Vitrine de Atletas')</title>
    @stack('meta')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <link rel="icon" type="image/png" href="{{ asset('img/LOGO1.png') }}">

    <!-- No <head> do layouts.app -->
    @stack('styles')
    @stack('scripts')
    
</head>

<body class="pagina-tema">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Adicionando a logo -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <span class="spam-text">Vitrine de Atletas</span>
            </a>

            @if (request()->routeIs('olheiro.*') && auth('olheiro')->check())
                <div class="d-flex align-items-center gap-2 olheiro-topbar">
                    <small class="olheiro-topbar-user">Bem-vindo, {{ auth('olheiro')->user()->nome }}.</small>
                    <form method="POST" action="{{ route('olheiro.logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Sair</button>
                    </form>
                </div>
            @elseif (request()->routeIs('atletas.index'))
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm" title="Voltar para Home">
                    <i class="fas fa-home"></i>
                </a>
            @endif
        </div>
    </nav>

    <div class="d-flex flex-column flex-grow-1" style="flex: 1;">
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    <footer class="footer mt-auto site-footer">
        <div class="container text-center">
            Copyright &copy; {{ date('Y') }} |
            <a href="https://instagram.com/piraja.basquete" target="_blank" rel="noopener noreferrer">Basquete
                Pirajá</a>
        </div>
    </footer>
</body>

</html>
<style>
    footer.site-footer a {
            color: #28365F;
            font-weight: bold;
        }

    .olheiro-topbar-user {
        color: #fff;
        font-size: 0.9rem;
        line-height: 1;
        margin: 0;
    }

    @media (max-width: 576px) {
        .olheiro-topbar {
            gap: 6px !important;
        }

        .olheiro-topbar-user {
            font-size: 0.75rem;
        }
    }
</style>
