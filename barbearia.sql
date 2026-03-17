DROP DATABASE IF EXISTS `barbearia`;
CREATE DATABASE `barbearia` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `barbearia`;

CREATE TABLE `barbearias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'barbeiro',
  `barbearia_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuarios_email` (`email`),
  KEY `idx_usuarios_barbearia` (`barbearia_id`),
  CONSTRAINT `fk_usuarios_barbearias` FOREIGN KEY (`barbearia_id`) REFERENCES `barbearias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `barbearia_id` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `servico` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_clientes_barbearia` (`barbearia_id`),
  CONSTRAINT `fk_clientes_barbearias` FOREIGN KEY (`barbearia_id`) REFERENCES `barbearias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `servicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `barbearia_id` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `duracao_min` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_servicos_barbearia` (`barbearia_id`),
  CONSTRAINT `fk_servicos_barbearias` FOREIGN KEY (`barbearia_id`) REFERENCES `barbearias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `agendamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `barbearia_id` int NOT NULL,
  `cliente_id` int NOT NULL,
  `servico_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `data_hora` datetime NOT NULL,
  `status` enum('pendente','confirmado','concluido','cancelado') NOT NULL DEFAULT 'pendente',
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_agendamentos_barbearia` (`barbearia_id`),
  KEY `idx_agendamentos_cliente` (`cliente_id`),
  KEY `idx_agendamentos_servico` (`servico_id`),
  KEY `idx_agendamentos_usuario` (`usuario_id`),
  CONSTRAINT `fk_agendamentos_barbearias` FOREIGN KEY (`barbearia_id`) REFERENCES `barbearias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_agendamentos_clientes` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_agendamentos_servicos` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_agendamentos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `horarios_funcionamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `barbearia_id` int NOT NULL,
  `dia_semana` tinyint NOT NULL,
  `aberto` tinyint(1) NOT NULL DEFAULT 1,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_horario_barbearia_dia` (`barbearia_id`,`dia_semana`),
  CONSTRAINT `fk_horarios_barbearias` FOREIGN KEY (`barbearia_id`) REFERENCES `barbearias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pausas_usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `barbearia_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pausas_barbearia` (`barbearia_id`),
  KEY `idx_pausas_usuario` (`usuario_id`),
  CONSTRAINT `fk_pausas_barbearias` FOREIGN KEY (`barbearia_id`) REFERENCES `barbearias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pausas_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `barbearias` (`id`, `nome`, `email`, `telefone`, `endereco`) VALUES
(1, 'Barbearia Avanch Tech', 'contato@avanchtech.com', '(11) 99999-9999', 'Rua Exemplo, 123');

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `role`, `barbearia_id`) VALUES
(1, 'Administrador', 'admin@barbearia.com', '$2y$10$.fVQbmd8XxLUk8IDOQ8X1.E1Damp78/llN8uu1P8rg9wVUiCqZIvC', 'admin', 1),
(2, 'Carlos Barbeiro', 'carlos@barbearia.com', '$2y$10$.fVQbmd8XxLUk8IDOQ8X1.E1Damp78/llN8uu1P8rg9wVUiCqZIvC', 'barbeiro', 1);

INSERT INTO `servicos` (`id`, `barbearia_id`, `nome`, `preco`, `duracao_min`) VALUES
(1, 1, 'Corte tradicional', 35.00, 40),
(2, 1, 'Barba', 25.00, 30),
(3, 1, 'Corte + barba', 55.00, 60);

INSERT INTO `clientes` (`id`, `barbearia_id`, `nome`, `telefone`, `servico`) VALUES
(1, 1, 'Kaua', '1199999999', 'Barba'),
(2, 1, 'Marcos', '11988887777', 'Corte tradicional');

INSERT INTO `agendamentos` (`id`, `barbearia_id`, `cliente_id`, `servico_id`, `usuario_id`, `data_hora`, `status`, `observacoes`) VALUES
(1, 1, 1, 2, 2, '2026-03-16 14:00:00', 'confirmado', 'Cliente prefere acabamento na navalha'),
(2, 1, 2, 1, 1, '2026-03-16 16:00:00', 'pendente', 'Primeiro atendimento');

INSERT INTO `horarios_funcionamento` (`barbearia_id`, `dia_semana`, `aberto`, `hora_inicio`, `hora_fim`) VALUES
(1, 0, 0, NULL, NULL),
(1, 1, 1, '09:00:00', '19:00:00'),
(1, 2, 1, '09:00:00', '19:00:00'),
(1, 3, 1, '09:00:00', '19:00:00'),
(1, 4, 1, '09:00:00', '19:00:00'),
(1, 5, 1, '09:00:00', '19:00:00'),
(1, 6, 1, '09:00:00', '17:00:00');

INSERT INTO `pausas_usuarios` (`barbearia_id`, `usuario_id`, `data_inicio`, `data_fim`, `motivo`) VALUES
(1, 2, '2026-03-16 12:00:00', '2026-03-16 13:00:00', 'Almoco');
