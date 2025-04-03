<?php
// Inclui a conexão com o banco de dados
include('config/db.php');

// Habilitar CORS para permitir requisições de qualquer origem
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Capturar método via _method (para permitir DELETE via POST)
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = $_POST['_method'];
}

// Buscar filmes
if (isset($_GET['tipo']) && $_GET['tipo'] === 'filme') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $db->prepare("SELECT * FROM filmes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $filme = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($filme) {
            $filme['capa'] = !empty($filme['capa']) ? 'uploads/' . $filme['capa'] : 'uploads/default.png';
            echo json_encode($filme);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Filme não encontrado.']);
        }
    } else {
        $query = "SELECT f.*, g.nome AS genero FROM filmes f
                  LEFT JOIN generos g ON f.genero_id = g.id";
        $result = $db->query($query);

        $filmes = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach ($filmes as &$filme) {
            $filme['capa'] = !empty($filme['capa']) ? 'uploads/' . $filme['capa'] : 'uploads/default.png';
        }

        if (!empty($filmes)) {
            echo json_encode($filmes);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nenhum filme encontrado.']);
        }
    }
    exit;
}

// Buscar gêneros
if (isset($_GET['tipo']) && $_GET['tipo'] === 'genero') {
    $query = "SELECT id, nome FROM generos";
    $result = $db->query($query);
    $generos = $result->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(!empty($generos) ? $generos : ['status' => 'error', 'message' => 'Nenhum gênero encontrado.']);
    exit;
}

// Salvar ou atualizar filme
if ($method === 'POST') {
    try {
        $filme_id = $_POST['filme_id'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        $sinopse = $_POST['sinopse'] ?? '';
        $genero_id = $_POST['genero_id'] ?? '';
        $link = $_POST['trailer'] ?? '';
        $data_lancamento = $_POST['data_lancamento'] ?? '';
        $duracao = $_POST['duracao'] ?? '';
        $capa = '';

        // Upload de imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = uniqid() . '.' . $extensao;
            $destino = 'uploads/' . $nomeArquivo;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                $capa = $nomeArquivo;
            }
        }

        if (!empty($filme_id)) {
            // Atualizar filme
            $stmt = $db->prepare("UPDATE filmes SET titulo = :titulo, sinopse = :sinopse, genero_id = :genero_id, link = :link, data_lancamento = :data_lancamento, duracao = :duracao" . 
                ($capa ? ", capa = :capa" : "") . " WHERE id = :id");

            $params = [
                ':id' => $filme_id,
                ':titulo' => $titulo,
                ':sinopse' => $sinopse,
                ':genero_id' => $genero_id,
                ':link' => $link,
                ':data_lancamento' => $data_lancamento,
                ':duracao' => $duracao
            ];

            if ($capa) {
                $params[':capa'] = $capa;
            }

            $stmt->execute($params);
            echo json_encode(['status' => 'success', 'message' => 'Filme atualizado com sucesso!']);
        } else {
            // Inserir novo filme
            $stmt = $db->prepare("INSERT INTO filmes (titulo, sinopse, capa, link, genero_id, data_lancamento, duracao) VALUES (:titulo, :sinopse, :capa, :link, :genero_id, :data_lancamento, :duracao)");
            $stmt->execute([
                ':titulo' => $titulo,
                ':sinopse' => $sinopse,
                ':capa' => $capa,
                ':link' => $link,
                ':genero_id' => $genero_id,
                ':data_lancamento' => $data_lancamento,
                ':duracao' => $duracao
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Filme salvo com sucesso!']);
        }
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

// Excluir filme
if ($method === 'DELETE' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $db->prepare("DELETE FROM filmes WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Filme excluído com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir o filme.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
exit;
