@extends('layouts.app')

@section('content')
    <div class="container py-2">
        @if (session('olheiro_flash'))
            <div id="olheiro-flash" class="alert alert-success py-2">{{ session('olheiro_flash') }}</div>
        @endif

        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-2">Criar shortlist</h6>
                        <form method="POST" action="{{ route('olheiro.shortlists.store') }}">
                            @csrf
                            <div class="mb-2">
                                <input type="text" name="nome" class="form-control form-control-sm" maxlength="8"
                                    placeholder="Nome da shortlist" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100">Criar</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-2">Minhas shortlists</h6>
                        @if ($shortlists->isEmpty())
                            <small class="text-muted">Nenhuma shortlist criada.</small>
                        @else
                            @php
                                $totalShortlists = $shortlists->count();
                                $gridClass = 'shortlists-grid-3';
                                $scrollClass = $totalShortlists >= 6 ? 'shortlists-scroll' : '';
                            @endphp
                            <div class="shortlists-grid {{ $gridClass }} {{ $scrollClass }}">
                                @foreach ($shortlists as $shortlist)
                                    @php
                                        $ativa =
                                            $shortlistSelecionada && $shortlistSelecionada->id === $shortlist->id;
                                    @endphp
                                    <div class="shortlist-mini {{ $ativa ? 'shortlist-mini-active' : '' }}">
                                        <a class="d-block fw-semibold text-decoration-none shortlist-mini-name"
                                            title="{{ $shortlist->nome }}"
                                            href="{{ route('olheiro.atletas.index', ['shortlist_id' => $shortlist->id]) }}">
                                            {{ $shortlist->nome }}
                                        </a>
                                        <small class="text-muted shortlist-mini-count text-center d-block">
                                            {{ $shortlist->itens_count }} atleta(s)
                                        </small>
                                        <div class="shortlist-mini-actions">
                                            <a href="{{ route('olheiro.atletas.index', ['shortlist_id' => $shortlist->id]) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                Abrir
                                            </a>
                                            <form method="POST"
                                                action="{{ route('olheiro.shortlists.destroy', $shortlist->id) }}" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-2">Meus favoritos</h6>
                        @if ($favoritos->isEmpty())
                            <small class="text-muted">Ainda sem favoritos.</small>
                        @else
                            @php $usarScrollFavoritos = $favoritos->count() >= 4; @endphp
                            <div class="{{ $usarScrollFavoritos ? 'favoritos-scroll' : '' }}">
                                <ul class="list-group list-group-flush">
                                    @foreach ($favoritos as $fav)
                                        <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                                            <span class="small">{{ $fav->nome_completo }}</span>
                                            <span class="badge bg-light text-dark">{{ $fav->posicao_jogo }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-2">Filtro de atletas</h6>
                        <form method="GET" action="{{ route('olheiro.atletas.index') }}" class="row g-2">
                            @if ($shortlistSelecionada)
                                <input type="hidden" name="shortlist_id" value="{{ $shortlistSelecionada->id }}">
                            @endif
                            <div class="col-12 col-md-6">
                                <input type="text" name="nome" value="{{ request('nome') }}"
                                    placeholder="Nome do atleta" class="form-control form-control-sm">
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="cidade" class="form-select form-select-sm">
                                    <option value="">Cidade</option>
                                    @foreach ($cidades as $cidade)
                                        <option value="{{ $cidade }}" {{ request('cidade') === $cidade ? 'selected' : '' }}>
                                            {{ $cidade }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="entidade" class="form-select form-select-sm">
                                    <option value="">Entidade</option>
                                    @foreach ($entidades as $entidade)
                                        <option value="{{ $entidade }}" {{ request('entidade') === $entidade ? 'selected' : '' }}>
                                            {{ $entidade }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="posicao" class="form-select form-select-sm">
                                    <option value="">Posicao</option>
                                    @foreach ($posicoes as $posicao)
                                        <option value="{{ $posicao }}" {{ request('posicao') === $posicao ? 'selected' : '' }}>
                                            {{ $posicao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <button type="submit" class="btn btn-sm btn-primary w-100">Filtrar</button>
                            </div>
                            <div class="col-12 col-md-3">
                                <a href="{{ route('olheiro.atletas.index', $shortlistSelecionada ? ['shortlist_id' => $shortlistSelecionada->id] : []) }}"
                                    class="btn btn-sm btn-outline-secondary w-100">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($shortlistSelecionada)
                    <div class="accordion mb-3" id="shortlistAtivaAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="shortlistAtivaHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#shortlistAtivaCollapse" aria-expanded="false"
                                    aria-controls="shortlistAtivaCollapse">
                                    Shortlist ativa: {{ $shortlistSelecionada->nome }}
                                    <small class="ms-2 text-muted">({{ $shortlistSelecionada->itens->count() }} atleta(s))</small>
                                </button>
                            </h2>
                            <div id="shortlistAtivaCollapse" class="accordion-collapse collapse"
                                aria-labelledby="shortlistAtivaHeading" data-bs-parent="#shortlistAtivaAccordion">
                                <div class="accordion-body p-2 p-md-3">
                                    @include('olheiro._shortlist_ativa', ['semMargemTopo' => true])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row g-3">
                    @forelse($atletas as $atleta)
                        <div class="col-12 col-md-6">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex gap-2 mb-2">
                                        <img src="{{ $atleta->imagem_base64 ? 'data:image/png;base64,' . $atleta->imagem_base64 : asset('img/avatar.png') }}"
                                            alt="{{ $atleta->nome_completo }}" width="64" height="64"
                                            style="border-radius:8px; object-fit:cover;">
                                        <div>
                                            <div class="fw-semibold">{{ $atleta->nome_completo }}</div>
                                            <div class="small text-muted">{{ $atleta->posicao_jogo }} |
                                                {{ $atleta->entidade }}</div>
                                            <div class="small text-muted">{{ $atleta->cidade }} |
                                                {{ (int) ($atleta->visualizacoes ?? 0) }} visualizacoes</div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mt-auto">
                                        <form method="POST" action="{{ route('olheiro.favoritos.toggle', $atleta->id) }}">
                                            @csrf
                                            @php $isFav = in_array($atleta->id, $favoritoIds); @endphp
                                            <button type="submit"
                                                class="btn btn-sm {{ $isFav ? 'btn-danger' : 'btn-outline-danger' }}">
                                                {{ $isFav ? 'Desfavoritar' : 'Favoritar' }}
                                            </button>
                                        </form>

                                        <a href="{{ route('atletas.show', $atleta->id) }}" class="btn btn-sm btn-outline-primary"
                                            target="_blank" rel="noopener noreferrer">
                                            Ver perfil
                                        </a>
                                        @if ($shortlistSelecionada && !in_array($atleta->id, $shortlistAtletaIds))
                                            <form method="POST"
                                                action="{{ route('olheiro.shortlists.atletas.store', [$shortlistSelecionada->id, $atleta->id]) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    Adicionar na shortlist
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-secondary">Nenhum atleta encontrado para o filtro atual.</div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $atletas->links('pagination::simple-bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flash = document.getElementById('olheiro-flash');
            if (!flash) {
                return;
            }
            setTimeout(function() {
                flash.style.transition = 'opacity 0.4s ease';
                flash.style.opacity = '0';
                setTimeout(function() {
                    flash.remove();
                }, 450);
            }, 2500);
        });
    </script>
@endsection

@push('styles')
    <style>
        .shortlists-grid {
            display: grid;
            gap: 8px;
            align-items: start;
            grid-auto-rows: 98px;
        }

        .shortlists-grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .shortlists-scroll {
            max-height: calc((98px * 2) + 8px);
            overflow-y: scroll;
            padding-right: 4px;
            scrollbar-width: thin;
        }

        .shortlists-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .shortlists-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.35);
            border-radius: 4px;
        }

        .shortlist-mini {
            border: 1px solid #d5d5d5;
            border-radius: 8px;
            background: #fff;
            padding: 4px;
            min-height: auto;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .shortlist-mini-active {
            border-color: #0d6efd;
        }

        .shortlist-mini-name {
            font-size: 0.78rem;
            line-height: 1.1;
            color: #1f2430;
            min-height: 24px;
            margin-bottom: 0;
            word-break: break-word;
            text-align: center;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .shortlist-mini-count {
            font-size: 0.62rem;
            margin-top: 6px;
            margin-bottom: 0;
            line-height: 1.1;
            text-align: center !important;
            display: flex;
            flex: 1;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        .shortlist-mini .btn {
            font-size: 0.72rem;
            padding: 3px 6px;
            line-height: 1.2;
        }

        .shortlist-mini-actions {
            display: flex;
            gap: 2px;
            align-items: center;
            margin-top: auto;
            width: 100%;
        }

        .shortlist-mini-actions .btn {
            flex: 1 1 0;
            padding: 0 4px;
            font-size: 0.62rem;
            height: 24px;
            line-height: 1;
            white-space: nowrap;
            width: 100%;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .shortlist-mini-actions form {
            flex: 1 1 0;
            margin: 0;
        }

        .favoritos-scroll {
            max-height: 132px;
            overflow-y: scroll;
            padding-right: 4px;
            scrollbar-width: thin;
        }

        .favoritos-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .favoritos-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.35);
            border-radius: 4px;
        }

        .shortlist-ativa-scroll {
            max-height: 126px;
            overflow-y: auto;
            overflow-x: hidden !important;
            scrollbar-width: thin;
        }

        .shortlist-ativa-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .shortlist-ativa-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.35);
            border-radius: 4px;
        }

        .shortlist-ativa-table {
            table-layout: fixed;
            width: 100%;
            margin-bottom: 0;
        }

        .shortlist-ativa-table th,
        .shortlist-ativa-table td {
            padding-top: 0.3rem;
            padding-bottom: 0.3rem;
            vertical-align: middle;
        }

        .shortlist-col-status {
            width: 120px;
        }

        .shortlist-col-acoes {
            width: 140px;
            white-space: nowrap;
        }

        .shortlist-status-select {
            width: 100%;
            min-width: 95px;
            max-width: 110px;
        }

        .shortlist-ativa-acoes {
            flex-wrap: nowrap;
        }

        .accordion-button {
            font-size: 0.92rem;
            font-weight: 600;
        }

        .accordion-button:not(.collapsed) {
            background-color: #eef3ff;
            color: #1f2430;
        }

        @media (max-width: 991.98px) {
            .shortlists-grid-3 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .shortlists-grid {
                grid-auto-rows: 98px;
            }

            .shortlists-grid.shortlists-scroll {
                max-height: calc((98px * 3) + (8px * 2));
            }
        }
    </style>
@endpush
