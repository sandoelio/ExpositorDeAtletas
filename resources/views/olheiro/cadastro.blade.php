@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('olheiro.register') }}">
            @csrf

            <div class="row" style="margin-top: 15px;">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input id="nome" type="text" name="nome"
                        class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input id="telefone" type="text" name="telefone"
                        class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone') }}"
                        required>
                    @error('telefone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="entidade" class="form-label">Qual a entidade pertence</label>
                    <input id="entidade" type="text" name="entidade"
                        class="form-control @error('entidade') is-invalid @enderror" value="{{ old('entidade') }}"
                        required>
                    @error('entidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input id="cidade" type="text" name="cidade"
                        class="form-control @error('cidade') is-invalid @enderror" value="{{ old('cidade') }}" required>
                    @error('cidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input id="login" type="text" name="login"
                        class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" required>
                    @error('login')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control"
                        required>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 flex-wrap btn-container">
                <a href="{{ route('olheiro.login.form') }}" class="btn btn-custom" style="background:#e66000; color:white">
                    Voltar
                </a>
                <button type="submit" class="btn btn-custom" style="background:#e66000; color:white;">
                    Cadastrar olheiro
                </button>
                <button type="button" class="btn btn-secondary btn-custom" style="background:#e66000; color:white;"
                    onclick="location.reload()">
                    Limpar
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

    .btn-secondary.btn-custom {
        background: #6c757d;
    }

    .btn-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
    }
</style>
