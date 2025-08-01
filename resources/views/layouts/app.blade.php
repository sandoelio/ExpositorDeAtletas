<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestão de Atletas</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- No <head> do layouts.app -->
    @stack('styles')

</head>
<body class="pagina-tema">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <!-- Adicionando a logo -->
            <a class="navbar-brand" href="#">
                <img src="{{ asset('img/slogan.png') }}" alt="Logo" class="logo-img"> 
                <span class="spam-text"> Gestão de Atletas</span><br>
            </a>
        </div>
    </nav>

    <div class="d-flex flex-column flex-grow-1" style="flex: 1;">
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    <footer class="footer mt-auto">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Desenvolvido por Basquete Piraja. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>

