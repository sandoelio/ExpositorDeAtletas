@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
@endpush

@section('content')

<div class="welcome-page">

    <section class="welcome-hero">

        <img src="{{ asset('img/LOGO1.png') }}" alt="Logo">

        <h1>Onde o talento baiano ganha visibilidade.</h1>

        <p>
            A Vitrine de Atletas do Cesta Baiana Basquete nasceu para conectar
            atletas, clubes, treinadores, escolas e projetos esportivos,
            aproximando talentos de novas oportunidades.
        </p>

        <br>

        <a href="{{ route('home') }}" class="welcome-btn">
            Entrar na Vitrine
        </a>

    </section>

    <section class="welcome-box">

        <h3>O que é a Vitrine de Atletas?</h3>

        <p>
            Aqui reunimos, em um único espaço, informações de atletas de diferentes categorias,
            tornando mais fácil para clubes, escolas, treinadores, olheiros, organizadores de eventos
            e demais profissionais conhecerem quem está fazendo o basquete acontecer na Bahia.
        </p>

        <p>
            Mais do que um cadastro, esta é uma plataforma de valorização do atleta.
            Cada perfil funciona como uma vitrine digital, apresentando informações esportivas,
            características técnicas, histórico, posição em quadra, altura, vídeos, redes sociais
            e outros dados que ajudam o mercado a conhecer melhor cada jogador.
        </p>

        <h4>Nosso objetivo</h4>

        <ul>
            <li>🏀 Dar visibilidade aos atletas baianos.</li>
            <li>🎓 Aproximar talentos de oportunidades esportivas e educacionais.</li>
            <li>🤝 Fortalecer a conexão entre atletas, clubes, escolas e projetos.</li>
            <li>⭐ Valorizar o trabalho desenvolvido nas categorias de base da Bahia.</li>
            <li>📊 Criar um banco de dados organizado que represente o potencial do basquete baiano.</li>
        </ul>

        <div class="welcome-frase">
            Acreditamos que talento precisa ser visto.
        </div>

        <p>
            Quanto mais pessoas conhecerem nossos atletas, maiores serão as possibilidades
            de intercâmbios, convites, bolsas de estudo, participação em equipes,
            campeonatos e projetos dentro e fora da Bahia.
        </p>

        <div class="welcome-alert">
            <strong>O cadastro é gratuito e está em constante atualização.</strong><br><br>

            Nosso compromisso é construir, junto com toda a comunidade do basquete,
            a maior vitrine de atletas da Bahia.
        </div>
        <br>
        <h5>
            Se você sonha em crescer através do basquete,
            este espaço também é seu.
        </h5>
    </section>

</div>

@endsection