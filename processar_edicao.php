<?php
include('config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $genero_id = $_POST['genero_id'];
    $data_lancamento = $_POST['data_lancamento'];
    $duracao = $_POST['duracao'];
    $trailer = $_POST['trailer'];

    // Verifica se uma nova imagem foi enviada
    if (!empty($_FILES['imagem']['name'])) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['imagem']['name']);
        move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile);
    } else {
        // Mantém a imagem existente
        $uploadFile = $_POST['imagem_atual'];
    }

    // Atualiza o banco de dados
    $stmt = $pdo->prepare("UPDATE filmes SET titulo=?, descricao=?, genero_id=?, data_lancamento=?, duracao=?, capa=?, trailer=? WHERE id=?");
    $stmt->execute([$titulo, $descricao, $genero_id, $data_lancamento, $duracao, $uploadFile, $trailer, $id]);

    // Redireciona de volta para a área administrativa
    header("Location: admin.php");
    exit();
}
?>
