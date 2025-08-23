@extends('layouts.app')

@section('content')
<div class="container">

  <h2 class="mb-4">Administração de Atletas</h2>

  {{-- Filtro por Entidade --}}
  <form action="{{ route('admin.index') }}" method="GET" class="mb-4 d-flex align-items-center">
    <label for="entidade" class="me-2">Instituição:</label>
    <select name="entidade" id="entidade" class="form-select me-2" style="max-width: 300px"
            onchange="this.form.submit()">
      <option value="">Todas</option>
      @foreach($entidades as $ent)
        <option value="{{ $ent }}"
          {{ $filtroEntidade === $ent ? 'selected' : '' }}>
          {{ $ent }}
        </option>
      @endforeach
    </select>
    @if($filtroEntidade)
      <a href="{{ route('admin.index') }}"
         class="btn btn-secondary btn-sm">
        Limpar filtro
      </a>
    @endif
  </form>

  {{-- Tabela --}}
  <table class="table table-striped table-center">
    <thead>
      <tr class="column text-center">
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
          <td>
            <a href="{{ route('atletas.edit', $a->id) }}"
               class="btn btn-sm btn-primary" style="background:#28365F; border:none">
              Editar
            </a>
            <form action="{{ route('atletas.destroy', $a->id) }}"
                  method="POST" class="d-inline"
                  onsubmit="return confirm('Excluir este atleta?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger">Excluir</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center">Nenhum atleta encontrado.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Links de paginação --}}
  <div class="d-flex justify-content-center">
    {{ $atletas->links('pagination::bootstrap-5') }}
  </div>

</div>
@endsection

<style>
    
 /* Estilização da tabela mobile */
  .table thead th {
    background-color: #FF7209;
    color: #28365F;
  }

  @media (min-width: 992px) {  /* desktop a partir de 992px */
    .table-center {
      width: 80%;       /* ou qualquer % / px que desejar */
      margin: 0 auto;   /* centraliza horizontalmente */
    }

    .table-center thead th {
      background-color: #FF7209;
      color: #28365F;
    }

    .table-center tbody td {
      vertical-align: middle; /* centraliza verticalmente o conteúdo das células */
      text-align: center;      /* centraliza horizontalmente o conteúdo das células */
    }
  }
</style>