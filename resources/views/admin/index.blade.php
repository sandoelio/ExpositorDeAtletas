@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- Mensagens --}}
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

        <div class="table-center mb-4">

            {{-- FILTRO --}}
            <form action="{{ route('admin.index') }}" method="GET" class="mb-3" id="form-filtros">

                <div class="row gx-2">
                    <div class="col-6">
                        <input type="text" id="busca-ajax" class="form-control"
                            placeholder="Digite o nome do atleta..."><br>
                    </div>
                    <div class="col-6">
                        <select name="entidade" id="entidade" class="form-select" onchange="this.form.submit()">
                            <option value="">Todas</option>
                            @foreach ($entidades as $ent)
                                <option value="{{ $ent }}" {{ $filtroEntidade === $ent ? 'selected' : '' }}>
                                    {{ $ent }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- BOTOES lado a lado 50% cada --}}
                <div class="row gx-2">
                    <div class="col-6">
                        <a href="{{ route('admin.index') }}" class="btn btn-secondary w-100">
                            Limpar filtro
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-custom w-100"
                            style="background:#FF7209; color:white">
                            Voltar
                        </a>
                    </div>
                </div>
            </form>

            {{-- TABELA --}}
            <table class="table table-striped" id="tabela-atletas">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Instituição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($atletas as $a)
                        <tr>
                            <td>{{ $a->nome_completo }}</td>
                            <td>{{ $a->entidade }}</td>
                            <td class="text-center btn-acoes">
                                <a href="{{ route('atletas.edit', $a->id) }}" class="btn btn-sm btn-primary"
                                    style="background:#28365F; border:none">
                                    Editar
                                </a>
                                <form action="{{ route('atletas.destroy', $a->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Excluir este atleta?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Nenhum atleta encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINAÇÃO --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $atletas->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* wrapper que alinha a tabela e o select juntos */
    .table-center {
        width: 100%;
        margin: 0 auto;
        text-align: center;
    }

    @media (min-width: 992px) {
        .table-center {
            width: 80%;
            text-align: center;
        }
    }

    .table-center .form-select {
        width: 100%;
    }

    .table thead th {
        background-color: #FF7209;
        color: #28365F;
    }

    /* --- ESTILO DOS BOTÕES NO MOBILE --- */
    @media (max-width: 576px) {

        /* coloca os botões um embaixo do outro */
        .btn-acoes {
            display: flex !important;
            flex-direction: column !important;
            gap: 10px;
            align-items: stretch !important;
        }

        /* faz os botões terem 100% da largura */
        .btn-acoes .btn,
        .btn-acoes form button {
            width: 100% !important;
        }
    }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let urlBuscar = "{{ route('admin.buscaAtletas') }}";

            if (urlBuscar && !urlBuscar.startsWith('/') && !urlBuscar.startsWith('http')) {
                urlBuscar = '/' + urlBuscar;
            }

            const campoBusca = document.getElementById('busca-ajax');
            const filtroEntidade = document.getElementById('entidade');
            const tabela = document.getElementById('tabela-atletas');
            const tbody = tabela.querySelector('tbody');

            let debounceTimer = null;
            const DEBOUNCE_MS = 300;
            const MIN_CHARS = 1;

            async function buscar(texto) {
                try {
                    const ent = filtroEntidade ? filtroEntidade.value : '';

                    const params = new URLSearchParams();
                    if (texto) params.set('texto', texto);
                    if (ent) params.set('entidade', ent);

                    const fullUrl = urlBuscar + (params.toString() ? ('?' + params.toString()) : '');

                    const res = await fetch(fullUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    if (!res.ok) {
                        let text = '';
                        try {
                            text = await res.text();
                        } catch (e) {
                            text = 'Não foi possível ler o body da resposta.';
                        }
                        console.error('Erro na resposta fetch buscarAtletas:', res.status, text);
                    }

                    const data = await res.json();

                    if (!Array.isArray(data)) {
                        console.error('Resposta inesperada (não é array):', data);
                        tbody.innerHTML =
                            `<tr><td colspan="3" class="text-center">Resposta inesperada do servidor.</td></tr>`;
                        return;
                    }

                    if (data.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="3" class="text-center">Nenhum atleta encontrado.</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = '';
                    data.forEach(a => {
                        const nome = a.nome_completo ? escapeHtml(a.nome_completo) : '';
                        const entTxt = a.entidade ? escapeHtml(a.entidade) : '';

                        tbody.innerHTML += `
                    <tr>
                        <td>${nome}</td>
                        <td>${entTxt}</td>
                        <td class="text-center btn-acoes">
                            <a href="${a.edit_url}" class="btn btn-sm btn-primary" 
                               style="background:#28365F; border:none; width:100%">
                                Editar
                            </a>

                            <form action="${a.delete_url}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Excluir este atleta?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" style="width:100%">Excluir</button>
                            </form>
                        </td>
                    </tr>
                `;
                    });

                } catch (err) {
                    console.error('Erro fetch buscarAtletas:', err);
                    tbody.innerHTML =
                        `<tr><td colspan="3" class="text-center">Erro ao buscar atletas.</td></tr>`;
                }
            }

            campoBusca.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const valor = campoBusca.value.trim();

                if (valor.length < MIN_CHARS) {
                    return;
                }

                debounceTimer = setTimeout(() => buscar(valor), DEBOUNCE_MS);
            });

            if (filtroEntidade) {
                filtroEntidade.addEventListener('change', function() {
                    const valor = campoBusca.value.trim();
                    if (valor.length >= MIN_CHARS) {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => buscar(valor), DEBOUNCE_MS);
                    }
                });
            }

            function escapeHtml(text) {
                return String(text)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

        });

        // oculta mensagens após 3s
        document.addEventListener('DOMContentLoaded', function() {
            ['success-message', 'error-message'].forEach(id => {
                const el = document.getElementById(id);
                if (el) setTimeout(() => el.style.display = 'none', 3000);
            });
        });
    </script>
@endpush
