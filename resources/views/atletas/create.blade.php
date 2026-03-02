@extends('layouts.app')

@section('content')
    <div class="container atleta-form-wrap">
        @if (session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div id="error-message" class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form id="formAtleta" action="{{ route('atletas.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <section class="form-section mb-2">
                <h5 class="form-section-title">Dados pessoais</h5>
                <div class="row g-2 align-items-start">
                    <div class="col-12 col-md-3">
                        <label class="form-label d-block">Imagem atual</label>
                        <div class="image-preview-wrap">
                            <img id="imagem-preview"
                                src="{{ !empty($atleta) && !empty($atleta->imagem_base64) ? 'data:image/png;base64,' . $atleta->imagem_base64 : asset('img/avatar.png') }}"
                                alt="Imagem do atleta" class="image-preview">
                        </div>
                        <label for="imagem" class="form-label mt-2">Imagem do atleta</label>
                        <input type="file" class="form-control" name="imagem" id="imagem" accept="image/*">
                    </div>

                    <div class="col-12 col-md-9">
                        <div class="row g-2">
                            <div class="col-12 col-md-8">
                                <label for="nome_completo" class="form-label">Nome e sobrenome</label>
                                <input type="text" class="form-control" name="nome_completo" id="nome_completo"
                                    placeholder="Ex: Joao Silva" required value="{{ old('nome_completo') }}">
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="data_nascimento" class="form-label">Data de nascimento</label>
                                <input type="date" class="form-control" name="data_nascimento" id="data_nascimento"
                                    required value="{{ old('data_nascimento') }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="sexo" class="form-label">Sexo</label>
                                <select name="sexo" id="sexo" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <option value="Masculino" {{ old('sexo') === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Feminino" {{ old('sexo') === 'Feminino' ? 'selected' : '' }}>Feminino</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Ex: Salvador"
                                    required value="{{ old('cidade') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="form-section mb-2">
                <h5 class="form-section-title">Esporte</h5>
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label for="entidade" class="form-label">Instituicao</label>
                        <input type="text" class="form-control" name="entidade" id="entidade"
                            placeholder="Equipe ou instituicao" required value="{{ old('entidade') }}">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="posicao_jogo" class="form-label">Posicao no jogo</label>
                        <select name="posicao_jogo" id="posicao_jogo" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="Ala" {{ old('posicao_jogo') === 'Ala' ? 'selected' : '' }}>Ala</option>
                            <option value="Armador" {{ old('posicao_jogo') === 'Armador' ? 'selected' : '' }}>Armador</option>
                            <option value="Pivo" {{ old('posicao_jogo') === 'Pivo' ? 'selected' : '' }}>Pivo</option>
                            <option value="Ala-Armador" {{ old('posicao_jogo') === 'Ala-Armador' ? 'selected' : '' }}>Ala-Armador</option>
                            <option value="Ala-Pivo" {{ old('posicao_jogo') === 'Ala-Pivo' ? 'selected' : '' }}>Ala-Pivo</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="altura" class="form-label">Altura (m)</label>
                        <input type="text" class="form-control" name="altura" id="altura" inputmode="decimal"
                            placeholder="Ex: 1.75" required value="{{ old('altura') }}">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="peso" class="form-label">Peso (kg)</label>
                        <input type="text" class="form-control" name="peso" id="peso" inputmode="decimal"
                            placeholder="Ex: 75" required value="{{ old('peso') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </section>

            <section class="form-section mb-2">
                <h5 class="form-section-title">Contato</h5>
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label for="contato" class="form-label">Contato (telefone)</label>
                        <input type="text" class="form-control" name="contato" id="contato"
                            placeholder="Ex: (71) 91234-5678" required
                            value="{{ old('contato') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" name="email" id="email"
                            placeholder="Ex: atleta@email.com" value="{{ old('email') }}">
                    </div>
                </div>
            </section>

            <section class="form-section mb-2">
                <h5 class="form-section-title">Midia</h5>
                <div class="row g-2">
                    <div class="col-12">
                        <label for="resumo" class="form-label">Video (URL)</label>
                        <input type="url" class="form-control" name="resumo" id="resumo"
                            placeholder="https://exemplo.com/video" value="{{ old('resumo') }}">
                        <small class="text-muted">Informe o link de video com demonstracao do atleta.</small>
                    </div>
                </div>
            </section>

            <div class="form-actions-card">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary form-action-btn">Voltar</a>
                <button type="button" class="btn btn-outline-danger form-action-btn" id="btnSairAdmin">Sair</button>
                <button type="submit" class="btn btn-save form-action-btn" id="btnSalvar">Salvar</button>
            </div>
        </form>
        <form id="logout-admin-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .atleta-form-wrap {
            max-width: 980px;
            margin: 0 auto;
            margin-bottom: 0.9rem;
        }

        .form-section {
            border: 1px solid rgba(40, 54, 95, 0.14);
            border-radius: 12px;
            padding: 8px;
            background: #fff;
            box-shadow: 0 6px 16px rgba(17, 35, 70, 0.04);
        }

        .form-section-title {
            margin: 0 0 6px;
            color: #28365f;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .atleta-form-wrap .form-label {
            margin-bottom: 0.2rem;
            font-size: 0.88rem;
        }

        .atleta-form-wrap .form-control,
        .atleta-form-wrap .form-select {
            min-height: 34px;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            font-size: 0.92rem;
        }

        .image-preview-wrap {
            border: 1px dashed #cfd7e7;
            border-radius: 10px;
            padding: 8px;
            background: #f9fbff;
            text-align: center;
        }

        .image-preview {
            width: 100%;
            max-width: 96px;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #d7deea;
        }

        .form-actions-card {
            margin-top: 6px;
            padding: 6px;
            border: 1px solid rgba(40, 54, 95, 0.14);
            border-radius: 12px;
            background: #fff;
            display: flex;
            justify-content: flex-end;
            gap: 6px;
            flex-wrap: wrap;
        }

        .form-action-btn {
            min-width: 102px;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.35rem 0.55rem;
        }

        .btn-save {
            background: #ff7209;
            border: 1px solid #ff7209;
            color: #fff;
        }

        .btn-save:hover {
            background: #e66000;
            border-color: #e66000;
            color: #fff;
        }

        @media (max-width: 767.98px) {
            .form-section {
                padding: 7px;
            }

            .form-actions-card {
                justify-content: stretch;
            }

            .form-action-btn {
                flex: 1 1 calc(33.333% - 6px);
                min-width: 86px;
            }

            .atleta-form-wrap {
                margin-bottom: 1.5rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formAtleta');
            const altura = document.getElementById('altura');
            const peso = document.getElementById('peso');
            const contato = document.getElementById('contato');
            const imagem = document.getElementById('imagem');
            const preview = document.getElementById('imagem-preview');

            if (!form || !altura || !peso || !contato) {
                return;
            }

            const btnSairAdmin = document.getElementById('btnSairAdmin');
            const logoutForm = document.getElementById('logout-admin-form');

            function showFieldState(input, valid, message) {
                const feedback = input.nextElementSibling && input.nextElementSibling.classList.contains('invalid-feedback')
                    ? input.nextElementSibling
                    : null;

                if (valid) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    if (feedback) feedback.textContent = '';
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    if (feedback) feedback.textContent = message || 'Valor invalido.';
                }
            }

            function normalizeDecimal(value) {
                return String(value || '').replace(',', '.').trim();
            }

            function validateAltura() {
                const raw = normalizeDecimal(altura.value);
                const n = Number(raw);
                if (!raw) {
                    altura.classList.remove('is-valid', 'is-invalid');
                    return true;
                }
                const ok = !Number.isNaN(n) && n >= 0.5 && n <= 2.5;
                showFieldState(altura, ok, 'Informe altura entre 0.50 e 2.50 m.');
                return ok;
            }

            function validatePeso() {
                const raw = normalizeDecimal(peso.value);
                const n = Number(raw);
                if (!raw) {
                    peso.classList.remove('is-valid', 'is-invalid');
                    return true;
                }
                const ok = !Number.isNaN(n) && n >= 30 && n <= 150;
                showFieldState(peso, ok, 'Informe peso entre 30 e 150 kg.');
                return ok;
            }

            function formatPhone(digits) {
                const d = String(digits || '').slice(0, 13);
                if (d.length <= 2) return d;
                if (d.length <= 6) return `(${d.slice(0,2)}) ${d.slice(2)}`;
                if (d.length <= 10) return `(${d.slice(0,2)}) ${d.slice(2,6)}-${d.slice(6)}`;
                return `(${d.slice(0,2)}) ${d.slice(2,7)}-${d.slice(7,11)}`;
            }

            function validateContato() {
                const value = (contato.value || '').trim();
                if (!value) {
                    contato.classList.remove('is-valid', 'is-invalid');
                    return true;
                }

                if (value.includes('@')) {
                    const okEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                    showFieldState(contato, okEmail, 'Informe um e-mail valido.');
                    return okEmail;
                }

                const digits = value.replace(/\D/g, '');
                contato.value = formatPhone(digits);
                const okPhone = digits.length >= 10 && digits.length <= 13;
                showFieldState(contato, okPhone, 'Telefone deve ter entre 10 e 13 digitos.');
                return okPhone;
            }

            altura.addEventListener('input', validateAltura);
            peso.addEventListener('input', validatePeso);
            contato.addEventListener('input', validateContato);

            [altura, peso].forEach(el => {
                el.addEventListener('blur', function() {
                    const normalized = normalizeDecimal(el.value);
                    if (normalized) el.value = normalized;
                });
            });

            if (imagem && preview) {
                imagem.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            }

            form.addEventListener('submit', function(event) {
                const okAltura = validateAltura();
                const okPeso = validatePeso();
                const okContato = validateContato();

                if (!form.checkValidity() || !okAltura || !okPeso || !okContato) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            ['success-message', 'error-message'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    setTimeout(() => {
                        el.style.display = 'none';
                    }, 3000);
                }
            });

            if (btnSairAdmin && logoutForm) {
                btnSairAdmin.addEventListener('click', function() {
                    logoutForm.submit();
                });
            }
        });
    </script>
@endpush
