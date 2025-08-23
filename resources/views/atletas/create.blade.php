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

            <div class="mb-3">
                <label for="imagem" class="form-label">Imagem do Atleta</label>
                <input type="file" class="form-control" name="imagem" id="imagem" accept="image/*">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" class="form-control" name="cpf" id="cpf" placeholder="000.000.000-00"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nome_completo" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" name="nome_completo" id="nome_completo"
                        placeholder="Ex: João da Silva" required>
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
                        placeholder="Ex: Amador, Pivo..." required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="entidade" class="form-label">Entidade</label>
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
                        placeholder="Ex: 1.75" required>
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
                    Para o cadastro do atleta, é necessário que o vídeo esteja hospedado em uma plataforma como YouTube ou
                    Vimeo.
                    Insira o link do vídeo do atleta demonstrando:
                </p>
                <ul class="video-requirements">
                    <li>03 arremessos da zona morta</li>
                    <li>03 arremessos do garrafão</li>
                    <li>03 arremessos da linha de 03 pontos</li>
                    <li>01 bandeja do lado esquerdo</li>
                    <li>01 bandeja do lado direito</li>
                    <li>Domínio de bola pelos cones</li>
                </ul>

            </div>

            <div class="d-flex justify-content-center gap-3 flex-wrap btn-container">
                <a href="{{ route('home') }}" class="btn btn-custom" style="background:#e66000; color:white">
                    Voltar para a Home
                </a>
                <button type="submit" class="btn btn-custom" style="background:#e66000; color:white" id="btnSalvar">Cadastrar
                    Atleta</button>
                <button type="button" class="btn btn-custom btn-danger" id="btnExcluir" style="display: none;">Excluir Atleta</button>
                <button type="button" class="btn btn-secondary btn-custom"onclick="window.location.href='{{ route('atletas.create') }}'">Limpar</button>
             </div><br>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const cpfInput = document.querySelector('input[name="cpf"]');
            const form = document.getElementById('formAtleta');
            const btnSalvar = document.getElementById('btnSalvar');
            const btnExcluir = document.getElementById('btnExcluir');
            const token = document.querySelector('input[name="_token"]').value;

            cpfInput.addEventListener('blur', function() {
                const cpf = this.value.trim();
                if (cpf.length === 14) {
                    fetch(`/atletas/buscar-cpf?cpf=${cpf}`)
                        .then(response => {
                            if (!response.ok) throw new Error('CPF não encontrado');
                            return response.json();
                        })
                        .then(data => {
                            if (data.id) {
                                document.querySelector('input[name="nome_completo"]').value = data
                                    .nome_completo || '';
                                document.querySelector('input[name="data_nascimento"]').value = data
                                    .data_nascimento || '';
                                document.querySelector('input[name="cidade"]').value = data.cidade ||
                                    '';
                                document.querySelector('input[name="posicao_jogo"]').value = data
                                    .posicao_jogo || '';
                                document.querySelector('input[name="entidade"]').value = data
                                    .entidade || '';
                                document.querySelector('input[name="contato"]').value = data.contato ||
                                    '';
                                document.querySelector('input[name="peso"]').value = data.peso || '';
                                document.querySelector('input[name="altura"]').value = data.altura ||
                                    '';
                                document.querySelector('select[name="sexo"]').value = (data.sexo || '')
                                    .charAt(0).toUpperCase() + (data.sexo || '').slice(1).toLowerCase();
                                document.querySelector('input[name="resumo"]').value = data.resumo ||
                                    '';
                                document.getElementById('atleta_id').value = data.id;

                                const imagemPreview = document.getElementById('imagem-preview');
                                imagemPreview.src = data.imagem ?
                                    `data:image/jpeg;base64,${data.imagem}` : '/img/avatar.png';
                                imagemPreview.style.display = 'block';

                                form.action = `/atletas/${data.id}`;
                                if (!document.querySelector('input[name="_method"]')) {
                                    const methodInput = document.createElement('input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'PUT';
                                    form.appendChild(methodInput);
                                } else {
                                    document.querySelector('input[name="_method"]').value = 'PUT';
                                }

                                btnSalvar.textContent = 'Atualizar Atleta';
                                btnExcluir.style.display = 'inline-block';
                                btnExcluir.dataset.id = data.id;
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao buscar atleta:', error.message);
                        });
                }
            });

            btnExcluir.addEventListener('click', function() {
                const id = this.dataset.id;
                if (confirm('Deseja realmente excluir este atleta?')) {
                    fetch(`/atletas/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.mensagem) {
                                let successDiv = document.getElementById('success-message');
                                if (!successDiv) {
                                    successDiv = document.createElement('div');
                                    successDiv.id = 'success-message';
                                    successDiv.className = 'alert alert-success';
                                    form.parentNode.insertBefore(successDiv, form);
                                }
                                successDiv.textContent = data.mensagem;
                                successDiv.style.display = 'block';

                                setTimeout(() => {
                                    successDiv.style.display = 'none';
                                }, 3000);

                                form.reset();
                                form.action = '{{ route('atletas.store') }}';

                                if (document.querySelector('input[name="_method"]')) {
                                    document.querySelector('input[name="_method"]').remove();
                                }

                                document.getElementById('imagem-preview').src = '/img/slogan.png';
                                document.getElementById('imagem-preview').style.display = 'block';

                                btnSalvar.textContent = 'Cadastrar Atleta';
                                btnExcluir.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao excluir atleta:', error);
                        });
                }
            });

            // Exibir mensagem de sucesso por 3 segundos
            const successMsg = document.getElementById('success-message');
            if (successMsg) {
                setTimeout(() => {
                    successMsg.style.display = 'none';
                }, 3000);
            }

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
        });
    </script>
@endsection
<style>

  /* Botões padronizados */
        .btn-custom {
            flex: 1 1 220px;   /* largura mínima 220px, todos iguais */
            max-width: 220px;  /* largura máxima */
            text-align: center;
            background: #e66000;
            color: white;
            border: none;
        }

        .btn-custom:hover {
            background: #cc5200;
            color: white;
        }

        .btn-danger.btn-custom {
            background: #dc3545;
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