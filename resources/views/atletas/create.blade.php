@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center my-4">Cadastro de Atleta</h2>

    <form action="{{ route('atletas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem do Atleta</label>
            <input type="file" class="form-control" name="imagem" accept="image/*">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nome_completo" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" name="nome_completo" placeholder="Ex: João da Silva" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" name="data_nascimento" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" class="form-control" name="cidade" placeholder="Ex: São Paulo" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="posicao_jogo" class="form-label">Posição no Jogo</label>
                <input type="text" class="form-control" name="posicao_jogo" placeholder="Ex: Amador, Pivo..." required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="entidade" class="form-label">Entidade</label>
                <input type="text" class="form-control" name="entidade" placeholder="Nome da equipe ou instituição" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="contato" class="form-label">Contato</label>
                <input type="text" class="form-control" name="contato" placeholder="Ex: 71912345678" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" name="cpf" placeholder="000.000.000-00" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="peso" class="form-label">Peso (Kg)</label>
                <input type="number" class="form-control" name="peso" placeholder="Ex: 75" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="altura" class="form-label">Altura (m)</label>
                <input type="number" step="0.01" class="form-control" name="altura" placeholder="Ex: 1.75" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-control" name="sexo" required>
                    <option value="">Selecione...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="resumo" class="form-label">Resumo</label>
                <textarea class="form-control" name="resumo" rows="3" placeholder="Descreva informações adicionais sobre o atleta"></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                ← Voltar para a Home
            </a>
            <button type="submit" class="btn btn-success">Cadastrar Atleta</button>
        </div>
    </form>
</div>
@endsection
