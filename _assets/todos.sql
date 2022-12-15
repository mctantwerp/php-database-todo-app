DROP TABLE IF EXISTS `todos`;
CREATE TABLE `todos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `text` varchar(255) DEFAULT NULL,
  `done` tinyint DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
