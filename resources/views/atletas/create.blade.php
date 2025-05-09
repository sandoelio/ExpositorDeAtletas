@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center my-4">Cadastro de Atleta</h2>

    <form action="{{ route('atletas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nome_completo" class="form-label">Nome Completo</label>
            <input type="text" class="form-control" name="nome_completo" required>
        </div>

        <div class="mb-3">
            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" name="data_nascimento" required>
        </div>

        <div class="mb-3">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" class="form-control" name="cidade" required>
        </div>

        <div class="mb-3">
            <label for="posicao_jogo" class="form-label">Posição no Jogo</label>
            <input type="text" class="form-control" name="posicao_jogo" required>
        </div>

        <div class="mb-3">
            <label for="entidade" class="form-label">Entidade</label>
            <input type="text" class="form-control" name="entidade" required>
        </div>

        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem do Atleta</label>
            <input type="file" class="form-control" name="imagem" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-success">Cadastrar Atleta</button>
    </form>
</div>
@endsection
