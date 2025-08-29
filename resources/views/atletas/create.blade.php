@extends('layouts.app')

@section('content')
    <div class="container">

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

        <form id="formAtleta" action="{{ route('atletas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="id" id="atleta_id">

            <div id="imagem-container" style="display: flex; flex-direction: column; align-items: center;">
                <label>Imagem Atual:</label>
                <img id="imagem-preview"
                    src="{{ !empty($atleta) && !empty($atleta->imagem_base64) ? 'data:image/png;base64,' . $atleta->imagem_base64 : asset('img/avatar.png') }}"
                    alt="Imagem do Atleta"
                    style="max-width: 100px; border: 1px solid #ccc; padding: 4px; border-radius: 8px; display: block;">
            </div>

            <div class="row" style="margin-top: 15px;">
                <div class="col-md-6 mb-3">
                    <label for="imagem" class="form-label">Imagem do Atleta</label>
                    <input type="file" class="form-control" name="imagem" id="imagem" accept="image/*">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nome_completo" class="form-label">Nome e Sobrenome</label>
                    <input type="text" class="form-control" name="nome_completo" id="nome_completo"
                        placeholder="Ex: João Silva" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="peso" class="form-label">Peso (Kg)</label>
                    <input type="number" class="form-control" name="peso" id="peso" placeholder="Ex: 75" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" name="data_nascimento" id="data_nascimento" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Ex: São Paulo"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="posicao_jogo" class="form-label">Posição no Jogo</label>
                    <input type="text" class="form-control" name="posicao_jogo" id="posicao_jogo"
                        placeholder="Insira somente uma Ex: Amador" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="entidade" class="form-label">Instituição</label>
                    <input type="text" class="form-control" name="entidade" id="entidade"
                        placeholder="Nome da equipe ou instituição" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contato" class="form-label">Contato</label>
                    <input type="text" class="form-control" name="contato" id="contato" placeholder="Ex: 71912345678"
                        required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="altura" class="form-label">Altura (m)</label>
                    <input type="number" step="0.01" class="form-control" name="altura" id="altura"
                        placeholder="Ex: 1.75 use o ponto para separar" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sexo" class="form-label">Sexo</label>
                    <select name="sexo" class="form-select @error('sexo') is-invalid @enderror" required>
                        <option value="">Selecione...</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                    </select>
                    @error('sexo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="altura" class="form-label">Video</label>
                <input type="url" class="form-control" name="resumo" id="resumo"
                    placeholder="https://exemplo.com/video">
            </div>

            <div class="col-md-12 mb-3">
                <p class="video-instructions">
                    Para o cadastro do atleta, é necessário que o vídeo esteja hospedado em uma plataforma como ex: YouTube,Instagram.
                    Insira o link do vídeo do atleta demonstrando:
                </p>
                <ul class="video-requirements">
                    <li>01 arremessos do garrafão</li>
                    <li>01 arremessos da linha de 03 pontos</li>
                    <li>01 bandeja do lado esquerdo</li>
                    <li>01 bandeja do lado direito</li>
                    <li>Domínio de bola pelos cones</li>
                </ul>

            </div>

            <div class="d-flex justify-content-center gap-3 flex-wrap btn-container">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-custom" style="background:#e66000; color:white">
                    Voltar
                </a>
                <button type="submit" class="btn btn-custom" style="background:#e66000; color:white"
                    id="btnSalvar">Cadastrar
                    Atleta</button>
                <button type="button"
                    class="btn btn-secondary btn-custom" style="background:#e66000; color:white" onclick="location.reload()">Limpar</button>
            </div><br>
        </form>
    </div>

    <script>
        // Exibir mensagem de sucesso por 3 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const successMsg = document.getElementById('success-message');
            if (successMsg) {
                setTimeout(() => {
                    successMsg.style.display = 'none';
                }, 3000);
            }
        });
        document.getElementById('imagem').addEventListener('change', function(event) {
            const preview = document.getElementById('imagem-preview');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
<style>
    /* Botões padronizados */
    .btn-custom {
        flex: 1 1 220px;
        /* largura mínima 220px, todos iguais */
        max-width: 220px;
        /* largura máxima */
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

    /* Espaçamento dos botões */
    .btn-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
    }
</style>
