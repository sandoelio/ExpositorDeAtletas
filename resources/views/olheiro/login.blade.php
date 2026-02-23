@extends('layouts.app')

@section('content')
    <div class="login-container d-flex justify-content-center align-items-center">
        <form method="POST" action="{{ route('olheiro.login') }}" class="p-4 border rounded w-100"
            style="max-width: 400px;">
            @csrf

            <h4 class="mb-4 text-center">Login Técnico/Olheiro</h4>

            @if (session('olheiro_success'))
                <div class="alert alert-success py-2">{{ session('olheiro_success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mb-3">
                <label for="login" class="form-label">Login</label>
                <input id="login" type="text" name="login" class="form-control" value="{{ old('login') }}" required
                    autofocus>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Senha</label>
                <input id="password" type="password" name="password" class="form-control" required>
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-sm-between">
                <button type="submit" class="btn btn-primary flex-fill me-sm-2" style="background:#e66000; border:none;">
                    Entrar
                </button>
                <a href="{{ route('olheiro.register.form') }}" class="btn btn-secondary flex-fill me-sm-2"
                    style="background:#FF7209; color:white; border:none;">
                    Cadastrar
                </a>
                <a href="{{ route('home') }}" class="btn btn-secondary flex-fill"
                    style="background:#FF7209; color:white; border:none;">
                    Voltar
                </a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .login-container {
            margin-top: 15%;
        }

        @media (max-width: 575.98px) {
            .login-container {
                margin-top: 5%;
            }
        }
    </style>
@endpush
