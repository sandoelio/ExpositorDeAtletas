@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Relatório de Importação</h3>

    <ul class="list-group mb-4">
        <li class="list-group-item">
            Criados: {{ $report['created'] }}
        </li>
        <li class="list-group-item">
            Ignorados: {{ $report['ignored'] }}
        </li>
    </ul>

    @if(!empty($ignoredDetails))
        <h5>Linhas Ignoradas</h5>
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>Linha</th>
                    <th>Nome</th>
                    <th>Instituição</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ignoredDetails as $item)
                    <tr>
                        <td>{{ $item['row'] }}</td>
                        <td>{{ $item['nome'] }}</td>
                        <td>{{ $item['entidade'] }}</td>
                        <td>{{ $item['reason'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('atletas.import.form') }}" class="btn btn-primary">
        Importar Outro Arquivo
    </a>
    <a href="{{ route('atletas.index') }}" class="btn btn-secondary">
        Voltar à Lista
    </a>
</div>
@endsection
