<?php

class Filme {
    private $id;
    private $titulo;
    private $sinopse;
    private $capa;
    private $genero_id;
    private $link;
    private $data_lancamento;
    private $duracao;

    // Construtor para inicializar o objeto
    public function __construct($id = null, $titulo = null, $sinopse = null, $capa = null, $genero_id = null, $link = null, $data_lancamento = null, $duracao = null) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->sinopse = $sinopse;
        $this->capa = $capa;
        $this->genero_id = $genero_id;
        $this->link = $link;
        $this->data_lancamento = $data_lancamento;
        $this->duracao = $duracao;
    }

    // Getters e Setters

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getTitulo() { return $this->titulo; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }

    public function getSinopse() { return $this->sinopse; }
    public function setSinopse($sinopse) { $this->sinopse = $sinopse; }

    public function getCapa() { return $this->capa; }
    public function setCapa($capa) { $this->capa = $capa; }

    public function getGeneroId() { return $this->genero_id; }
    public function setGeneroId($genero_id) { $this->genero_id = $genero_id; }

    public function getLink() { return $this->link; }
    public function setLink($link) { $this->link = $link; }

    public function getDataLancamento() { return $this->data_lancamento; }
    public function setDataLancamento($data_lancamento) { $this->data_lancamento = $data_lancamento; }

    public function getDuracao() { return $this->duracao; }
    public function setDuracao($duracao) { $this->duracao = $duracao; }

    // Método para salvar o filme no banco de dados (inserir ou atualizar)
    public function salvar($db) {
        if ($this->id) {
            // Atualiza um filme existente
            $query = "UPDATE filmes 
                      SET titulo = :titulo, sinopse = :sinopse, capa = :capa, link = :link, 
                          genero_id = :genero_id, data_lancamento = :data_lancamento, duracao = :duracao 
                      WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $this->id);
        } else {
            // Insere um novo filme
            $query = "INSERT INTO filmes (titulo, sinopse, capa, link, genero_id, data_lancamento, duracao) 
                      VALUES (:titulo, :sinopse, :capa, :link, :genero_id, :data_lancamento, :duracao)";
            $stmt = $db->prepare($query);
        }

        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':sinopse', $this->sinopse);
        $stmt->bindParam(':capa', $this->capa);
        $stmt->bindParam(':link', $this->link);
        $stmt->bindParam(':genero_id', $this->genero_id);
        $stmt->bindParam(':data_lancamento', $this->data_lancamento);
        $stmt->bindParam(':duracao', $this->duracao);

        return $stmt->execute();
    }

    // Método para excluir o filme
    public function excluir($db) {
        if ($this->id) {
            // Exclui a capa do filme
            if (file_exists($this->capa)) {
                unlink($this->capa);
            }

            // Exclui as relações do filme com os gêneros
            $stmt = $db->prepare("DELETE FROM filme_genero WHERE filme_id = :id");
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();

            // Exclui o filme da tabela filmes
            $stmt = $db->prepare("DELETE FROM filmes WHERE id = :id");
            $stmt->bindParam(':id', $this->id);
            return $stmt->execute();
        }
        return false;
    }

    // Método estático para buscar um filme pelo ID
    public static function buscarPorId($db, $id) {
        $query = "SELECT * FROM filmes WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $filme = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($filme) {
            return new Filme($filme['id'], $filme['titulo'], $filme['sinopse'], $filme['capa'], $filme['genero_id'], $filme['link'], $filme['data_lancamento'], $filme['duracao']);
        }
        return null;
    }

    // Método estático para listar todos os filmes
    public static function listarTodos($db) {
        $query = "SELECT * FROM filmes";
        $stmt = $db->query($query);
        $filmes = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $filmes[] = new Filme($row['id'], $row['titulo'], $row['sinopse'], $row['capa'], $row['genero_id'], $row['link'], $row['data_lancamento'], $row['duracao']);
        }
        return $filmes;
    }
}
?>
