@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="mb-3">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                ← Voltar para a Home
            </a>
        </div>

        {{-- Filtros --}}
        <form class="mb-4" onsubmit="event.preventDefault(); buscarAtletas();">
            <div class="row">
                <div class="col-md-2">
                    <label>Idade Mínima:</label>
                    <input type="number" id="idade_min" class="form-control">
                </div>
                <div class="col-md-2">
                    <label>Idade Máxima:</label>
                    <input type="number" id="idade_max" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Posição:</label>
                    <select id="posicao_jogo" class="form-control">
                        <option value="">Todas</option>
                        @foreach ($posicoes as $posicao)
                            <option value="{{ $posicao->posicao_jogo }}">{{ $posicao->posicao_jogo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Cidade:</label>
                    <select id="cidade" class="form-control">
                        <option value="">Todas</option>
                        @foreach ($cidades as $cidade)
                            <option value="{{ $cidade->cidade }}">{{ $cidade->cidade }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Entidade:</label>
                    <select id="entidade" class="form-control">
                        <option value="">Todas</option>
                        @foreach ($entidades as $entidade)
                            <option value="{{ $entidade->entidade }}">{{ $entidade->entidade }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="text-center mt-3">
                <button type="button" class="btn btn-primary" onclick="buscarAtletas()">Filtrar</button>
                <button type="button" class="btn btn-secondary" onclick="limparFiltros()">Limpar</button>
            </div>
        </form>

        {{-- Aqui será exibido o resultado --}}
        <div id="resultado-atletas" class="mt-4"></div>

        {{-- Contador de atletas sem filtro --}}
        <p id="contagem-original" class="pagination-text">
            Mostrando {{ $atletas->lastItem() }} de {{ $atletas->total() }} atletas
        </p>

        {{-- Contador de atletas filtrados (oculto inicialmente) --}}
        <p id="filtro-contagem" class="pagination-text" style="display: none;"></p>

        {{-- Lista original (oculta após filtrar) --}}
        <div id="lista-atletas" class="row row-cols-1 row-cols-md-3 g-3">
            @foreach ($atletas as $atleta)
                <div class="col mb-4 h-100">
                    <div class="card-flip" onclick="this.classList.toggle('flipped')">
                        <div class="card front card-body text-center">
                            <img src="{{ !empty($atleta->imagem_base64) ? 'data:image/png;base64,' . $atleta->imagem_base64 : asset('img/slogan.png') }}"
                                class="avatar-img rounded-circle mb-3" width="100" height="100" alt="Avatar">
                            <h5 class="card-title">{{ $atleta->nome_completo }}</h5>
                            <p class="card-text">Idade: {{ $atleta->idade }}</p>
                            <button class="btn btn-primary">Clique para ver mais</button>
                        </div>
                        <div class="card back card-body text-center d-flex flex-column">
                        <h5 class="card-title">{{ $atleta->nome_completo }}</h5>
                        <div class="scrollable-info flex-grow-1 overflow-auto">
                            <p><strong>Cidade:</strong> {{ $atleta->cidade }}</p>
                            <p><strong>Posição:</strong> {{ $atleta->posicao_jogo }}</p>
                            <p><strong>Entidade:</strong> {{ $atleta->entidade }}</p>
                            <p><strong>Altura:</strong> {{ $atleta->altura }}</p>
                            <p><strong>Peso:</strong> {{ $atleta->peso }}</p>
                            <p><strong>Contato:</strong> {{ $atleta->contato }}</p>
                            <p><strong>Resumo:</strong> {!! nl2br(e($atleta->resumo)) !!}</p>
                        </div>
                        <button class="btn btn-primary mt-2">Voltar</button>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Cards filtrados via JS -->
        <div id="filtro-resultados" class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3" style="display: none;"></div>

        <div id="paginacao-filtrada" class="d-flex justify-content-center mt-4"></div>

        {{-- Paginação --}}
        <div class="d-flex justify-content-center mt-1">
            {{ $atletas->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
        </div>

    </div>
    <script>
        let atletasFiltrados = [];
        let paginaAtual = 1;
        const itensPorPagina = 6;

        // Função para buscar atletas com os filtros aplicados
        function buscarAtletas() {
            const idadeMin = document.getElementById('idade_min');
            const idadeMax = document.getElementById('idade_max');
            const posicao = document.getElementById('posicao_jogo');
            const cidade = document.getElementById('cidade');
            const entidade = document.getElementById('entidade');

            let url = '/atletas/buscar?';

            if (entidade.value) url += 'entidade=' + entidade.value + '&';
            if (idadeMin.value) url += 'idade_min=' + idadeMin.value + '&';
            if (idadeMax.value) url += 'idade_max=' + idadeMax.value + '&';
            if (posicao.value) url += 'posicao_jogo=' + posicao.value + '&';
            if (cidade.value) url += 'cidade=' + cidade.value;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    atletasFiltrados = data;
                    paginaAtual = 1;
                    renderPaginaFiltrada();

                    // Desabilita os filtros após filtrar
                    idadeMin.disabled = true;
                    idadeMax.disabled = true;
                    posicao.disabled = true;
                    cidade.disabled = true;
                    entidade.disabled = true;

                    // Esconde lista original
                    document.getElementById('lista-atletas').style.display = 'none';
                    document.getElementById('contagem-original').style.display = 'none';
                })
                .catch(error => console.error("Erro ao buscar atletas:", error));
        }

        // Função para exibir atletas filtrados
        function exibirAtletas(atletas) {
            const listaOriginal = document.getElementById('lista-atletas');
            const container = document.getElementById('filtro-resultados');

            listaOriginal.style.display = 'none';
            container.style.display = 'flex';
            container.innerHTML = '';

            if (atletas.length === 0) {
                container.innerHTML =
                    '<p class="text-center text-white">Nenhum atleta encontrado com os filtros aplicados.</p>';
                return;
            }

            atletas.forEach(atleta => {
                container.innerHTML += `
                <div class="col-md-4 mb-4">
                    <div class="card-flip" onclick="this.classList.toggle('flipped')">
                        <div class="card front card-body text-center">
                            <img src="${atleta.imagem_base64 ? 'data:image/png;base64,' + atleta.imagem_base64 : '/img/avatar.png'}"
                                    class="avatar-img rounded-circle mb-3" width="100" height="100">
                            <h5 class="card-title">${atleta.nome_completo}</h5>
                            <p class="card-text">Idade: ${atleta.idade}</p>
                            <button class="btn btn-primary">Clique para ver mais</button>
                        </div>
                        <div class="card back card-body text-start d-flex flex-column">
                            <h5 class="card-title">${atleta.nome_completo}</h5>
                            <div class="scrollable-info flex-grow-1 overflow-auto">
                                <p><strong>Cidade:</strong> ${atleta.cidade}</p>
                                <p><strong>Posição:</strong> ${atleta.posicao_jogo}</p>
                                <p><strong>Entidade:</strong> ${atleta.entidade}</p>
                                <p><strong>Altura:</strong> ${atleta.altura}</p>
                                <p><strong>Peso:</strong> ${atleta.peso}</p>
                                <p><strong>Contato:</strong> ${atleta.contato}</p>
                               <p><strong>Resumo:</strong> ${atleta.resumo.replace(/\n/g, '<br>')}</p>
                            </div>
                            <button class="btn btn-primary mt-2">Voltar</button>
                        </div>
                    </div>
                </div>`;
            });
        }

        // Função para limpar os filtros
        function limparFiltros() {
            const idadeMin = document.getElementById('idade_min');
            const idadeMax = document.getElementById('idade_max');
            const posicao = document.getElementById('posicao_jogo');
            const cidade = document.getElementById('cidade');
            const entidade = document.getElementById('entidade');

            idadeMin.value = '';
            idadeMax.value = '';
            posicao.value = '';
            cidade.value = '';
            entidade.value = '';

            idadeMin.disabled = false;
            idadeMax.disabled = false;
            posicao.disabled = false;
            cidade.disabled = false;
            entidade.disabled = false;

            document.getElementById('filtro-resultados').style.display = 'none';
            document.getElementById('filtro-resultados').innerHTML = '';
            document.getElementById('paginacao-filtrada').innerHTML = '';
            document.getElementById('filtro-contagem').style.display = 'none';

            document.getElementById('lista-atletas').style.display = 'flex';
            document.getElementById('contagem-original').style.display = 'block';

            atualizarFiltrosAtivos();
        }

        // Oculta contagem original
        document.getElementById('contagem-original').style.display = 'none';

        // Exibe contagem filtrada
        function renderPaginaFiltrada() {

            const container = document.getElementById('filtro-resultados');
            const listaOriginal = document.getElementById('lista-atletas');
            const paginacao = document.getElementById('paginacao-filtrada');

            // Esconde lista original e sua contagem
            listaOriginal.style.display = 'none';
            document.getElementById('contagem-original').style.display = 'none';

            // Mostra contagem do filtro
            const contagemFiltro = document.getElementById('filtro-contagem');
            contagemFiltro.style.display = 'block';

            container.style.display = 'flex';
            container.innerHTML = '';
            paginacao.innerHTML = '';

            const total = atletasFiltrados.length;
            const totalPaginas = Math.ceil(total / itensPorPagina);
            const inicio = (paginaAtual - 1) * itensPorPagina;
            const fim = inicio + itensPorPagina;

            const atletasPagina = atletasFiltrados.slice(inicio, fim);

            if (atletasPagina.length === 0) {
                container.innerHTML =
                    '<p class="text-center text-white">Nenhum atleta encontrado com os filtros aplicados.</p>';
                contagemFiltro.innerText = 'Nenhum atleta encontrado.';
                return;
            }

            // Atualiza contagem dos atletas filtrados
            const inicioExibicao = inicio + 1;
            const fimExibicao = Math.min(fim, total);
            contagemFiltro.innerText = `Mostrando ${fimExibicao} de ${total} atletas encontrados com os filtros aplicados.`;

            // Renderiza os atletas filtrados
            atletasPagina.forEach(atleta => {
                container.innerHTML += `
                <div class="col mb-4">
                    <div class="card-flip" onclick="this.classList.toggle('flipped')">
                        <div class="card front card-body">
                            <img src="${atleta.imagem_base64 ? 'data:image/png;base64,' + atleta.imagem_base64 : '/img/avatar.png'}"
                                class="avatar-img rounded-circle mb-3" width="100" height="100">
                            <h5 class="card-title">${atleta.nome_completo}</h5>
                            <p class="card-text">Idade: ${atleta.idade}</p>
                            <button class="btn btn-primary">Clique para ver mais</button>
                        </div>
                        <div class="card back card-body text-start">
                        <h5 class="card-title">${atleta.nome_completo}</h5>
                        <div class="scrollable-info flex-grow-1 overflow-auto d-flex flex-column">
                            <p><strong>Cidade:</strong> ${atleta.cidade}</p>
                            <p><strong>Posição:</strong> ${atleta.posicao_jogo}</p>
                            <p><strong>Entidade:</strong> ${atleta.entidade}</p>
                            <p><strong>Altura:</strong> ${atleta.altura}</p>
                            <p><strong>Peso:</strong> ${atleta.peso}</p>
                            <p><strong>Contato:</strong> ${atleta.contato}</p>
                            <p><strong>Resumo:</strong> ${atleta.resumo.replace(/\n/g, '<br>')}</p>
                        </div>
                        <button class="btn btn-primary mt-2">Voltar</button>
                    </div>
                </div>`;
            });

            for (let i = 1; i <= totalPaginas; i++) {
                paginacao.innerHTML += `
                <button class="btn btn-sm ${i === paginaAtual ? 'btn-primary' : 'btn-secondary'} mx-1"
                        onclick="mudarPagina(${i})">${i}</button>`;
            }
        }

        // Função para mudar a página
        function mudarPagina(numero) {
            paginaAtual = numero;
            renderPaginaFiltrada();
        }

        // Desabilita os campos de filtro ao carregar a página
        document.addEventListener('DOMContentLoaded', function () {
            const filtros = ['idade_min', 'idade_max', 'posicao_jogo', 'cidade', 'entidade'];

            filtros.forEach(id => {
                const campo = document.getElementById(id);
                campo.addEventListener('input', () => {
                    // Verifica se o filtro idade está ativo
                    const idadeMinVal = document.getElementById('idade_min').value.trim();
                    const idadeMaxVal = document.getElementById('idade_max').value.trim();
                    const idadeAtiva = idadeMinVal !== '' || idadeMaxVal !== '';

                    filtros.forEach(outroId => {
                        const outroCampo = document.getElementById(outroId);

                        // Se idade está ativa, só desativa os campos que não fazem parte dela
                        if (id === 'idade_min' || id === 'idade_max') {
                            if (outroId !== 'idade_min' && outroId !== 'idade_max') {
                                outroCampo.disabled = idadeAtiva;
                            } else {
                                outroCampo.disabled = false;
                            }
                        } else {
                            // Caso esteja usando outro filtro, desativa todos os demais
                            outroCampo.disabled = (id !== outroId) && campo.value.trim() !== '';
                        }
                    });

                    // Se todos os campos estiverem vazios, reativa todos
                    const algumPreenchido = filtros.some(fid => document.getElementById(fid).value.trim() !== '');
                    if (!algumPreenchido) {
                        filtros.forEach(fid => document.getElementById(fid).disabled = false);
                    }
                });
            });
        });

        function atualizarFiltrosAtivos() {
            const idadeMin = document.getElementById('idade_min');
            const idadeMax = document.getElementById('idade_max');
            const posicao = document.getElementById('posicao_jogo');
            const cidade = document.getElementById('cidade');
            const entidade = document.getElementById('entidade');

            const idadePreenchida = idadeMin.value || idadeMax.value;
            const outrosPreenchidos = posicao.value || cidade.value || entidade.value;

            if (idadePreenchida) {
                posicao.disabled = true;
                cidade.disabled = true;
                entidade.disabled = true;
                idadeMin.disabled = false;
                idadeMax.disabled = false;
            } else if (outrosPreenchidos) {
                idadeMin.disabled = true;
                idadeMax.disabled = true;
                posicao.disabled = false;
                cidade.disabled = false;
                entidade.disabled = false;
            } else {
                // Se nada estiver preenchido, tudo fica ativo
                idadeMin.disabled = false;
                idadeMax.disabled = false;
                posicao.disabled = false;
                cidade.disabled = false;
                entidade.disabled = false;
            }
        }


    </script>
@endsection
