DROP TABLE IF EXISTS `generos`;
CREATE TABLE `generos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `generos` WRITE;
INSERT INTO `generos` VALUES (1,'Ação','Filmes de ação e aventura'),(2,'Comédia','Filmes de comédia e humor'),(3,'Drama','Filmes dramáticos com fortes emoções'),(4,'Terror','Filmes de suspense e terror'),(5,'Ficção Científica ','Filmes sobre o futuro e tecnologia');
UNLOCK TABLES;
