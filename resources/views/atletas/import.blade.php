@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Importar Atletas em Massa</h3>

    <div class="mb-4">
        <a href="{{ route('atletas.template') }}"
           class="btn btn-success">
            Baixar Template XLS
        </a>
    </div>

    <form action="{{ route('atletas.import') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Arquivo CSV / XLS(X)</label>
            <input type="file"
                   name="file"
                   id="file"
                   accept=".csv,.xls,.xlsx"
                   class="form-control"
                   required>
        </div>
        <button class="btn btn-primary">Importar</button>
        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
