<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vitrine de Atletas</title>
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
        <div class="container">
            <!-- Adicionando a logo -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('img/LOGO1.png') }}" alt="Logo" class="logo-img">
                <span class="spam-text"> Vitrine de Atletas</span><br>
            </a>
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
</style>