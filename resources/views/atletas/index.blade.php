@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center my-4">Lista de Atletas</h2>
    
    <a href="{{ route('atletas.store') }}" class="btn btn-success mb-4">Cadastrar Atleta</a>

    <div class="row row-cols-2 row-cols-md-4 g-3"> <!-- Ajuste para exibir mais atletas por linha -->
        @foreach($atletas as $atleta)
        <div class="col">
            <div class="card">
                <img src="{{ $atleta->imagem_base64 ? 'data:image/png;base64,'.$atleta->imagem_base64 : asset('img/avatar.png') }}" class="avatar-img" alt="Imagem do atleta">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $atleta->nome_completo }}</h5>
                    <p class="card-text">Idade: {{ $atleta->idade }}</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAtleta{{$atleta->id}}">
                        Mais...
                    </button>
                </div>
            </div>
            
        </div>  

        <!-- Modal compacto -->
        <div class="modal fade" id="modalAtleta{{$atleta->id}}" tabindex="-1" aria-labelledby="modalLabel{{$atleta->id}}" aria-hidden="true">
            <div class="modal-dialog modal-sm"> <!-- Tamanho menor para modais -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $atleta->nome_completo }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Cidade:</strong> {{ $atleta->cidade }}</p>
                        <p><strong>Posição:</strong> {{ $atleta->posicao_jogo }}</p>
                        <p><strong>Entidade:</strong> {{ $atleta->entidade }}</p>
                        <p><strong>Contato:</strong> {{ $atleta->contato }}</p>
                        <p><strong>CPF:</strong> {{ $atleta->cpf }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $atletas->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
    </div>
    
    <!-- Texto informativo abaixo da paginação -->
    <p class="pagination-text">Mostrando {{ $atletas->firstItem() }} a {{ $atletas->lastItem() }} de {{ $atletas->total() }} resultados</p>
    
</div>
@endsection
