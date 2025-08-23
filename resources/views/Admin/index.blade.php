@extends('layouts.app')

@section('content')
<div class="container">

  {{-- Mensagens --}}
  @if(session('success'))
    <div id="success-message" class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div id="error-message" class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  <h2 class="mb-4">Administração de Atletas</h2>

  {{-- wrapper comum para filtro + tabela --}}
  <div class="table-center mb-4">

    {{-- FILTRO --}}
    <form action="{{ route('admin.index') }}" method="GET" class="mb-3">
      
      <div class="mb-3">
        <label for="entidade" class="form-label">Instituição:</label>
        <select name="entidade"
                id="entidade"
                class="form-select"
                onchange="this.form.submit()">
          <option value="">Todas</option>
          @foreach($entidades as $ent)
            <option value="{{ $ent }}"
              {{ $filtroEntidade === $ent ? 'selected' : '' }}>
              {{ $ent }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- BOTOES lado a lado 50% cada --}}
      <div class="row gx-2">
        <div class="col-6">
            <a href="{{ route('admin.index') }}"
               class="btn btn-secondary w-100">
              Limpar filtro
            </a>
        </div>
        <div class="col-6">
          <a href="{{ route('admin.dashboard') }}"
             class="btn btn-custom w-100"
             style="background:#FF7209; color:white">
            Voltar
          </a>
        </div>
      </div>
    </form>

    {{-- TABELA --}}
    <table class="table table-striped">
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
            <td class="text-center">
              <a href="{{ route('atletas.edit', $a->id) }}"
                 class="btn btn-sm btn-primary"
                 style="background:#28365F; border:none">
                Editar
              </a>
              <form action="{{ route('atletas.destroy', $a->id) }}"
                    method="POST"
                    class="d-inline"
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
      {{ $atletas->links('pagination::bootstrap-5') }}
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

  /* garante que o select tenha 100% da largura desse wrapper */
  .table-center .form-select {
    width: 100%;
  }

  /* cor do header da tabela */
  .table thead th {
    background-color: #FF7209;
    color: #28365F;
  }
</style>
@endpush

@push('scripts')
<script>
  // oculta mensagens após 3s
  document.addEventListener('DOMContentLoaded', function() {
    ['success-message','error-message'].forEach(id => {
      const el = document.getElementById(id);
      if (el) setTimeout(() => el.style.display = 'none', 3000);
    });
  });
</script>
@endpush
