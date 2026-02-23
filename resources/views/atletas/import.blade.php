@extends('layouts.app')

@section('content')
    <div class="container import-wrap">
        <div class="import-head mb-3">
            <h3 class="m-0">Importar Atletas em Massa</h3>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Voltar</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card import-card mb-3">
            <div class="card-body">
                <div class="import-steps">
                    <div class="import-step">
                        <span class="step-badge">1</span>
                        <strong>Baixe o template</strong>
                        <small>Use o modelo oficial para evitar falhas na importacao.</small>
                    </div>
                    <div class="import-step">
                        <span class="step-badge">2</span>
                        <strong>Preencha os dados</strong>
                        <small>Mantenha os nomes de colunas do template.</small>
                    </div>
                    <div class="import-step">
                        <span class="step-badge">3</span>
                        <strong>Envie o arquivo</strong>
                        <small>Formatos aceitos: CSV, XLS, XLSX.</small>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('atletas.template') }}" class="btn btn-template">
                        Baixar Template XLS
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('atletas.import') }}" method="POST" enctype="multipart/form-data" id="import-form"
            class="card import-card">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label for="file" class="form-label">Arquivo CSV / XLS(X)</label>
                    <input type="file" name="file" id="file" accept=".csv,.xls,.xlsx" class="form-control" required>
                    <small id="file-selected" class="text-muted d-block mt-1">Nenhum arquivo selecionado.</small>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-import" id="btn-importar">Importar</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .import-wrap {
            max-width: 920px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .import-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 0.75rem 0.9rem;
            border-radius: 12px;
            border: 1px solid rgba(40, 54, 95, 0.12);
            background: linear-gradient(135deg, #fff5ed 0%, #ffe7d7 100%);
        }

        .import-head h3 {
            color: #28365f;
            font-weight: 800;
        }

        .import-card {
            border: 1px solid rgba(40, 54, 95, 0.12);
            border-radius: 12px;
            overflow: hidden;
        }

        .import-steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .import-step {
            border: 1px solid rgba(40, 54, 95, 0.12);
            border-radius: 10px;
            background: #f8faff;
            padding: 0.6rem;
            display: grid;
            gap: 4px;
        }

        .step-badge {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #28365f;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 800;
        }

        .import-step strong {
            color: #28365f;
            font-size: 0.95rem;
        }

        .import-step small {
            color: #4a5878;
            font-size: 0.8rem;
            line-height: 1.2;
        }

        .btn-template {
            background: #28365f;
            border: 1px solid #28365f;
            color: #fff;
            font-weight: 700;
        }

        .btn-template:hover {
            background: #1f2b4f;
            border-color: #1f2b4f;
            color: #fff;
        }

        .btn-import {
            background: #ff7209;
            border: 1px solid #ff7209;
            color: #fff;
            font-weight: 700;
            min-width: 110px;
        }

        .btn-import:hover {
            background: #e66000;
            border-color: #e66000;
            color: #fff;
        }

        @media (max-width: 767.98px) {
            .import-wrap {
                margin-bottom: 1.6rem;
            }

            .import-head {
                flex-direction: column;
                align-items: stretch;
            }

            .import-steps {
                grid-template-columns: 1fr;
            }

            .btn-template,
            .btn-import {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file');
            const selectedLabel = document.getElementById('file-selected');
            const form = document.getElementById('import-form');
            const importBtn = document.getElementById('btn-importar');

            if (fileInput && selectedLabel) {
                fileInput.addEventListener('change', function() {
                    const fileName = fileInput.files && fileInput.files[0] ? fileInput.files[0].name : '';
                    selectedLabel.textContent = fileName ? `Arquivo: ${fileName}` : 'Nenhum arquivo selecionado.';
                });
            }

            if (form && importBtn) {
                form.addEventListener('submit', function() {
                    importBtn.disabled = true;
                    importBtn.textContent = 'Importando...';
                });
            }
        });
    </script>
@endpush
