@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-90 text-center">
  <div>
    <h2 class="text-center my-5">Bem-vindo ao Painel de Administração</h2>
    <img src="{{ asset('img/LOGO1.png') }}" alt="Logo" class="basquete-img">

    <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
      <a href="{{ route('atletas.create') }}" class="btn-custom">Criar Atletas</a>
      <a href="{{ route('admin.index') }}" class="btn-custom">Atualizar Atletas</a>

      {{-- logout-form recebe classe específica --}}
      <form action="{{ route('logout') }}"
            method="POST"
            class="logout-form">
        @csrf
        <button type="submit" class="btn-custom">Sair</button>
      </form>
    </div>
  </div>
</div>

<style>
  /* --- botões comuns --- */
  .btn-custom {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    background: #FF7209;
    color: white;
    font-size: 1.1rem;
    font-weight: 500;
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    transition: 0.3s;
  }
  .btn-custom:hover {
    background: #e66000;
    transform: scale(1.05);
  }
  .basquete-img {
      width: 90%;
      max-width: 500px;
    }

  /* mobile: força cada item (links e form) a 100% */
  @media (max-width: 768px) {
    .mt-4.d-flex > * {
      flex: 0 0 100%;
    }
    .gap-3 {
      gap: 15px !important;
    }
    .btn-custom {
      width: 100%;
      padding: 15px;
      font-size: 1.2rem;
    }
    .basquete-img {
      width: 90%;
      max-width: 500px;
    }
  }
</style>
@endsection
