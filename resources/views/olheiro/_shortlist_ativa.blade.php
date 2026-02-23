@php
    $semMargemTopo = $semMargemTopo ?? false;
@endphp
<div class="card {{ $semMargemTopo ? '' : 'mt-3' }}">
    <div class="card-body">
        @php
            $usarScrollShortlistAtiva = $shortlistSelecionada->itens->count() > 2;
            $bulkFormId = 'shortlist-bulk-' . $shortlistSelecionada->id;
        @endphp
        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
            <h6 class="mb-0">
                Shortlist ativa: {{ $shortlistSelecionada->nome }}
                <small class="text-muted">({{ $shortlistSelecionada->itens->count() }} atleta(s))</small>
            </h6>
            @if ($shortlistSelecionada->itens->isNotEmpty())
                <button type="submit" form="{{ $bulkFormId }}" class="btn btn-sm btn-primary">
                    Salvar
                </button>
            @endif
        </div>

        <form id="{{ $bulkFormId }}" method="POST"
            action="{{ route('olheiro.shortlists.itens.update', $shortlistSelecionada->id) }}" class="d-none">
            @csrf
            @method('PATCH')
        </form>

        <div class="table-responsive {{ $usarScrollShortlistAtiva ? 'shortlist-ativa-scroll' : '' }}">
            <table class="table table-sm align-middle mb-0 shortlist-ativa-table">
                <thead>
                    <tr>
                        <th>Atleta</th>
                        <th>Status</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shortlistSelecionada->itens as $item)
                        <tr>
                            <td>{{ $item->atleta->nome_completo ?? 'Atleta removido' }}</td>
                            <td class="shortlist-col-status">
                                <select name="status[{{ $item->atleta_id }}]" form="{{ $bulkFormId }}"
                                    class="form-select form-select-sm shortlist-status-select">
                                    @foreach ($statusOptions as $key => $label)
                                        <option value="{{ $key }}" {{ $item->status === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-end shortlist-col-acoes">
                                <form method="POST"
                                    action="{{ route('olheiro.shortlists.atletas.destroy', [$shortlistSelecionada->id, $item->atleta_id]) }}"
                                    class="m-0 d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        style="min-width: 62px; height: 28px; padding: 2px 8px; line-height: 1;">
                                        Remover
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Nenhum atleta na shortlist ativa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
