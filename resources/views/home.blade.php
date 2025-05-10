@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="my-4">Bem-vindo ao Cesta Baiana</h1>
    <p class="lead">Esta aplicação tem o objetivo de gerenciar atletas cadastrados, oferecendo uma forma prática de acessar informações.</p>
    <img src="{{ asset('img/basquete.png') }}" alt="Logo" class="basquete-img"> 

    <div class="mt-4">
        <a href="{{ route('atletas.index') }}" class="btn btn-primary btn-lg">Listar Atletas</a>
        <a href="{{ route('atletas.create') }}" class="btn btn-success btn-lg">Administração</a>
    </div>
</div>
@endsection
