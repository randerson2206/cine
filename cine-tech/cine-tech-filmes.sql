CREATE TABLE filmes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    sinopse TEXT NOT NULL,
    capa VARCHAR(255) NULL,
    link VARCHAR(255) NULL,
    genero_id INT NULL,
    data_lancamento DATE NOT NULL,
    duracao INT NOT NULL,
    FOREIGN KEY (genero_id) REFERENCES generos(id) ON DELETE SET NULL
);
