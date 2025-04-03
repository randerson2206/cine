<?php include('views/header.php'); ?>

<!-- Container principal -->
<div class="container mt-4">
    <!-- Banner estilo Netflix -->
    <div class="banner text-center text-white py-5">
        <h1 class="fw-bold">🎬 Descubra os Melhores Filmes</h1>
        <p class="lead">Explore, assista trailers e escolha seu próximo filme favorito!</p>
    </div>
    
    <!-- Filtro e pesquisa com layout flexível -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-4">
            <input type="text" id="pesquisa" class="form-control" placeholder="🔎 Buscar filme...">
        </div>
        <div class="col-md-3">
            <select id="filtro-genero" class="form-select" onchange="filtrarFilmes()">
                <option value="">Todos os Gêneros</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="btn-pesquisar" class="btn btn-danger w-100" onclick="filtrarFilmes()">Pesquisar</button>
        </div>
    </div>

    <!-- Lista de filmes com informações visíveis -->
    <div class="row row-cols-1 row-cols-md-2 g-4" id="lista-filmes">
        <!-- Filmes serão carregados dinamicamente aqui -->
    </div>
</div>

<script>
    let filmes = [];

    document.addEventListener('DOMContentLoaded', () => {
        carregarFilmes();
        carregarGeneros();
    });

    function formatarData(data) {
        const [ano, mes, dia] = data.split('-');
        return `${dia}/${mes}/${ano}`;
    }

    function carregarFilmes() {
        fetch('api.php?tipo=filme')
            .then(response => response.json())
            .then(data => {
                filmes = data;
                renderizarFilmes(filmes);
            })
            .catch(error => console.error('Erro ao carregar filmes:', error));
    }

    function renderizarFilmes(lista) {
        let html = '';
        lista.forEach(filme => {
            const dataFormatada = formatarData(filme.data_lancamento);
            html += `
                <div class="col">
                    <div class="card bg-dark text-white shadow-sm border-0 d-flex flex-row align-items-center p-3">
                        <img src="${filme.capa}" class="card-img-left" alt="${filme.titulo}" style="width: 150px; height: auto; object-fit: cover; border-radius: 10px;">
                        <div class="card-body">
                            <h5 class="card-title">${filme.titulo}</h5>
                            <p><strong>Gênero:</strong> ${filme.genero}</p>
                            <p><strong>Duração:</strong> ${filme.duracao} min</p>
                            <p><strong>Data de Lançamento:</strong> ${dataFormatada}</p>
                            <p><strong>Sinopse:</strong> ${filme.sinopse}</p>
                            <a href="${filme.link}" target="_blank" class="btn btn-danger">🎬 Assistir Trailer</a>
                        </div>
                    </div>
                </div>
            `;
        });
        document.getElementById('lista-filmes').innerHTML = html;
    }

    function carregarGeneros() {
        fetch('api.php?tipo=genero')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('filtro-genero');
                data.forEach(genero => {
                    const option = document.createElement('option');
                    option.value = genero.nome.toLowerCase();
                    option.innerText = genero.nome;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar gêneros:', error));
    }

    function filtrarFilmes() {
        const termoPesquisa = document.getElementById('pesquisa').value.toLowerCase();
        const generoSelecionado = document.getElementById('filtro-genero').value;

        const filmesFiltrados = filmes.filter(filme => {
            const correspondeNome = filme.titulo.toLowerCase().includes(termoPesquisa);
            const correspondeGenero = !generoSelecionado || filme.genero.toLowerCase() === generoSelecionado;
            return correspondeNome && correspondeGenero;
        });

        renderizarFilmes(filmesFiltrados);
    }
</script>

<style>
    body {
        background-color: #141414;
        color: white;
    }
    .banner {
        background: linear-gradient(to right, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.3)), url('banner.jpg') center/cover;
        border-radius: 10px;
    }
    .card {
        display: flex;
        flex-direction: row;
        align-items: center;
        transition: transform 0.3s ease-in-out;
        height: auto;
    }
    .card:hover {
        transform: scale(1.02);
    }
    .card-body {
        flex: 1;
    }
    .card-body p {
        font-size: 14px;
    }
    .card-img-left {
        flex-shrink: 0;
        width: 150px;
        height: auto;
        object-fit: cover;
        border-radius: 10px;
    }
</style>

<?php include('views/footer.php'); ?>
