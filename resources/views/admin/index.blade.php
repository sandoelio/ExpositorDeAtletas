@extends('layouts.app')

@section('content')
    <div class="container admin-ath-wrap">
        @if (session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div id="error-message" class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="admin-ath-head mb-3">
            <div>
                <h4 class="m-0">Gestao de Atletas</h4>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-back">Voltar</a>
        </div>

        <div class="card admin-filter-card mb-3">
            <div class="card-body">
                <form action="{{ route('admin.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-6">
                        <label for="texto" class="form-label mb-1">Nome do atleta</label>
                        <input type="text" id="texto" name="texto" class="form-control"
                            value="{{ $filtroTexto ?? '' }}" placeholder="Digite o nome...">
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="entidade" class="form-label mb-1">Entidade</label>
                        <select name="entidade" id="entidade" class="form-select">
                            <option value="">Todas</option>
                            @foreach ($entidades as $ent)
                                <option value="{{ $ent }}" {{ ($filtroEntidade ?? '') === $ent ? 'selected' : '' }}>
                                    {{ $ent }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="filter-actions-row">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-filter">Filtrar</button>
                                <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Limpar</a>
                            </div>
                            <span class="admin-result-meta" id="admin-result-meta">{{ $atletas->total() }} atleta(s) encontrado(s)</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card admin-table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tabela-atletas">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Instituicao</th>
                            <th class="text-center acoes-col">Acoes</th>
                        </tr>
                    </thead>
                    <tbody id="admin-atletas-body">
                        @forelse($atletas as $a)
                            <tr>
                                <td class="fw-semibold">{{ $a->nome_completo }}</td>
                                <td>{{ $a->entidade }}</td>
                                <td class="text-center acoes-col">
                                    <div class="acoes-wrap">
                                        <a href="{{ route('atletas.edit', $a->id) }}" class="btn btn-sm btn-edit">
                                            Editar
                                        </a>
                                        <form action="{{ route('atletas.destroy', $a->id) }}" method="POST"
                                            onsubmit="return confirm('Excluir este atleta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3">Nenhum atleta encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center py-3" id="admin-pagination-wrap">
                {{ $atletas->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .admin-ath-wrap {
            max-width: 1040px;
            margin: 0 auto;
        }

        .admin-ath-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #fff5ed 0%, #ffe7d7 100%);
            border: 1px solid rgba(40, 54, 95, 0.1);
            border-radius: 12px;
            padding: 0.7rem 0.85rem;
        }

        .admin-ath-head h4 {
            color: #28365f;
            font-weight: 800;
        }

        .btn-back {
            background: #ff7209;
            color: #fff;
            border: 1px solid #ff7209;
            font-weight: 700;
            min-width: 92px;
        }

        .btn-back:hover {
            background: #e66000;
            border-color: #e66000;
            color: #fff;
        }

        .admin-filter-card {
            border: 1px solid rgba(40, 54, 95, 0.12);
            border-radius: 12px;
        }

        .btn-filter {
            background: #28365f;
            border: 1px solid #28365f;
            color: #fff;
            font-weight: 700;
        }

        .btn-filter:hover {
            background: #1f2b4f;
            border-color: #1f2b4f;
            color: #fff;
        }

        .filter-actions-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 4px;
        }

        .filter-buttons {
            display: inline-flex;
            gap: 8px;
        }

        .filter-buttons .btn {
            min-width: 108px;
            font-weight: 700;
        }

        .admin-result-meta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #28365f;
            color: #fff;
            border-radius: 8px;
            padding: 0.48rem 0.72rem;
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
        }

        .admin-table-card {
            border: 1px solid rgba(40, 54, 95, 0.12);
            border-radius: 12px;
            overflow: hidden;
        }

        .admin-table-card thead th {
            background: #ff7209;
            color: #28365f;
            font-weight: 800;
        }

        .admin-table-card td,
        .admin-table-card th {
            vertical-align: middle;
            padding: 0.7rem 0.65rem;
        }

        .acoes-col {
            width: 210px;
        }

        .acoes-wrap {
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }

        .btn-edit {
            background: #28365f;
            border: 1px solid #28365f;
            color: #fff;
        }

        .btn-edit:hover {
            background: #1f2b4f;
            border-color: #1f2b4f;
            color: #fff;
        }

        @media (max-width: 576px) {
            .admin-ath-wrap {
                margin-bottom: 2rem;
            }

            .admin-ath-head {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-back {
                width: 100%;
            }

            .filter-actions-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-buttons {
                width: 100%;
            }

            .filter-buttons .btn {
                flex: 1 1 0;
                min-width: 0;
            }

            .admin-result-meta {
                width: 100%;
            }

            .acoes-col {
                width: 1%;
                white-space: nowrap;
            }

            .acoes-wrap {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 6px;
            }

            .acoes-wrap .btn,
            .acoes-wrap form,
            .acoes-wrap form button {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ['success-message', 'error-message'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    setTimeout(() => {
                        el.style.display = 'none';
                    }, 3000);
                }
            });

            const inputNome = document.getElementById('texto');
            const selectEntidade = document.getElementById('entidade');
            const tbody = document.getElementById('admin-atletas-body');
            const countMeta = document.getElementById('admin-result-meta');
            const paginationWrap = document.getElementById('admin-pagination-wrap');

            if (!inputNome || !tbody || !countMeta || !paginationWrap) {
                return;
            }

            const initialTbody = tbody.innerHTML;
            const initialCount = countMeta.textContent;
            const initialPaginationDisplay = paginationWrap.style.display || '';
            const csrfToken = @json(csrf_token());

            function restoreInitialTable() {
                tbody.innerHTML = initialTbody;
                countMeta.textContent = initialCount;
                paginationWrap.style.display = initialPaginationDisplay;
            }

            function createActionCell(atleta) {
                const td = document.createElement('td');
                td.className = 'text-center acoes-col';

                const wrap = document.createElement('div');
                wrap.className = 'acoes-wrap';

                const editLink = document.createElement('a');
                editLink.href = atleta.edit_url;
                editLink.className = 'btn btn-sm btn-edit';
                editLink.textContent = 'Editar';
                wrap.appendChild(editLink);

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = atleta.delete_url;
                form.onsubmit = function() {
                    return confirm('Excluir este atleta?');
                };

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const deleteButton = document.createElement('button');
                deleteButton.className = 'btn btn-sm btn-danger';
                deleteButton.type = 'submit';
                deleteButton.textContent = 'Excluir';

                form.appendChild(tokenInput);
                form.appendChild(methodInput);
                form.appendChild(deleteButton);
                wrap.appendChild(form);
                td.appendChild(wrap);

                return td;
            }

            function renderRows(data) {
                tbody.innerHTML = '';
                if (!Array.isArray(data) || data.length === 0) {
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.colSpan = 3;
                    td.className = 'text-center py-3';
                    td.textContent = 'Nenhum atleta encontrado.';
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                    return;
                }

                data.forEach(atleta => {
                    const tr = document.createElement('tr');

                    const tdNome = document.createElement('td');
                    tdNome.className = 'fw-semibold';
                    tdNome.textContent = atleta.nome_completo || '';

                    const tdEntidade = document.createElement('td');
                    tdEntidade.textContent = atleta.entidade || '';

                    tr.appendChild(tdNome);
                    tr.appendChild(tdEntidade);
                    tr.appendChild(createActionCell(atleta));

                    tbody.appendChild(tr);
                });
            }

            let timer;
            function runLiveSearch() {
                const texto = (inputNome.value || '').trim();
                const entidade = selectEntidade ? (selectEntidade.value || '') : '';

                if (texto === '') {
                    restoreInitialTable();
                    return;
                }

                const params = new URLSearchParams();
                params.set('texto', texto);
                if (entidade) {
                    params.set('entidade', entidade);
                }

                fetch(@json(route('admin.buscaAtletas')) + '?' + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.ok ? res.json() : [])
                    .then(data => {
                        renderRows(data);
                        countMeta.textContent = `${Array.isArray(data) ? data.length : 0} atleta(s) encontrado(s)`;
                        paginationWrap.style.display = 'none';
                    })
                    .catch(() => {
                        restoreInitialTable();
                    });
            }

            inputNome.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(runLiveSearch, 250);
            });

            if (selectEntidade) {
                selectEntidade.addEventListener('change', function() {
                    const texto = (inputNome.value || '').trim();
                    if (texto === '') {
                        return;
                    }
                    runLiveSearch();
                });
            }
        });
    </script>
@endpush
