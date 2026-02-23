@extends('layouts.app')

@section('content')
    <div class="admin-olh-wrap">
        @if (session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div id="error-message" class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="admin-olh-head mb-3">
            <h4 class="m-0">Gestão de Técnicos/Olheiros</h4>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-back">Voltar</a>
        </div>

        <div class="card admin-olh-filter-card mb-3">
            <div class="card-body">
                <form action="{{ route('admin.olheiros.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-7">
                        <label for="nome" class="form-label mb-1">Nome do técnico/olheiro</label>
                        <input id="nome" type="text" name="nome" class="form-control"
                            placeholder="Digite o nome..." value="{{ $filtroNome }}">
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="olh-filter-actions">
                            <button type="submit" class="btn btn-filter">Filtrar</button>
                            <a href="{{ route('admin.olheiros.index') }}" class="btn btn-outline-secondary">Limpar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card admin-olh-table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tabela-olheiros">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Entidade</th>
                            <th>Cidade</th>
                            <th class="text-center acoes-col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($olheiros as $o)
                            <tr>
                                <td class="fw-semibold" data-label="Nome">{{ $o->nome }}</td>
                                <td data-label="Entidade">{{ $o->entidade }}</td>
                                <td data-label="Cidade">{{ $o->cidade }}</td>
                                <td class="text-center acoes-col" data-label="Ações">
                                    <div class="acoes-wrap">
                                        <a href="{{ route('admin.olheiros.edit', $o->id) }}" class="btn btn-sm btn-edit">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.olheiros.destroy', $o->id) }}" method="POST"
                                            onsubmit="return confirm('Excluir este técnico/olheiro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="4" class="text-center py-3">Nenhum técnico/olheiro encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center py-3">
                {{ $olheiros->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .admin-olh-wrap {
            max-width: 1040px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .admin-olh-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #fff5ed 0%, #ffe7d7 100%);
            border: 1px solid rgba(40, 54, 95, 0.1);
            border-radius: 12px;
            padding: 0.7rem 0.85rem;
        }

        .admin-olh-head h4 {
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

        .admin-olh-filter-card,
        .admin-olh-table-card {
            border: 1px solid rgba(40, 54, 95, 0.12);
            border-radius: 12px;
            overflow: hidden;
        }

        .olh-filter-actions {
            display: inline-flex;
            gap: 8px;
            width: 100%;
        }

        .olh-filter-actions .btn {
            flex: 1 1 0;
            min-width: 108px;
            font-weight: 700;
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

        .admin-olh-table-card thead th {
            background-color: #ff7209;
            color: #28365f;
            font-weight: 800;
        }

        .admin-olh-table-card td,
        .admin-olh-table-card th {
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
            .admin-olh-wrap {
                margin-bottom: 2rem;
            }

            .admin-olh-head {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-back {
                width: 100%;
            }

            .olh-filter-actions {
                width: 100%;
            }

            .olh-filter-actions .btn {
                min-width: 0;
            }

            .admin-olh-table-card .table-responsive {
                overflow-x: hidden;
            }

            #tabela-olheiros {
                width: 100%;
                min-width: 0;
                table-layout: fixed;
            }

            .admin-olh-table-card th,
            .admin-olh-table-card td {
                font-size: 0.78rem;
                padding: 0.42rem 0.34rem;
                white-space: normal;
                word-break: break-word;
                overflow-wrap: anywhere;
                text-align: center;
            }

            .admin-olh-table-card th:nth-child(1),
            .admin-olh-table-card td:nth-child(1) {
                width: 31%;
            }

            .admin-olh-table-card th:nth-child(2),
            .admin-olh-table-card td:nth-child(2) {
                width: 19%;
            }

            .admin-olh-table-card th:nth-child(3),
            .admin-olh-table-card td:nth-child(3) {
                width: 20%;
            }

            .acoes-col {
                width: 30%;
                white-space: normal;
            }

            .acoes-wrap {
                display: flex;
                flex-direction: column;
                gap: 4px;
                width: 100%;
                align-items: stretch;
            }

            .acoes-wrap .btn,
            .acoes-wrap form,
            .acoes-wrap form button {
                width: 100%;
                font-size: 0.7rem;
                line-height: 1.1;
                padding: 0.22rem 0.3rem;
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
        });
    </script>
@endpush
