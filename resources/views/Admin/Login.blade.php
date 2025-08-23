@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-90" style="margin-top: 15%;">
  <form method="POST" action="{{ route('admin.login.post') }}"
        class="p-4 border rounded" style="max-width: 400px; width:100%">
    @csrf

    <h4 class="mb-4 text-center">Login Administração</h4>

    <div class="mb-3">
      <label for="email" class="form-label">E-mail</label>
      <input id="email" type="email" name="email"
             class="form-control" required autofocus>
    </div>

    <div class="mb-4">
      <label for="password" class="form-label">Senha</label>
      <input id="password" type="password" name="password"
             class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100" style="background:#e66000; border:none;" >
      Entrar
    </button>
  </form>
</div>
@endsection

<style>

  @media (max-width: 768px) {
    div.d-flex {
      margin-top: 30%;  
    }
  }
  
</style>