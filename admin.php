<?php include('views/header.php'); ?> 

<h1 class="text-center mb-4">Área Administrativa</h1>

<div class="card shadow p-4">
    <form id="form-filme" enctype="multipart/form-data" method="POST" action="api.php">
        <input type="hidden" id="filme_id" name="filme_id">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="sinopse" class="form-label">Sinopse</label>
            <textarea class="form-control" id="sinopse" name="sinopse" required></textarea>
        </div>
        <div class="mb-3">
            <label for="genero" class="form-label">Gênero</label>
            <select class="form-control" id="genero" name="genero_id" required>
                <option value="">Selecione um gênero</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="data_lancamento" class="form-label">Data de Lançamento</label>
            <input type="date" class="form-control" id="data_lancamento" name="data_lancamento" required>
        </div>
        <div class="mb-3">
            <label for="duracao" class="form-label">Duração (minutos)</label>
            <input type="number" class="form-control" id="duracao" name="duracao" min="1" required>
        </div>
        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem</label>
            <input type="file" class="form-control" id="imagem" name="imagem" accept=".jpg, .jpeg, .png" required>
            <small class="text-muted">Formatos permitidos: .jpg, .jpeg, .png</small>
        </div>
        <div class="mb-3">
            <label for="trailer" class="form-label">Link do Trailer</label>
            <input type="url" class="form-control" id="trailer" name="trailer" required placeholder="https://example.com/trailer">
        </div>
        <button type="submit" class="btn btn-success w-100" id="btn-submit">Salvar</button>
    </form>
</div>

<h2 class="text-center mt-5">Filmes Cadastrados</h2>
<div class="table-responsive">
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Título</th>
                <th>Gênero</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="lista-filmes-admin"></tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        carregarFilmes();
        carregarGeneros();
        document.getElementById('form-filme').addEventListener('submit', salvarFilme);
    });

    function carregarFilmes() {
        fetch('api.php?tipo=filme')
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(filme => {
                    html += `
                        <tr>
                            <td>${filme.titulo}</td>
                            <td>${filme.genero}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editarFilme(${filme.id})">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="excluirFilme(${filme.id})">Excluir</button>
                            </td>
                        </tr>`;
                });
                document.getElementById('lista-filmes-admin').innerHTML = html;
            })
            .catch(error => console.error('Erro ao carregar filmes:', error));
    }

    function carregarGeneros() {
        fetch('api.php?tipo=genero')
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Selecione um gênero</option>';
                data.forEach(genero => {
                    options += `<option value="${genero.id}">${genero.nome}</option>`;
                });
                document.getElementById('genero').innerHTML = options;
            })
            .catch(error => console.error('Erro ao carregar gêneros:', error));
    }

    function salvarFilme(event) {
        event.preventDefault();
        const formData = new FormData(document.getElementById('form-filme'));
        fetch('api.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                document.getElementById('form-filme').reset();
                carregarFilmes();
            }
        })
        .catch(error => console.error('Erro ao salvar filme:', error));
    }

    function editarFilme(id) {
        fetch(`api.php?tipo=filme&id=${id}`)
            .then(response => response.json())
            .then(filme => {
                document.getElementById('filme_id').value = filme.id;
                document.getElementById('titulo').value = filme.titulo;
                document.getElementById('sinopse').value = filme.sinopse;
                document.getElementById('genero').value = filme.genero_id;
                document.getElementById('data_lancamento').value = filme.data_lancamento;
                document.getElementById('duracao').value = filme.duracao;
                document.getElementById('trailer').value = filme.trailer;
            })
            .catch(error => console.error('Erro ao carregar filme:', error));
    }

    function excluirFilme(id) {
        if (confirm('Tem certeza que deseja excluir este filme?')) {
            fetch(`api.php?id=${id}`, { method: 'DELETE' })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    carregarFilmes();
                })
                .catch(error => console.error('Erro ao excluir filme:', error));
        }
    }
</script>

<?php include('views/footer.php'); ?>