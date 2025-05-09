@extends('layouts.app')

@section('content')
<div class="container">

{{-- Formulário de filtro --}}

<form id="filtro-form" action="#" method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <label for="idade_min">Idade Mínima:</label>
            <input type="number" name="idade_min" id="idade_min" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="idade_max">Idade Máxima:</label>
            <input type="number" name="idade_max" id="idade_max" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="posicao_jogo">Posição:</label>
            <select name="posicao_jogo" id="posicao_jogo" class="form-control">
                <option value="">Todas</option>
                <option value="Armador">Armador</option>
                <option value="Ala">Ala</option>
                <option value="Ala-pivo">Ala-pivo</option>
                <option value="Ala-armador">Ala-armador</option>
                <option value="Pivo">Pivo</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" class="form-control">
        </div>
    </div>
    <div class="text-center mt-3">
        <button type="button" class="btn btn-primary" onclick="buscarAtletas()">Filtrar</button>
        <button type="button" class="btn btn-secondary" onclick="limparFiltros()">Limpar</button>
    </div>
</form>

<div class="row justify-content-center">
    @foreach ($atletas as $atleta)
        <div class="col-md-4 mb-4">
            <div class="card-flip" onclick="this.classList.toggle('flipped')">
                <div class="card front card-body text-center">
                    <img src="{{ $atleta->imagem_base64 ? 'data:image/png;base64,'.$atleta->imagem_base64 : asset('img/avatar.png') }}" class="avatar-img" alt="Imagem do atleta" 
                        class="rounded-circle mb-3" width="100" height="100" alt="Avatar">
                    <h5 class="card-title">{{ $atleta->nome_completo }}</h5>
                    <p class="card-text">Idade: {{ $atleta->idade }}</p>
                    <button class="btn btn-primary">
                    Clique para ver mais
                    </button>
                </div>
                <div class="card back card-body text-center">
                    <h5 class="card-title">{{ $atleta->nome_completo }}</h5>
                    <p><strong>Cidade:</strong> {{ $atleta->cidade }}</p>
                    <p><strong>Posição:</strong> {{ $atleta->posicao_jogo }}</p>
                    <p><strong>Entidade:</strong> {{ $atleta->entidade }}</p>
                    <p><strong>Contato:</strong> {{ $atleta->contato }}</p>
                    <p><strong>CPF:</strong> {{ $atleta->cpf }}</p>
                    <button class="btn btn-primary">
                    Voltar
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    <div id="filtro-resultados" class="row row-cols-2 row-cols-md-4 g-1"> 
        <!-- Aqui os resultados filtrados serão exibidos -->
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $atletas->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
    </div>
        <!-- Texto informativo abaixo da paginação -->
        <p class="pagination-text">Mostrando {{ $atletas->firstItem() }} a {{ $atletas->lastItem() }} de {{ $atletas->total() }} resultados</p>  
    </div>
</div>
@endsection

<script>

// Função para buscar atletas com base nos filtros aplicados

function buscarAtletas() {
    let idadeMin = document.getElementById('idade_min').value;
    let idadeMax = document.getElementById('idade_max').value;
    let posicao = document.getElementById('posicao_jogo').value;
    let cidade = document.getElementById('cidade').value;
 
    // Monta a URL com os parâmetros do filtro
    let url = `/atletas/buscar?idade_min=${idadeMin}&idade_max=${idadeMax}&posicao_jogo=${posicao}&cidade=${cidade}`;

    // Faz a requisição para a API
    fetch(url)
        .then(response => response.json())
        .then(data => {
            exibirAtletas(data); // Atualiza a lista com os atletas filtrados
        })
        .catch(error => console.error('Erro ao buscar atletas:', error));
}

function limparFiltros() {
    document.getElementById('idade_min').value = '';
    document.getElementById('idade_max').value = '';
    document.getElementById('posicao_jogo').value = '';
    document.getElementById('cidade').value = '';
    buscarAtletas(); // Atualiza a lista removendo os filtros
}

function exibirAtletas(atletas) {
    let listaOriginal = document.getElementById('lista-atletas');
    listaOriginal.style.display = 'none'; // Esconde a lista original

    let container = document.getElementById('filtro-resultados');
    container.innerHTML = ''; // Limpa a área dos filtros

    if (atletas.length === 0) {
        container.innerHTML = '<p class="text-center text-white">Nenhum atleta encontrado com os filtros aplicados.</p>';
        return;
    }

    atletas.forEach(atleta => {
        container.innerHTML += `
            <div class="card">
                <img src="${atleta.imagem_base64 ? 'data:image/png;base64,' + atleta.imagem_base64 : '/img/avatar.png'}" class="avatar-img">
                <div class="card-body text-center">
                    <h5 class="card-title">${atleta.nome_completo}</h5>
                    <p class="card-text">Idade: ${atleta.idade}</p>
                    <p class="card-text">Cidade: ${atleta.cidade}</p>
                    <button class="btn btn-primary abrir-modal" data-id="${atleta.id}" 
                        data-nome="${atleta.nome_completo}" data-cidade="${atleta.cidade}" 
                        data-posicao="${atleta.posicao_jogo}" data-entidade="${atleta.entidade}" 
                        data-contato="${atleta.contato}" data-cpf="${atleta.cpf}">
                        Mais...
                    </button>
                </div>
            </div>
        `;
    });
}
</script>