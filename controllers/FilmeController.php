<?php

class FilmeController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para cadastrar filme
    public function cadastrarFilme($titulo, $sinopse, $generos, $capa, $trailer, $data_lancamento, $duracao) {
        // Salvar imagem na pasta 'uploads/imagens'
        $capa_nome = time() . '-' . basename($capa['name']);
        $capa_destino = 'imagens/' . $capa_nome;

        // Move a imagem para a pasta
        if (move_uploaded_file($capa['tmp_name'], $capa_destino)) {
            // Insere os dados do filme no banco de dados
            $query = $this->db->prepare("INSERT INTO filmes (titulo, sinopse, capa, trailer, data_lancamento, duracao) VALUES (?, ?, ?, ?, ?, ?)");
            $query->execute([$titulo, $sinopse, $capa_destino, $trailer, $data_lancamento, $duracao]);

            $filme_id = $this->db->lastInsertId();

            // Relaciona o filme aos gêneros
            foreach ($generos as $genero_id) {
                $queryGenero = $this->db->prepare("INSERT INTO filme_genero (filme_id, genero_id) VALUES (?, ?)");
                $queryGenero->execute([$filme_id, $genero_id]);
            }

            return true;
        }
        return false;
    }

    // Método para excluir filme
    public function excluirFilme($id) {
        // Primeiro, buscamos a capa associada ao filme
        $stmt = $this->db->prepare("SELECT capa FROM filmes WHERE id = ?");
        $stmt->execute([$id]);
        $filme = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($filme) {
            // Exclui a imagem da pasta 'imagens'
            $capa_path = $filme['capa'];
            if (file_exists($capa_path)) {
                unlink($capa_path);
            }

            // Exclui as relações do filme com os gêneros
            $stmt = $this->db->prepare("DELETE FROM filme_genero WHERE filme_id = ?");
            $stmt->execute([$id]);

            // Exclui o filme da tabela filmes
            $stmt = $this->db->prepare("DELETE FROM filmes WHERE id = ?");
            $stmt->execute([$id]);

            return true;
        }

        return false;
    }
}
?>
