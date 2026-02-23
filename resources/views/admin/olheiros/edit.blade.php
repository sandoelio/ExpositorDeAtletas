@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.olheiros.update', $olheiro->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row" style="margin-top: 15px;">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome"
                        value="{{ old('nome', $olheiro->nome) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" name="email" id="email"
                        value="{{ old('email', $olheiro->email) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" name="telefone" id="telefone"
                        value="{{ old('telefone', $olheiro->telefone) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="entidade" class="form-label">Entidade</label>
                    <input type="text" class="form-control" name="entidade" id="entidade"
                        value="{{ old('entidade', $olheiro->entidade) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" class="form-control" name="cidade" id="cidade"
                        value="{{ old('cidade', $olheiro->cidade) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input type="text" class="form-control" name="login" id="login"
                        value="{{ old('login', $olheiro->login) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Nova senha (opcional)</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                    <input type="password" class="form-control" name="password_confirmation"
                        id="password_confirmation">
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 flex-wrap btn-container">
                <a href="{{ route('admin.olheiros.index') }}" class="btn btn-custom"
                    style="background:#e66000; color:white;">
                    Voltar
                </a>
                <button type="submit" class="btn btn-custom" style="background:#e66000; color:white;">
                    Salvar alteracoes
                </button>
            </div>
            <br>
        </form>
    </div>
@endsection

<style>
    .btn-custom {
        flex: 1 1 220px;
        max-width: 220px;
        text-align: center;
        background: #e66000;
        color: white;
        border: none;
    }

    .btn-custom:hover {
        background: #cc5200;
        color: white;
    }

    .btn-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
    }
</style>

