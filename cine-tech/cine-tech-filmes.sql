DROP TABLE IF EXISTS `filmes`;
CREATE TABLE `filmes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `sinopse` text DEFAULT NULL,
  `capa` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `genero_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `genero_id` (`genero_id`),
  CONSTRAINT `filmes_ibfk_1` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `filmes` WRITE;
INSERT INTO `filmes` VALUES 
UNLOCK TABLES;