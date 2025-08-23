@extends('layouts.app')

@section('content')
<div class="container">
  <h2 class="mb-4">Editar Atleta</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Formulário de UPDATE --}}
  <form action="{{ route('atletas.update', $atleta->id) }}" 
        method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- CPF (read-only) e Nome --}}
    <div class="row mb-3">
      <div class="col">
        <label class="form-label">CPF</label>
        <input type="text" name="cpf" 
               value="{{ $atleta->cpf }}" class="form-control" readonly>
      </div>
      <div class="col">
        <label class="form-label">Nome Completo</label>
        <input type="text" name="nome_completo"
               value="{{ $atleta->nome_completo }}" class="form-control" required>
      </div>
    </div>

    {{-- Exemplo de imagem atual e opção de trocar --}}
    <div class="mb-3 text-center">
      <label class="form-label d-block">Imagem Atual</label>
      <img src="data:image/png;base64,{{ $atleta->imagem_base64 }}"
           style="max-width:100px" class="mb-2">
      <input type="file" name="imagem" class="form-control">
    </div>

    {{-- Outros campos pré-preenchidos --}}
    <div class="row mb-3">
      <div class="col">
        <label class="form-label">Peso (Kg)</label>
        <input type="number" name="peso" value="{{ $atleta->peso }}"
               class="form-control" required>
      </div>
      <div class="col">
        <label class="form-label">Data de Nascimento</label>
        <input type="date" name="data_nascimento"
               value="{{ $atleta->data_nascimento }}" class="form-control" required>
      </div>
    </div>

    {{-- Botão Atualizar --}}
    <button type="submit" class="btn btn-success">Atualizar</button>
  </form>

  {{-- Formulário de DELETE --}}
  <form action="{{ route('atletas.destroy', $atleta->id) }}" method="POST"
        onsubmit="return confirm('Confirma exclusão do atleta?');"
        class="mt-3">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Excluir Atleta</button>
  </form>
</div>
@endsection
