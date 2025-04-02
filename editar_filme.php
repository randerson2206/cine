<?php
include('views/header.php');
include(__DIR__ . '/config/database.php');

// Verifica se o ID foi passado na URL
if (!isset($_GET['id'])) {
    echo "<script>alert('ID do filme não especificado!'); window.location.href = 'admin.php';</script>";
    exit;
}

$id = $_GET['id'];

// Busca os dados do filme
$stmt = $pdo->prepare("SELECT * FROM filmes WHERE id = ?");
$stmt->execute([$id]);
$filme = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$filme) {
    echo "<script>alert('Filme não encontrado!'); window.location.href = 'admin.php';</script>";
    exit;
}
?>

<h1 class="text-center mb-4">Editar Filme</h1>

<div class="card shadow p-4">
    <form id="form-editar-filme">
        <input type="hidden" id="filme_id" name="id" value="<?php echo $filme['id']; ?>">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($filme['titulo']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="sinopse" class="form-label">Sinopse</label>
            <textarea class="form-control" id="sinopse" name="sinopse" required><?php echo htmlspecialchars($filme['sinopse']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="genero" class="form-label">Gênero</label>
            <select class="form-control" id="genero" name="genero_id" required>
                <?php
                $stmt_generos = $pdo->query("SELECT id, nome FROM generos");
                while ($genero = $stmt_generos->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($filme['genero_id'] == $genero['id']) ? 'selected' : '';
                    echo "<option value='{$genero['id']}' $selected>{$genero['nome']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="data_lancamento" class="form-label">Data de Lançamento</label>
            <input type="date" class="form-control" id="data_lancamento" name="data_lancamento" value="<?php echo $filme['data_lancamento']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="duracao" class="form-label">Duração (minutos)</label>
            <input type="number" class="form-control" id="duracao" name="duracao" value="<?php echo $filme['duracao']; ?>" min="1" required>
        </div>
        <div class="mb-3">
            <label for="trailer" class="form-label">Link do Trailer</label>
            <input type="url" class="form-control" id="trailer" name="trailer" value="<?php echo $filme['trailer']; ?>" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Atualizar</button>
    </form>
</div>

<script>
document.getElementById('form-editar-filme').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    fetch('api.php?tipo=atualizar_filme', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.href = 'admin.php';
    })
    .catch(error => console.error('Erro ao atualizar filme:', error));
});
</script>

<?php include('views/footer.php'); ?>
