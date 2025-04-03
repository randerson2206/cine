--tabela filmes 

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

--tabela generos

DROP TABLE IF EXISTS `generos`;
CREATE TABLE `generos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

