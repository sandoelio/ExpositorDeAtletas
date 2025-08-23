@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- Mensagens de sucesso / erro --}}
        @if (session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div id="error-message" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Formulário de edição --}}
        <form id="formAtleta"
              action="{{ route('atletas.update', $atleta->id) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="atleta_id" value="{{ $atleta->id }}">

            {{-- Preview da imagem atual --}}
            <div id="imagem-container" class="text-center mb-3">
                <label class="form-label d-block">Imagem Atual:</label>
                <img id="imagem-preview"
                     src="{{ $atleta->imagem_base64
                             ? 'data:image/png;base64,' . $atleta->imagem_base64
                             : asset('img/avatar.png') }}"
                     alt="Imagem do Atleta"
                     style="max-width:100px; border:1px solid #ccc; padding:4px; border-radius:8px;">
            </div>

            {{-- Trocar imagem --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="imagem" class="form-label">Nova Imagem</label>
                    <input type="file"
                           class="form-control"
                           name="imagem"
                           id="imagem"
                           accept="image/*">
                </div>
                <div class="col-md-6">
                    <label for="nome_completo" class="form-label">Nome Completo</label>
                    <input type="text"
                           class="form-control"
                           name="nome_completo"
                           id="nome_completo"
                           placeholder="Ex: João da Silva"
                           required
                           value="{{ old('nome_completo', $atleta->nome_completo) }}">
                </div>
            </div>

            {{-- Peso e Data de Nascimento --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="peso" class="form-label">Peso (Kg)</label>
                    <input type="number"
                           class="form-control"
                           name="peso"
                           id="peso"
                           placeholder="Ex: 75"
                           required
                           value="{{ old('peso', $atleta->peso) }}">
                </div>
                <div class="col-md-6">
                    <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date"
                           class="form-control"
                           name="data_nascimento"
                           id="data_nascimento"
                           required
                           value="{{ old('data_nascimento', $atleta->data_nascimento) }}">
                </div>
            </div>

            {{-- Cidade e Posição de Jogo --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text"
                           class="form-control"
                           name="cidade"
                           id="cidade"
                           placeholder="Ex: São Paulo"
                           required
                           value="{{ old('cidade', $atleta->cidade) }}">
                </div>
                <div class="col-md-6">
                    <label for="posicao_jogo" class="form-label">Posição no Jogo</label>
                    <input type="text"
                           class="form-control"
                           name="posicao_jogo"
                           id="posicao_jogo"
                           placeholder="Ex: Armador, Pivô..."
                           required
                           value="{{ old('posicao_jogo', $atleta->posicao_jogo) }}">
                </div>
            </div>

            {{-- Instituição e Contato --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="entidade" class="form-label">Instituição</label>
                    <input type="text"
                           class="form-control"
                           name="entidade"
                           id="entidade"
                           placeholder="Nome da equipe ou instituição"
                           required
                           value="{{ old('entidade', $atleta->entidade) }}">
                </div>
                <div class="col-md-6">
                    <label for="contato" class="form-label">Contato</label>
                    <input type="text"
                           class="form-control"
                           name="contato"
                           id="contato"
                           placeholder="Ex: 71912345678"
                           required
                           value="{{ old('contato', $atleta->contato) }}">
                </div>
            </div>

            {{-- Altura e Sexo --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="altura" class="form-label">Altura (m)</label>
                    <input type="number"
                           step="0.01"
                           class="form-control"
                           name="altura"
                           id="altura"
                           placeholder="Ex: 1.75"
                           required
                           value="{{ old('altura', $atleta->altura) }}">
                </div>
                <div class="col-md-6">
                    <label for="sexo" class="form-label">Sexo</label>
                    <select name="sexo"
                            id="sexo"
                            class="form-select"
                            required>
                        <option value="">Selecione...</option>
                        <option value="Masculino"
                          {{ old('sexo', $atleta->sexo) === 'masculino' ? 'selected' : '' }}>
                          Masculino
                        </option>
                        <option value="Feminino"
                          {{ old('sexo', $atleta->sexo) === 'feminino' ? 'selected' : '' }}>
                          Feminino
                        </option>
                    </select>
                </div>
            </div>

            {{-- Vídeo --}}
            <div class="mb-3">
                <label for="resumo" class="form-label">Vídeo</label>
                <input type="url"
                       class="form-control"
                       name="resumo"
                       id="resumo"
                       placeholder="https://exemplo.com/video"
                       value="{{ old('resumo', $atleta->resumo) }}">
            </div>

            {{-- Botões --}}
            <div class="d-flex justify-content-center gap-3 flex-wrap btn-container">
                <a href="{{ route('admin.index') }}"
                   class="btn btn-custom">
                    Voltar
                </a>
                <button type="submit"
                        class="btn btn-custom"
                        id="btnSalvar">
                    Atualizar Atleta
                </button>
            </div>
        </form>
    </div>

    {{-- Script para preview da imagem --}}
    <script>
        document.getElementById('imagem')
            .addEventListener('change', function(event) {
                const preview = document.getElementById('imagem-preview');
                const file    = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => preview.src = e.target.result;
                    reader.readAsDataURL(file);
                }
            });

        // Oculta mensagens após 3s
        document.addEventListener('DOMContentLoaded', function() {
            const successMsg = document.getElementById('success-message');
            const errorMsg   = document.getElementById('error-message');
            [successMsg, errorMsg].forEach(el => {
                if (el) setTimeout(() => el.style.display = 'none', 3000);
            });
        });
    </script>

@endsection

@push('styles')
<style>
    /* Botões padronizados */
    .btn-custom {
        flex: 1 1 220px;
        max-width: 220px;
        background: #e66000;
        color: #fff;
        border: none;
    }
    .btn-custom:hover {
        background: #cc5200;
        color: #fff;
    }
    .btn-container {
        margin-top: 20px;
        margin-bottom: 20px
    }
</style>
@endpush
