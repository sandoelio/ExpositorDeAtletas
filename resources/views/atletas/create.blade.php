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

            <section class="form-section mb-2">
                <h5 class="form-section-title">Portfolio esportivo</h5>
                <p class="portfolio-help">Preencha apenas informacoes que nao existem nos dados principais do atleta.</p>

                <ul class="nav nav-pills portfolio-tabs" id="portfolioTabsCreate" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" type="button" data-bs-toggle="pill" data-bs-target="#portfolio-resumo-create">Resumo</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" type="button" data-bs-toggle="pill" data-bs-target="#portfolio-qualidades-create">Qualidades</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" type="button" data-bs-toggle="pill" data-bs-target="#portfolio-temporadas-create">Temporadas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" type="button" data-bs-toggle="pill" data-bs-target="#portfolio-conquistas-create">Conquistas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" type="button" data-bs-toggle="pill" data-bs-target="#portfolio-historico-create">Historico</button>
                    </li>
                </ul>

                <div class="tab-content portfolio-tab-content">
                    <!-- ABA RESUMO -->
                    <div class="tab-pane fade show active" id="portfolio-resumo-create">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <label for="nacionalidade" class="form-label">Nacionalidade</label>
                                <input type="text" class="form-control" name="nacionalidade" id="nacionalidade"
                                    placeholder="Ex: Brasileiro" value="{{ old('nacionalidade') }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="estilo_jogo" class="form-label">Estilo de jogo</label>
                                <input type="text" class="form-control" name="estilo_jogo" id="estilo_jogo"
                                    placeholder="Ex: Ala 3&D, armador criador, pivo reboteiro" value="{{ old('estilo_jogo') }}">
                            </div>

                            <div class="col-12">
                                <label for="perfil_profissional" class="form-label">Resumo profissional</label>
                                <textarea class="form-control" name="perfil_profissional" id="perfil_profissional" rows="5"
                                    placeholder="Responda em texto curto: Como o atleta joga? Quais pontos fortes aparecem em quadra? O que ele entrega no ataque e na defesa? O que diferencia esse atleta?">{{ old('perfil_profissional') }}</textarea>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" name="instagram" id="instagram"
                                    placeholder="Ex: @atleta" value="{{ old('instagram') }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="highlights_texto" class="form-label">Chamada dos highlights</label>
                                <input type="text" class="form-control" name="highlights_texto" id="highlights_texto"
                                    placeholder="Ex: Highlights disponiveis sob demanda" value="{{ old('highlights_texto') }}">
                            </div>
                        </div>
                    </div>

                    <!-- ABA QUALIDADES -->
                    <div class="tab-pane fade" id="portfolio-qualidades-create">
                        <div class="dynamic-form-group">
                            <div id="qualidades-container" class="dynamic-items-list">
                                <div class="dynamic-item">
                                    <input type="text" class="form-control" name="qualidades[]" placeholder="Ex: Defensor de elite">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-item btn-remove-icon" style="display: none;"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-qualidade-btn">
                                <i class="bi bi-plus-circle"></i> Adicionar qualidade
                            </button>
                        </div>
                    </div>

                    <!-- ABA TEMPORADAS -->
                    <div class="tab-pane fade" id="portfolio-temporadas-create">
                        <div class="dynamic-form-group">
                            <div id="temporadas-container" class="dynamic-items-list">
                                <div class="dynamic-item temporada-item">
                                    <div class="row g-2">
                                        <div class="col-12 col-md-3">
                                            <label class="form-label small">Equipe</label>
                                            <input type="text" class="form-control" name="temporadas[equipe][]" placeholder="Ex: EC Bahia">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label small">Ícone</label>
                                            <input type="file" class="form-control team-icon-input" name="temporadas[icone][]" accept="image/*">
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label class="form-label small">Ano</label>
                                            <input type="text" class="form-control" name="temporadas[temporada][]" placeholder="Ex: 2025">
                                        </div>
                                        <div class="col-6 col-md-1">
                                            <label class="form-label small">PPG <span class="stat-help" title="Pontos por jogo" data-bs-toggle="tooltip" tabindex="0">?</span></label>
                                            <input type="text" class="form-control" name="temporadas[ppg][]" placeholder="21.5">
                                        </div>
                                        <div class="col-6 col-md-1">
                                            <label class="form-label small">RPG <span class="stat-help" title="Rebotes por jogo" data-bs-toggle="tooltip" tabindex="0">?</span></label>
                                            <input type="text" class="form-control" name="temporadas[rpg][]" placeholder="12.0">
                                        </div>
                                        <div class="col-6 col-md-1">
                                            <label class="form-label small">APG <span class="stat-help" title="Assistencias por jogo" data-bs-toggle="tooltip" tabindex="0">?</span></label>
                                            <input type="text" class="form-control" name="temporadas[apg][]" placeholder="3.4">
                                        </div>
                                        <div class="col-12 col-md-1">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-item btn-remove-icon" title="Remover" aria-label="Remover" style="display: none;"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-temporada-btn">
                                <i class="bi bi-plus-circle"></i> Adicionar temporada
                            </button>
                        </div>
                    </div>

                    <!-- ABA CONQUISTAS -->
                    <div class="tab-pane fade" id="portfolio-conquistas-create">
                        <div class="dynamic-form-group">
                            <div id="conquistas-container" class="dynamic-items-list">
                                <div class="dynamic-item conquista-item">
                                    <div class="row g-2">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label small">Equipe</label>
                                            <input type="text" class="form-control" name="conquistas[equipe][]" placeholder="Ex: EC Bahia">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label small">Ícone</label>
                                            <div class="d-flex flex-column align-items-center">
                                                <input type="file" class="form-control team-icon-input" name="conquistas[icone][]" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label small">Ano</label>
                                            <input type="text" class="form-control" name="conquistas[ano][]" placeholder="Ex: 2025">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label class="form-label small">Conquistas</label>
                                            <input type="text" class="form-control" name="conquistas[itens][]" placeholder="Ex: Campeão; MVP; Melhor ala (separar com ;)">
                                        </div>
                                        <div class="col-12 col-md-1">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-item btn-remove-icon" title="Remover" aria-label="Remover" style="display: none;"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-conquista-btn">
                                <i class="bi bi-plus-circle"></i> Adicionar conquista
                            </button>
                        </div>
                    </div>

                    <!-- ABA HISTÓRICO -->
                    <div class="tab-pane fade" id="portfolio-historico-create">
                        <div class="dynamic-form-group">
                            <div id="historico-container" class="dynamic-items-list">
                                <div class="dynamic-item historico-item">
                                    <div class="row g-2">
                                        <div class="col-12 col-md-3">
                                            <input type="text" class="form-control" name="historico[ano][]" placeholder="Ano">
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <input type="text" class="form-control" name="historico[equipe][]" placeholder="Equipe">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <div class="d-flex flex-column align-items-center">
                                                <input type="file" class="form-control" name="historico[icone][]" accept="image/*" aria-label="Icone do time">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-item btn-remove-icon" style="display: none;" title="Remover" aria-label="Remover"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-historico-btn">
                                <i class="bi bi-plus-circle"></i> Adicionar clube
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <div class="form-actions-card">
                <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary form-action-btn">Voltar</a>
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

        .portfolio-help {
            margin: -2px 0 8px;
            color: #5d6b84;
            font-size: 0.84rem;
        }

        .portfolio-tabs {
            gap: 5px;
            margin-bottom: 8px;
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 2px;
        }

        .portfolio-tabs .nav-link {
            white-space: nowrap;
            color: #28365f;
            background: #eef3fb;
            border: 1px solid #d7deea;
            font-size: 0.82rem;
            font-weight: 800;
            padding: 0.35rem 0.6rem;
        }

        .portfolio-tabs .nav-link.active {
            background: #28365f;
            border-color: #28365f;
            color: #fff;
        }

        .portfolio-tab-content {
            border: 1px solid #d7deea;
            border-radius: 10px;
            padding: 8px;
            background: #f9fbff;
        }

        .code-textarea {
            font-family: Consolas, monospace;
            font-size: 0.78rem !important;
        }

        .dynamic-form-group {
            margin-top: 0.5rem;
        }

        .dynamic-items-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .dynamic-item {
            padding: 0.75rem;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .dynamic-item input {
            flex: 1;
            min-width: 0;
        }

        .dynamic-item .remove-item {
            flex-shrink: 0;
        }

        .dynamic-item .remove-item:not([style*="display: none"]) {
            display: inline-flex !important;
        }

        .dynamic-item .btn-remove-icon {
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin-top: 1.45rem;
        }

        .team-icon-input {
            padding-left: 0.35rem;
            padding-right: 0.35rem;
            font-size: 0.78rem !important;
        }

        .team-icon-preview {
            width: 34px;
            height: 34px;
            display: block;
            margin-bottom: 0.5rem;
            border: 1px solid #d7deea;
            border-radius: 50%;
            object-fit: cover;
            background: #fff;
            order: -1;
        }

        .stat-help {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            margin-left: 3px;
            border-radius: 50%;
            background: #28365f;
            color: #fff;
            font-size: 0.68rem;
            font-weight: 800;
            line-height: 1;
            cursor: help;
        }

        .dynamic-item.temporada-item,
        .dynamic-item.conquista-item,
        .dynamic-item.historico-item {
            display: block;
        }

        .dynamic-item.temporada-item .row,
        .dynamic-item.conquista-item .row,
        .dynamic-item.historico-item .row {
            align-items: flex-end;
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
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .btn-save {
            background: #28365f;
            border-color: #28365f;
            color: #fff;
        }

        .btn-save:hover {
            background: #1f2847;
            border-color: #1f2847;
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .atleta-form-wrap {
                margin-bottom: 0.5rem;
            }

            .form-section {
                padding: 6px;
            }

            .portfolio-tabs {
                gap: 3px;
            }

            .portfolio-tabs .nav-link {
                font-size: 0.75rem;
                padding: 0.25rem 0.4rem;
            }

            .form-actions-card {
                flex-direction: column;
            }

            .form-action-btn {
                width: 100%;
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

            // ===== GERENCIAMENTO DE CAMPOS DINÂMICOS =====
            function setupDynamicFields(containerId, addButtonId, itemTemplate, maxItems = null) {
                const container = document.getElementById(containerId);
                const addBtn = document.getElementById(addButtonId);

                if (!container || !addBtn) return;

                function updateRemoveButtons() {
                    const items = container.querySelectorAll('.dynamic-item');
                    if (maxItems) {
                        addBtn.disabled = items.length >= maxItems;
                        addBtn.classList.toggle('disabled', items.length >= maxItems);
                    }

                    items.forEach(item => {
                        const removeBtn = item.querySelector('.remove-item');
                        if (removeBtn) {
                            if (items.length > 1) {
                                removeBtn.style.display = 'inline-flex';
                                removeBtn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    item.remove();
                                    updateRemoveButtons();
                                });
                            } else {
                                removeBtn.style.display = 'none';
                            }
                        }
                    });
                }

                addBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (maxItems && container.querySelectorAll('.dynamic-item').length >= maxItems) {
                        updateRemoveButtons();
                        return;
                    }

                    const newItem = document.createElement('div');
                    newItem.innerHTML = itemTemplate;
                    newItem.className = 'dynamic-item ' + container.querySelector('.dynamic-item').className.split(' ').slice(2).join(' ');
                    container.appendChild(newItem);
                    initializeTooltips(newItem);
                    updateRemoveButtons();
                });

                updateRemoveButtons();
            }

            // Qualidades
            setupDynamicFields('qualidades-container', 'add-qualidade-btn', 
                '<input type="text" class="form-control" name="qualidades[]" placeholder="Ex: Defensor de elite">' +
                '<button type="button" class="btn btn-sm btn-outline-danger remove-item btn-remove-icon"><i class="bi bi-trash"></i></button>'
            );

            // Temporadas
            setupDynamicFields('temporadas-container', 'add-temporada-btn',
                '<div class="row g-2">' +
                '<div class="col-12 col-md-3"><label class="form-label small">Equipe</label><input type="text" class="form-control" name="temporadas[equipe][]" placeholder="Ex: EC Bahia"></div>' +
                '<div class="col-12 col-md-2"><label class="form-label small">Icone</label><input type="file" class="form-control team-icon-input" name="temporadas[icone][]" accept="image/*"></div>' +
                '<div class="col-6 col-md-2"><label class="form-label small">Ano</label><input type="text" class="form-control" name="temporadas[temporada][]" placeholder="Ex: 2025"></div>' +
                '<div class="col-6 col-md-1"><label class="form-label small">PPG <span class="stat-help" title="Pontos por jogo" data-bs-toggle="tooltip" tabindex="0">?</span></label><input type="text" class="form-control" name="temporadas[ppg][]" placeholder="21.5"></div>' +
                '<div class="col-6 col-md-1"><label class="form-label small">RPG <span class="stat-help" title="Rebotes por jogo" data-bs-toggle="tooltip" tabindex="0">?</span></label><input type="text" class="form-control" name="temporadas[rpg][]" placeholder="12.0"></div>' +
                '<div class="col-6 col-md-1"><label class="form-label small">APG <span class="stat-help" title="Assistencias por jogo" data-bs-toggle="tooltip" tabindex="0">?</span></label><input type="text" class="form-control" name="temporadas[apg][]" placeholder="3.4"></div>' +
                '<div class="col-12 col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-item btn-remove-icon" title="Remover" aria-label="Remover"><i class="bi bi-trash"></i></button></div>' +
                '</div>',
                2
            );

            // Conquistas
            setupDynamicFields('conquistas-container', 'add-conquista-btn',
                '<div class="row g-2">' +
                '<div class="col-12 col-md-4"><input type="text" class="form-control" name="conquistas[equipe][]" placeholder="Equipe"></div>' +
                '<div class="col-12 col-md-2"><div class="d-flex flex-column align-items-center"><input type="file" class="form-control team-icon-input" name="conquistas[icone][]" accept="image/*"></div></div>' +
                '<div class="col-12 col-md-2"><input type="text" class="form-control" name="conquistas[ano][]" placeholder="Ex: 2025"></div>' +
                '<div class="col-12 col-md-3"><input type="text" class="form-control" name="conquistas[itens][]" placeholder="Ex: Campeao; MVP; Melhor ala (separar com ;)"></div>' +
                '<div class="col-12 col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-item btn-remove-icon" title="Remover" aria-label="Remover"><i class="bi bi-trash"></i></button></div>' +
                '</div>',
                3
            );

            // Histórico
            setupDynamicFields('historico-container', 'add-historico-btn',
                '<div class="row g-2">' +
                '<div class="col-12 col-md-3"><input type="text" class="form-control" name="historico[ano][]" placeholder="Ano"></div>' +
                '<div class="col-12 col-md-4"><input type="text" class="form-control" name="historico[equipe][]" placeholder="Equipe"></div>' +
                '<div class="col-12 col-md-2"><div class="d-flex flex-column align-items-center"><input type="file" class="form-control" name="historico[icone][]" accept="image/*" aria-label="Icone do time"></div></div>' +
                '<div class="col-12 col-md-3"><button type="button" class="btn btn-sm btn-outline-danger remove-item btn-remove-icon" title="Remover" aria-label="Remover"><i class="bi bi-trash"></i></button></div>' +
                '</div>',
                7
            );

            function initializeTooltips(scope) {
                const root = scope || document;
                root.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
                    if (window.bootstrap && window.bootstrap.Tooltip) {
                        window.bootstrap.Tooltip.getOrCreateInstance(el);
                    }
                });
            }

            initializeTooltips(document);
        });
    </script>
@endpush
