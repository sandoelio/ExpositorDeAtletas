@extends('layouts.app')

@section('content')
  <div class="login-container d-flex justify-content-center align-items-center">
    <form method="POST"
          action="{{ route('admin.login.post') }}"
          class="p-4 border rounded w-100"
          style="max-width: 400px;">
      @csrf

      <h4 class="mb-4 text-center">Login Administração</h4>

      {{-- E-mail --}}
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input id="email"
               type="email"
               name="email"
               class="form-control"
               required
               autofocus>
      </div>

      {{-- Senha --}}
      <div class="mb-4">
        <label for="password" class="form-label">Senha</label>
        <input id="password"
               type="password"
               name="password"
               class="form-control"
               required>
      </div>

      {{-- Botões --}}
      <div class="mb-3">
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-between">
          <button type="submit"
                  class="btn btn-primary flex-fill me-sm-2"
                  style="background:#e66000; border:none">
            Entrar
          </button>
          <a href="{{ route('admin.dashboard') }}"
             class="btn btn-secondary flex-fill"
             style="background:#FF7209; color:white">
            Voltar
          </a>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('styles')
<style>
  /* controla margem-top só do container de login */
  .login-container {
    margin-top: 15%;
  }

  /* no mobile (<576px), diminui a margem para aproximar os campos do topo */
  @media (max-width: 575.98px) {
    .login-container {
      margin-top: 5%;
    }
  }
</style>
@endpush
