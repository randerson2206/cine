<?php include('views/header.php'); ?>

<!-- Container para garantir o layout responsivo -->
<div class="container">

    <!-- Título centralizado -->
    <h1 class="text-center mb-4">🎬 Lista de Filmes</h1>

    <!-- Filtro e pesquisa em um layout flexível -->
    <div class="d-flex flex-column flex-sm-row justify-content-between mb-4">
        <!-- Campo de pesquisa -->
        <input type="text" id="pesquisa" class="form-control mb-3 mb-sm-0 me-2" placeholder="🔎 Buscar filme..."> 

        <!-- Botão de pesquisa -->
        <button id="btn-pesquisar" class="btn btn-primary mb-3 mb-sm-0" onclick="filtrarFilmes()">Pesquisar</button>

        <!-- Filtro por gênero -->
        <select id="filtro-genero" class="form-select" onchange="filtrarFilmes()">
            <option value="">Todos os Gêneros</option>
        </select>
    </div>

    <!-- Lista de filmes ajustada para responsividade -->
    <div class="row" id="lista-filmes">
        <!-- Os filmes serão carregados dinamicamente aqui -->
    </div>
</div>

<script>
    let filmes = [];

    document.addEventListener('DOMContentLoaded', () => {
        carregarFilmes();
        carregarGeneros();
    });

    // Função para formatar a data de lançamento no formato DD/MM/YYYY
    function formatarData(data) {
        const [ano, mes, dia] = data.split('-');
        return `${dia}/${mes}/${ano}`; // Formato DD/MM/AAAA
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
            const dataFormatada = formatarData(filme.data_lancamento); // Formata a data
            html += `
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4" data-id="${filme.id}">
                    <div class="card h-100 shadow-sm">
                        <img src="${filme.capa}" class="card-img-top" alt="${filme.titulo}">
                        <div class="card-body">
                            <h5 class="card-title">${filme.titulo}</h5>
                            <p class="filme-info"><strong>Gênero:</strong> ${filme.genero}</p>
                            <p class="filme-info"><strong>Duração:</strong> ${filme.duracao} min</p>
                            <p class="filme-info"><strong>Data de Lançamento:</strong> ${dataFormatada}</p> <!-- Data formatada -->
                            <p class="card-text">${filme.sinopse}</p>
                            <a href="${filme.link}" target="_blank" class="btn btn-primary w-100">🎬 Assistir Trailer</a>
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

<?php include('views/footer.php'); ?>
