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
            <a class="navbar-brand " href="#">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img"> 
                <spam class="spam-text"> Gestão de Atletas </spam>
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

<footer class="footer">
    <div class="container text-center">
        <p>&copy; {{ date('Y') }} Gestão de Atletas. Todos os direitos reservados.</p>
    </div>
</footer>

</html>

