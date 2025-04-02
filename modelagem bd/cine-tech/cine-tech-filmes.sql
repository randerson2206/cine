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
(40, 'Interstellar', 'Uma equipe de exploradores viaja por um buraco de minhoca no espaço na tentativa de garantir a sobrevivência da humanidade.', 'interstellar.jpg', 'https://www.youtube.com/watch?v=mbbPSq63yuM', 4),
(41, 'O Senhor dos Anéis: O Retorno do Rei', 'Gandalf e Aragorn lideram o Mundo dos Homens contra o exército de Sauron para distraí-lo de Frodo e Sam, que estão cada vez mais perto da Montanha da Perdição.', 'lotr_retornodorei.jpg', 'https://www.youtube.com/watch?v=r5X-hFf6Bwo', 2),
(42, 'Clube da Luta', 'Um homem deprimido que sofre de insônia conhece um vendedor de sabão e juntos formam um clube de luta secreto.', 'clubedaluta.jpg', 'https://www.youtube.com/watch?v=SUXWAEX2jlg', 3),
(43, 'Coringa', 'A história de origem do vilão mais icônico do Batman, explorando sua jornada até se tornar o Coringa.', 'coringa.jpg', 'https://www.youtube.com/watch?v=t433PEQGErc', 5),
(44, 'Vingadores: Ultimato', 'Os Vingadores se unem para reverter o estrago causado por Thanos e restaurar o universo.', 'vingadores.jpg', 'https://www.youtube.com/watch?v=TcMBFSGVi1c', 1);
UNLOCK TABLES;
